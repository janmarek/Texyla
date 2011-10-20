// Okno obrázku
jQuery.texyla.addWindow('img', {
	createContent: function () {
		return jQuery(
			'<div><table><tbody><tr>' +
				// Adresa
				'<th><label>' + this.lng.imgSrc + '</label></th>' +
				'<td><input type="text" class="src"></td>' +
			'</tr><tr>' +
				// Alt
				'<th><label>' + this.lng.imgAlt + '</label></th>' +
				'<td><input type="text" class="alt"></td>' +
			'</tr><tr>' +
				// Zobrazit jako popisek
				'<td></td>' +
				'<td><label><input type="checkbox" class="descr">' + this.lng.imgDescription + '</label></td>' +
			'</tr><tr>' +
				// Zarovnání
				'<th><label>' + this.lng.imgAlign + '</label></th>' +
				'<td><select class="align">' +
					'<option value="*">' + this.lng.imgAlignNone + '</option>' +
					'<option value="<">' + this.lng.imgAlignLeft + '</option>' +
					'<option value=">">' + this.lng.imgAlignRight + '</option>' +
					'<option value="<>">' + this.lng.imgAlignCenter + '</option>' +
				'</select></td>' +
			'</tr><tr>' +
				'<th><label>' + this.lng.imgSize + '</label></th>' +
				'<td>' +
					'<input type="text" class="width" size="4" placeholder="' + this.lng.imgWidth +'">' +
					'<select class="asMax">' +
						'<option value="x">' + this.lng.imgNotAsMax + '</option>' +
						'<option value="X">' + this.lng.imgAsMax + '</option>' +
					'</select>' +
					'<input type="text" class="height" size="4" placeholder="' + this.lng.imgHeight +'">' +
				'</td>' +
			'</tr>' +
			'</tbody></table></div>'
		);
	},

	action: function (el) {
		this.texy.img(
			el.find('.src').val(),
			el.find('.alt').val(),
			el.find('.align').val(),
			el.find('.descr').get(0).checked,
			el.find('.width').val(),
			el.find('.asMax').val(),
			el.find('.height').val()
		);
	},
	
	dimensions: [350, 'auto']
});