jQuery.texyla.addWindow("textTransform", {
	createContent: function () {
		return jQuery(
			"<div><form>" +
			"<label><input type='radio' name='changeCase' value='lower'> " + this.lng.textTransformLower + "</label><br>" +
			"<label><input type='radio' name='changeCase' value='upper'> " + this.lng.textTransformUpper + "</label><br>" +
			"<label><input type='radio' name='changeCase' value='firstUpper'> " + this.lng.textTransformFirstUpper + "</label><br>" +
			"<label><input type='radio' name='changeCase' value='cap'> " + this.lng.textTransformCapitalize + "</label><br>" +
			"<label><input type='radio' name='changeCase' value='url'> " + this.lng.textTransformUrl + "</label>" +
			"</form></div>"
		);
	},

	action: function (el) {
		var text = this.texy.update().text();
		var newText = null;

		var transformation = el.find("form input:checked").val();

		switch (transformation) {
			case "lower":
				newText = text.toLowerCase();
				break;
			case "upper":
				newText = text.toUpperCase();
				break;
			case "cap":
				newText = text.replace(/\S+/g, function (a) {
					return a.charAt(0).toUpperCase() + a.substr(1, a.length).toLowerCase();
				});
				break;
			case "firstUpper":
				newText = text.charAt(0).toUpperCase() + text.substr(1, text.length).toLowerCase();
				break;
			case "url":
				// (c) Jakub Vrána, http://php.vrana.cz
				var nodiac = {
					'á': 'a', 'č': 'c', 'ď': 'd', 'é': 'e', 'ě': 'e', 'í': 'i', 'ň': 'n',
					'ó': 'o', 'ř': 'r', 'š': 's', 'ť': 't', 'ú': 'u', 'ů': 'u', 'ý': 'y',
					'ž': 'z'
				};

				var s = text.toLowerCase();
				var s2 = '';
				for (var i=0; i < s.length; i++) {
					s2 += (typeof nodiac[s.charAt(i)] != 'undefined' ? nodiac[s.charAt(i)] : s.charAt(i));
				}
				newText = s2.replace(/[^a-z0-9_]+/g, '-').replace(/^-|-$/g, '');
				break;
			default:
		}

		// replace
		if (newText !== null) {
			this.texy.replace(newText);
		}
	},

	dimensions: [220, 210]
});