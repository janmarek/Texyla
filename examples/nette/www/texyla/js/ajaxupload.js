/**
 * Ajax upload plugin
 * Odešle formulář a zavolá callback s JSON daty v parametru
 */

jQuery.fn.extend({
	ajaxUpload: function(callback) {
		if (!this.is("form")) return;

		// počítadlo
		if (!arguments.callee.count) {
			arguments.callee.count = 0;
		}
		var target = "ajaxUploadFrame" + (++arguments.callee.count);

		// vyrobí rámec
		var iframe = jQuery(
			'<iframe src="" width="1" height="1" frameborder="0" ' +
			'name="' + target + '"></iframe>'
		);
		iframe.css({
			visibility: "hidden",
			position: "absolute",
			left: "-1000px",
			top: "-1000px"
		});
		iframe.appendTo("body");

		// po načtení stránky zpracuje požadavek
		iframe.load(function () {
			jQuery.event.trigger("ajaxComplete");

			var iframeEl = iframe.get(0);
			var body;
			if (iframeEl.contentDocument) {
				body = iframeEl.contentDocument.body;
			} else {
				body = iframeEl.contentWindow.document.body;
			}
			var content = jQuery(body).text();

			if (!content) {
				callback();
			} else {
				eval("var data = " + content + ";");
				callback(data);
			}

			// nechat zmizet iframe
			setTimeout(function () {
				iframe.remove()
			}, 1000);
		});

		// odešle formulář do rámce
		this.attr({
			target: target,
			method: "post",
			enctype: "multipart/form-data"
		}).submit();

		jQuery.event.trigger("ajaxStart");
	}
});