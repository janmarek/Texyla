// Změnit pohled
Texyla.prototype.view = function(type, first) {
	// textarea value
	var taVal = this.textarea.val();

	// prázdná textarea
	if (type != "edit" && taVal == "") {
		// poprvé nebuzerovat a bez keců přepnout
		if (first) {
			this.view("edit");
			return;
		}

		alert(this.lng.viewEmpty);
		this.textarea.focus();

		return;
	}

	// schovávání a odkrývání
	switch (type) {
		// náhled
		case "preview":
			this.previewDiv.show();
			this.htmlPreviewDiv.hide();
			this.editDiv.hide();
			this.rightPreviewToolbar.show();
			this.rightEditToolbar.hide();
		break;

		// html náhled
		case "htmlPreview":
			this.previewDiv.hide();
			this.htmlPreviewDiv.show();
			this.editDiv.hide();
			this.rightPreviewToolbar.show();
			this.rightEditToolbar.hide();
		break;

		// upravovat
		case "edit":
			this.previewDiv.hide();
			this.htmlPreviewDiv.hide();
			this.editDiv.show();
			this.rightPreviewToolbar.hide();
			this.rightEditToolbar.show();
		break;
	}

	// výška náhledů
	if (type != "edit") {
		var height = this.textarea.get(0).offsetHeight || this.textareaHeight;
		if (height) {
			var curPrev = this[type == "preview" ? "preview" : "htmlPreview"].parent();
			curPrev.height(height);
			var delta = curPrev.get(0).offsetHeight - height;
			this.container.find("div.preview-wrapper").height(height - delta);
		} else {
			this.container.find("div.preview-wrapper").height("auto");
		}
	}

	// zvýraznění aktivního tabu
	if (this.options.tabs) {
		var tabs = this.leftToolbar;
		tabs.find(".ui-state-active").removeClass("ui-state-active");
		tabs.find(".btn_" + type).addClass("ui-state-active");

	// schovávání tlačítka aktivního pohledu
	} else {
		var views = ["preview", "htmlPreview", "edit"];
		for (var i = 0; i < views.length; i++) {
			if (views[i] == type) {
				this.container.find(".btn_" + type).hide();
			} else {
				this.container.find(".btn_" + views[i]).show();
			}
		}
	}

	// načtení náhledu
	if (type != "edit" && this.lastPreviewedTexy != taVal) {
		// při načtení náhledu
		var _this = this;
		function onLoad(data) {
			// náhled
			_this.preview.html(data).show();

			// náhled html
			_this.htmlPreview.text(data.replace(new RegExp("\n", "g"), _this.texy.lf())).show();

			// obarvit html pomocí JUSHe
			if (typeof jush != 'undefined') {
				_this.htmlPreview.html(jush.highlight("htm", data));
			}

			// schovat čekejte
			_this.wait.hide();
		};

		// kešuje poslední texy
		this.lastPreviewedTexy = taVal;

		// zobrazí prosím čekejte
		var parent = this[type == "preview" ? "preview" : "htmlPreview"].parent();

		parent.prepend(this.wait);

		this.wait.show().css({
			marginTop: (parent.get(0).offsetHeight - this.wait.get(0).offsetHeight) / 2,
			marginLeft: (parent.get(0).offsetWidth - this.wait.get(0).offsetWidth) / 2
		});

		// a schová staré obsahy náhledů
		this.preview.hide();
		this.htmlPreview.hide();

		// volá ajax
		jQuery.post(this.options.previewPath, {texy: taVal, cfg: this.options.texyCfg}, onLoad, "html");
	}
};