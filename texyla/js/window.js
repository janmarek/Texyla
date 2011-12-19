jQuery.texyla.initPlugin(function () {
	// seznam otevřených oken
	// 		název: jQuery objekt
	this.openedWindows = {};
});

/**
 *	Přidat okno
 *	bude možné zavolat $.texyla.addWindow({title: "Okno", ...});
 */
jQuery.texyla.addWindow = function (name, options) {
	Texyla.prototype.windowConfigs[name] = options;

	// nastavit velikosti okna
	if (options.dimensions) {
		var defaults = {};
		defaults[name + "WindowDimensions"] = options.dimensions;
		jQuery.texyla.setDefaults(defaults);
	}

	// přidat tlačítko
	jQuery.texyla.addButton(name, function () {
		this.openWindow(name);
	});
};

jQuery.texyla.extend({
	// možná okna
	windowConfigs: {},

	openWindow: function (name) {
		// kontrola
		if (typeof(jQuery.fn.dialog) != "function") {
			this.error("jQuery UI plugin Dialog is not loaded.");
			return false;
		}

		if (!Texyla.prototype.windowConfigs[name]) {
			this.error("Window " + name + " is not defined.");
			return false;
		}

		// focusovat otevřené
		if (this.isWindowOpened(name)) {
			return this.getWindow(name).dialog("moveToTop");
		}

		// otevřít nové
		var config = Texyla.prototype.windowConfigs[name];
		var el = config.createContent.call(this);

		// přiřadit do otevřených oken
		this.openedWindows[name] = el;

		// nastavení dialogu
		var options = config.options || {};

		// titulek
		options.title = config.title ? config.title : this.lng["win_" + name];

		// rozměry
		var dimensions = this.options[name + "WindowDimensions"];
		if (dimensions) {
			options.width = dimensions[0];
			options.height = dimensions[1];
		}

		// tlačítka
		var _this = this;
		if (config.action) {
			options.buttons = {};

			// tlačítko OK
			options.buttons[this.lng.windowOk] = function () {
				config.action.call(_this, el);
				if (!config.stayOpened) {
					_this.closeWindow(name);
				}
			};

			// tlačítko Storno
			options.buttons[this.lng.windowCancel] = function () {
				_this.closeWindow(name);
			};
		}

		// zavření
		options.close = function () {
			_this.closeWindow(name);
		};

		// vytvořit dialog
		el.dialog(options);

		// focus na první input
		el.find("input:first").focus();

		return el;
	},

	closeWindow: function (name) {
		// zrušení objektu v domu
		this.openedWindows[name].dialog("destroy").remove();
		// vynulování
		this.openedWindows[name] = null;
	},

	isWindowOpened: function (name) {
		return this.openedWindows[name] ? true : false;
	},

	getWindowAction: function (name) {
		return Texyla.prototype.windowConfigs[name].action;
	},

	/**
	 *	Získat objekt okna
	 *	@param string name jméno okna
	 *	@return jQuery|null
	 */
	getWindow: function (name) {
		return this.openedWindows[name] ? this.openedWindows[name] : null;
	}
});