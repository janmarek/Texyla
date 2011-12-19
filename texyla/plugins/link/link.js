jQuery.texyla.addWindow("link", {
	dimensions: [330, 180],

	createContent: function () {
		return jQuery(
			'<div><table><tbody><tr>' +
				'<th><label>' + this.lng.linkText + '</label></th>' +
				'<td><input type="text" class="link-text" value="' + this.texy.trimSelect().text() + '"></td>' +
			'</tr><tr>' +
				'<th><label>' + this.lng.linkUrl + '</label></th>' +
				'<td><input type="text" class="link-url" value="http://"></td>' +
			'</tr></tbody></table></div>'
		);
	},

	action: function (el) {
		var txt = el.find(".link-text").val();
		txt = txt == '' ? '' : '"' + txt + '":';
		this.texy.replace(txt + el.find(".link-url").val());
	}
});