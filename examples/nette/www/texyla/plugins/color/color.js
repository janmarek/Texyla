/**
 * Color plugin
 * Přidává obarvování textu a/nebo pozadí do Texyly.
 * @author Petr Vaněk aka krteczek
 */

jQuery.texyla.setDefaults({
	colors: [
	'red', 'blue', 'aqua', 'black', 'fuchsia', 'gray', 'green', 'lime',
	'maroon', 'navy', 'olive', 'orange', 'purple', 'silver', 'teal',
	'white', 'yellow', '#AABBCC'
	]
});

jQuery.texyla.addWindow("color", {
	createContent: function () {
		var _this = this;
		var colors = jQuery('<div></div>');
		var colorsEl = jQuery('<div class="colors"></div>').appendTo(colors);

		// vloží kód pro obarvení elementu
		function colorClk(color) {
			return function () {
				_this.texy.update();

				// Přidání obarvovacího kódu do textu
				if (_this.texy.isCursor()) {
					_this.texy.selectBlock().phrase('' , ' .{color: ' + color + '}');
				} else {
					_this.texy.phrase('"' , ' .{color: ' + color + '}"');
				}

				// zavření okna po vložení kódu
				if (colors.find("input.close-after-insert").get(0).checked) {
					colors.dialog("close");
				}
			}
		}

		// vytvoření jednotlivých barevných tlačítek
		for (var i = 0; i < _this.options.colors.length; i++) {
			var color = _this.options.colors[i];
			jQuery(
				'<span class="ui-state-default ui-corner-all" title="' + color + '">' +
				'<span style="background-color:' + color + '">&nbsp;</span>' +
				'</span>'
			).hover(function () {
				jQuery(this).addClass("ui-state-hover");
			}, function() {
				jQuery(this).removeClass("ui-state-hover");
			}).click(colorClk(color)).appendTo(colorsEl);
		}

		colors.append(
			"<br><label><input type='checkbox' checked class='close-after-insert'> " +
			this.lng.windowCloseAfterInsert + "</label>"
		);

		return colors;
	},

	dimensions: [200, 150]
});
