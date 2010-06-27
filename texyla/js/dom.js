/**
 *	Obalit textareu ovládacíma věcma
 */
Texyla.prototype.wrap = function () {
	/* kontejner */
	this.container = this.textarea.wrap('<div class="texyla"></div>').parent();

	var containerWidth = this.options.width || this.textarea.get(0).offsetWidth || this.textarea.width();
	this.container.width(containerWidth);

	/* div s textareou */
	this.editDiv = this.textarea.wrap('<div class="textarea-container"></div>')
		.parent().wrap('<div class="edit-div"></div>').parent();

	// nastavím šířku
	if (this.textarea.get(0).offsetWidth > 0) { // viditelná textarea
		this.textarea.width(containerWidth);
		var delta = this.textarea.get(0).offsetWidth - containerWidth;
	} else {
		var delta = 0;
	}

	this.textarea.width(containerWidth - delta - 2 * this.options.padding);

	// uložit výšku textarey
	this.textareaHeight = this.textarea.get(0).offsetHeight;

	/* div s náhledem */
	this.previewDiv = jQuery('<div class="preview-div"></div>').insertAfter(this.editDiv);
	// hlavička
	this.previewDiv.prepend(
		'<div class="view-header" style="background-image: url(\'' +
		this.expand(this.options.iconPath, "preview") + '\');">' +
		this.lng.btn_preview + '</div>'
	);

	this.preview = jQuery('<div class="preview"></div>')
		.appendTo(this.previewDiv)
		.wrap('<div class="preview-wrapper ui-widget-content"></div>');

	/* div s html náhledem */
	this.htmlPreviewDiv = jQuery('<div class="html-preview-div"></div>').insertAfter(this.previewDiv);
	// hlavička
	this.htmlPreviewDiv.prepend(
		'<div class="view-header" ' +
		'style="background-image: url(\'' + this.expand(this.options.iconPath, "htmlPreview") + '\');">' +
		this.lng.btn_htmlPreview + '</div>'
	);

	this.htmlPreview = jQuery('<pre class="html-preview"></pre>')
		.appendTo(this.htmlPreviewDiv)
		.wrap('<div class="preview-wrapper ui-widget-content"></div>');


	// čekejte
	this.wait = jQuery('<div class="preview-wait">' + this.lng.wait + '</div>');

	// vyrobím tlačítka
	this.createToolbar();
	this.createBottomToolbar();

	// zapnu pohled
	this.view(this.options.defaultView, true);
};

/**
 *	vyrobí horní lištu
 */
Texyla.prototype.createToolbar = function () {
	// lišta
	var toolbar = jQuery('<ul class="toolbar"></ul>').prependTo(this.editDiv);
	var item, toolbar2;

	// prochází lištu
	for (var i = 0; i < this.options.toolbar.length; i++) {
		// aktuální položka
		item = this.options.toolbar[i];

		// tlačítko
		if (typeof item == "string") {
			jQuery(
				"<span title='" + this.lng["btn_" + item] + "'>" +
				"<img src='" + this.expand(this.options.iconPath, item) +
				"' width='" + this.options.iconWidth + "' height='" + this.options.iconHeight + "'>" +
				"</span>"
			)
				.click(this.clickButton(item))
				.appendTo("<li class='btn_" + item + "'></li>").parent()
				.appendTo(toolbar);
		}

		// separator
		else if (item === null) {
			toolbar.append("<li class='separator'></li>");
		}

		// podmenu
		else if (jQuery.isArray(item)) {
			toolbar2 = jQuery("<ul class='ui-widget-content ui-corner-all'></ul>");
			var menuTimeout;
			toolbar2.appendTo("<li class='menu'></li>").parent().mouseover(function () {
				// prevence proti zmizení
				clearTimeout(menuTimeout);
				// schovat ostatní menu
				jQuery(this).siblings().find("ul:visible").fadeOut("fast");
				// zobrazit
				jQuery(this).find("ul").show();
			}).mouseout(function () {
				// po chvíli zmizí
				var _this = this;
				menuTimeout = setTimeout(function () {
					jQuery(_this).find("ul").fadeOut("fast");
				}, 300);
			}).appendTo(toolbar);

			// jednotlivé položky v menu
			for (var j = 0; j < item.length; j++) {
				jQuery(
					"<li class='btn_" + item[j] + " ui-corner-all'>" +
					"<span style='background-image: url(\"" + this.expand(this.options.iconPath, item[j]) + "\");'>" +
					this.lng["btn_" + item[j]] + "</span></li>"
				)
					.hover(function () {
						jQuery(this).addClass("ui-state-hover");
					}, function () {
						jQuery(this).removeClass("ui-state-hover");
					})
					.click(this.clickButton(item[j]))
					.appendTo(toolbar2);
			}

		// label
		} else if (typeof(item) == "object" && item.type == "label") {
			var text = item.translatedText ? this.lng[item.translatedText] : item.text;
			toolbar.append("<li class='label ui-state-disabled'>" + text + "</li>");
		}
	}
};

