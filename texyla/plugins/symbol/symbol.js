// Výchozí zvláštní znaky
$.texyla.setDefaults({
	symbols: [
		"&", "@", ["<", "&lt;"], [">", "&gt;"], "[", "]", "{", "}", "\\", 
		"α", "β", "π", "µ", "Ω", "∑", "°", "∞", "≠", "±", "×", "÷", "≥",
		"≤", "®", "™", "€", "£", "$", "~", "^", "·", "•"
	]
});

$.texyla.addWindow("symbol", {
	dimensions: [300, 230],

	createContent: function () {
		var _this = this;
		
		var el = $('<div></div>');
		var symbolsEl = $('<div class="symbols"></div>').appendTo(el);

		var symbols = this.options.symbols;

		// projít symboly
		for (var i = 0; i < symbols.length; i++) {
			function clk(text) {
				return function () {
					_this.texy.replace(text);

					if (el.find("input.close-after-insert").get(0).checked) {
						el.dialog("close");
					}
				}
			};

			$("<span class='ui-state-default'></span>")
				.hover(function () {
					$(this).addClass("ui-state-hover");
				}, function () {
					$(this).removeClass("ui-state-hover");
				})
				.text(symbols[i] instanceof Array ? symbols[i][0] : symbols[i])
				.click(clk(symbols[i] instanceof Array ? symbols[i][1] : symbols[i]))
				.appendTo(symbolsEl);
		}

		// kontrolka na zavření po vložení
		el.append(
			"<br><label><input type='checkbox' checked class='close-after-insert'> " +
			this.lng.windowCloseAfterInsert + "</label>"
		);

		return el;
	}
});