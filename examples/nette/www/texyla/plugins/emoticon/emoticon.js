// nastavení
$.texyla.setDefaults({
	emoticonPath: "%texyla_base%/emoticons/texy/%var%.gif",
	emoticons: {
		':-)': 'smile',
		':-(': 'sad',
		';-)': 'wink',
		':-D': 'biggrin',
		'8-O': 'eek',
		'8-)': 'cool',
		':-?': 'confused',
		':-x': 'mad',
		':-P': 'razz',
		':-|': 'neutral'
	}
});

$.texyla.initPlugin(function () {
	this.options.emoticonPath = this.expand(this.options.emoticonPath);
});

$.texyla.addWindow("emoticon", {
	createContent: function () {
		var _this = this;

		var emoticons = $('<div></div>');
		var emoticonsEl = $('<div class="emoticons"></div>').appendTo(emoticons);

		// projít smajly
		for (var i in this.options.emoticons) {
			function emClk(emoticon) {
				return function () {
					_this.texy.replace(emoticon);

					if (emoticons.find("input.close-after-insert").get(0).checked) {
						emoticons.dialog("close");
					}
				}
			};

			$(
				"<img src='" + this.options.emoticonPath.replace("%var%", this.options.emoticons[i]) +
				"' title='" + i + "' alt='" + i + "' class='ui-state-default'>"
			)
				.hover(function () {
					$(this).addClass("ui-state-hover");
				}, function () {
					$(this).removeClass("ui-state-hover");
				})
				.click(emClk(i))
				.appendTo(emoticonsEl);
		}

		emoticons.append("<br><label><input type='checkbox' checked class='close-after-insert'> " + this.lng.windowCloseAfterInsert + "</label>");

		return emoticons;
	},

	dimensions: [192, 170]
});