/**
 *	Vrátí funkci, která se přiřadí tlačítkům po kliknutí
 *	@param string name název funkce
 *	@return function
 */
Texyla.prototype.clickButton = function (name) {
	var _this = this;

	if (name in this.buttons) {
		return function (e) {
			_this.buttons[name].call(_this, e);
		};
	} else {
		return function () {
			_this.error('Function "' + name + '" is not supported!');
		};
	}
};

/**
 *	Vyrobí spodní lišty
 */
Texyla.prototype.createBottomToolbar = function () {
	// vytvořit lišty
	var bottomToolbar = jQuery("<div class='bottom-toolbar'></div>").appendTo(this.container);
	this.leftToolbar = jQuery("<div class='left-toolbar'></div>").appendTo(bottomToolbar);
	var right = jQuery('<div class="right-toolbar"></div>').appendTo(bottomToolbar);
	this.rightEditToolbar = jQuery("<div class='right-edit-toolbar'></div>").appendTo(right);
	this.rightPreviewToolbar = jQuery("<div class='right-preview-toolbar'></div>").appendTo(right);

	// přidat CSS třídy

	// když to jsou taby
	if (this.options.tabs) {
		this.leftToolbar.addClass("tabs");
	// nebo jsou tlačítka typu span
	} else if (this.options.buttonType == "span") {
		this.leftToolbar.addClass("span-tb");
	}

	// pravá lišta s tlačítkami typu span?
	if (this.options.buttonType == "span") {
		right.addClass("span-tb");
	}

	// přidat tlačítka
	var _this = this;

	// vyrobit tlačítko
	function createButton(icon, name, func, tabs) {
		var iconUrl = _this.expand(_this.options.iconPath, icon);

		// tlačítko typu span
		if (_this.options.buttonType == "span" || tabs) {
			return jQuery(
				"<span class='btn btn_" + icon + " ui-state-default " + (tabs ? "ui-corner-bottom" : "ui-corner-all") + "'>" +
					"<span class='btn-left'></span><span class='btn-middle'>" +
					"<span style='background-image: url(\"" +  iconUrl + "\");' class='icon-span'>" + name + "</span>" +
					"</span><span class='btn-right'></span>" +
				"</span>"
			).click(func).hover(function () {
					jQuery(this).addClass("ui-state-hover");
			}, function () {
					jQuery(this).removeClass("ui-state-hover");
			});

		// klasické tlačítko
		} else {
			return jQuery(
				"<button type='button' class='btn_" + icon + "'>" +
				"<img src='" + iconUrl + "' width='" + _this.options.iconWidth + "' height='" + _this.options.iconHeight + "'>" +
				" " + name + "</button>"
			).click(func);
		}
	};

	// vyplnit lištu tlačítky
	function insertButtons(toolbar, buttons, tabs) {
		for (var i = 0; i < buttons.length; i++) {
			createButton(
				buttons[i],
				_this.lng["btn_" + buttons[i]],
				_this.clickButton(buttons[i]),
				tabs
			).appendTo(toolbar);
		}
	};

	// vložit tlačítka
	insertButtons(this.leftToolbar, this.options.bottomLeftToolbar, this.options.tabs);
	insertButtons(this.rightEditToolbar, this.options.bottomRightEditToolbar);
	insertButtons(this.rightPreviewToolbar, this.options.bottomRightPreviewToolbar);
};