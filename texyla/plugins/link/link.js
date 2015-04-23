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
			'</tr><tr>'+
				'<th><label>'+ this.lng.linkTargetBlank + '</label></th>' +
				'<td><input type="checkbox" class="link-target-blank"></td>' +
			'</tr></tbody></table></div>'
		);
	},

	action: function (el) {
		var txt = el.find(".link-text").val();
		var targetBlank = el.find(".link-target-blank").is(":checked");
		var link = el.find(".link-url").val();

		if(txt != '') {
			if(targetBlank == true) {
				txt = '"' + txt + ' .{target:_blank}":';
			} else {
				txt = '"' + txt + '":';
			}
		}
		else {
			if(targetBlank == true) {
				txt = '"' + link + ' .{target:_blank}":';
			}
		}

		this.texy.replace(txt + link);
	}
});