// Rozšířit jQuery o texylování
jQuery.fn.extend({
	texyla: function (options) {
		this.filter("textarea").each(function () {
			new Texyla(this, options);
		});
	}
});

// $.texyla();
jQuery.extend({
	texyla: function (options) {
		jQuery("textarea").texyla(options);
	}
});

// Texyla konstruktor
function Texyla(textarea, options) {
	// uloží nastavení
	this.options = jQuery.extend({}, this.defaultOptions, options || {});

	// uložím jQuery objekt textarey
	this.textarea = jQuery(textarea);

	// ochrana proti vícenásobnému ztexylování
	if (this.textarea.data("texyla")) return false;
	this.textarea.data("texyla", true);

	// nastavím jazyk
	var lng = this.options.language;
	if (!this.languages[lng]) {
		this.error("Language '" + lng + "' is not loaded.");
		return false;
	}
	this.lng = this.languages[lng];

	// náhrada za %texyla_base% v adresách
	this.baseDir = this.options.baseDir || this.baseDir;
	this.options.iconPath = this.expand(this.options.iconPath);
	this.options.previewPath = this.expand(this.options.previewPath);

	// vytvořím texy pro texylu
	this.texy = new Texy(this);

	// obalit ovládacíma blbostma
	this.wrap();

	// spustí pluginy
	for (var i = 0; i < this.initPlugins.length; i++) {
		this.initPlugins[i].apply(this);
	}
};

// nahradí v řetězci hodnoty za proměnné
Texyla.prototype.expand = function (text, variable) {
	text = text.replace("%texyla_base%", this.baseDir);
	if (variable) {
		text = text.replace("%var%", variable);
	}

	return text;
};

// pole funkcí zprovozňující pluginy
Texyla.prototype.initPlugins = [];

// příkazy
jQuery.texyla.setDefaults = function (defaults) {
	jQuery.extend(Texyla.prototype.defaultOptions, defaults);
};

jQuery.texyla.initPlugin = function (pluginInit) {
	Texyla.prototype.initPlugins.push(pluginInit);
};

jQuery.texyla.addButton = function (name, func) {
	Texyla.prototype.buttons[name] = func;
};

jQuery.texyla.extend = function (extendingObject) {
	jQuery.extend(Texyla.prototype, extendingObject);
};

jQuery.texyla.addStrings = function (lng, strings) {
	if (!Texyla.prototype.languages[lng]) {
		Texyla.prototype.languages[lng] = {};
	}

	jQuery.extend(Texyla.prototype.languages[lng], strings);
};

jQuery.texyla.setErrorHandler = function (handler) {
	Texyla.prototype.error = handler;
};

// Odeslat formulář
Texyla.prototype.submit = function () {
	var f = this.textarea.get(0).form;
	function submitnout() {
		if (f.submit.tagName == undefined) {
			f.submit();
		} else {
			f.submit.click();
		}
	}
	if (typeof f.onsubmit == 'function') {
		if (f.onsubmit()) {
			submitnout();
		}
	} else {
		submitnout();
	}
};

// chybový handler
Texyla.prototype.error = function (message) {
	alert("Error: " + message);
};

// výchozí adresář
Texyla.prototype.baseDir = jQuery("head script:last").attr("src").replace(/(\/js)?\/?[\w-]+\.js$/, '');

// jazyky
Texyla.prototype.languages = {};

// výchozí nastavení
Texyla.prototype.defaultOptions = {
	// šířka Texyly v pixelech
	width: null,
	// Odsazení textarey od krajů Texyly
	padding: 5,
	// výchozí konfigurace Texy: žádná
	texyCfg: "",
	// lišta
	toolbar: ['bold', 'italic', null, 'ul', 'ol', null, 'link', null, 'emoticon', 'symbol', "img", "table", null, ['web']],
	// tlačítka vlevo dole
	bottomLeftToolbar: ['edit', 'preview'],
	// tlačítka vpravo dole při editaci
	bottomRightEditToolbar: ['syntax'],
	// tlačítka vpravo dole při náhledu
	bottomRightPreviewToolbar: ['submit'],
	// typ tlačítek (span | button)
	buttonType: "span",
	// jestli bude levá dolní lišta zobrazena jako taby
	tabs: false,
	// výchozí pohled
	defaultView: "edit",
	// šířka ikon
	iconWidth: 16,
	// výška ikon
	iconHeight: 16,
	// adresář Texyly. Texyla se to pokusí zjistit automaticky, ale je to vhodné vyplnit.
	baseDir: null,
	// cesta k ikonkám
	iconPath: "%texyla_base%/icons/%var%.png",
	// cesta k náhledu
	previewPath: null,
	// jazyk
	language: "cs"
};