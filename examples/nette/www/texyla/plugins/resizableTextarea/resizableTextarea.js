// Zvětšovací textarea
jQuery.texyla.initPlugin(function () {
	// pokud není načteno jQuery UI resizable, nic se nedělá
	if (typeof(this.textarea.resizable) != "function") return;

	var _this = this;
	this.textarea.resizable({
		handles: 's',
		minHeight: 80,
		stop: function () {
			_this.textareaHeight = _this.textarea.get(0).offsetHeight;
		}
	});

	// fix
	this.textarea.parent().css("padding-bottom", 0);
});