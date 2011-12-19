// ovládání klávesami

// funkce zavádějící ovládání klávesami
jQuery.texyla.initPlugin(function () {
	var _this = this;

	this.textarea.bind(jQuery.browser.opera ? "keypress" : "keydown", function(e) {
		_this.keys(e);
	});
});


jQuery.texyla.extend({
	keys: function(e) {
		var pressedKey = e.charCode || e.keyCode || -1;

		var action = false;

		// tučně (Ctrl + B nebo např. Shift + Ctrl + B)
		if (e.ctrlKey && pressedKey == 66 && !e.altKey) {
			this.texy.bold();
			action = true;
		}

		// kurzíva (Ctrl + I nebo např. Alt + Ctrl + I)
		if (e.ctrlKey && pressedKey == 73) {
			this.texy.italic();
			action = true;
		}

		// Zrušit odsazení (shift + tab)
		if (pressedKey == 9 && e.shiftKey) {
			this.texy.unindent();
			action = true;
		}

		// tabulátor (tab)
		if (pressedKey == 9 && !e.shiftKey) {
			if (this.texy.update().text().indexOf(this.texy.lf()) == -1) {
				this.texy.tag('\t', '');
			} else {
				this.texy.indent();
			}
			action = true;
		}

		// Odeslat formulář (Ctrl + S nebo např. Shift + Ctrl + S)
		if (e.ctrlKey && pressedKey == 83) {
			this.submit();
			action = true;
		}

		// zruší defaultní akce
		if (action) {
			// Firefox & Opera (ale ta na to docela sere co se týče klávesových zkratek programu)
			if (e.preventDefault && e.stopPropagation) {
				e.preventDefault();
				e.stopPropagation();

			// IE
			} else {
				window.event.cancelBubble = true;
				window.event.returnValue = false;
			}
		}
	}
});