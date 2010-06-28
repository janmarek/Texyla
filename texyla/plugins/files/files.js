/**
 * Files plugin
 */

jQuery.texyla.setDefaults({
	filesPath: null,
	filesThumbPath: '%var%',
	filesIconPath: "%texyla_base%/plugins/files/icons/%var%.png",
	filesUploadPath: null,
	filesMkDirPath: null,
	filesRenamePath: null,
	filesDeletePath: null
});

jQuery.texyla.initPlugin(function () {
	this.options.filesPath = this.expand(this.options.filesPath);
	this.options.filesThumbPath = this.expand(this.options.filesThumbPath);
	this.options.filesUploadPath = this.expand(this.options.filesUploadPath);
	this.options.filesMkDirPath = this.expand(this.options.filesMkDirPath);
	this.options.filesRenamePath = this.expand(this.options.filesRenamePath);
	this.options.filesDeletePath = this.expand(this.options.filesDeletePath);
});

jQuery.texyla.addWindow("files", {

	dimensions: [400, 350],

	createContent: function () {
		var _this = this;
		var currentDir = "";

		// template

		var el = jQuery(
			'<div>' +
				'<div class="toolbar">' +
					'<a href="" class="upload">' + this.lng.filesUpload + '</a> ' +
					'<a href="" class="mkdir">' + this.lng.filesMkDir + '</a>' +
				'</div>' +
				'<div class="files-filter">' + this.lng.filesFilter + ': <input type="text" class="ui-widget-content"></div>' +
				'<div class="files-gallery"></div>' +
				'<p class="wait">' + this.lng.wait + '</p>' +
			'</div>'
		);


		/**
		 * upload button
		 */

		el.find("div.toolbar a.upload").button({
			icons: {
				primary: "ui-icon-arrowthick-1-n"
			}
		}).click(function () {
			var win = _this.openWindow("upload");
			win.find("form input.folder").val(currentDir);
			el.dialog("close");
			return false;
		});


		/**
		 * mkdir button
		 * @todo
		 */

		el.find("div.toolbar a.mkdir").button({
			icons: {
				primary: "ui-icon-folder-collapsed"
			}
		}).click(function () {
			var name = prompt(_this.lng.filesDirectoryName, "");
			if (!name) return false;

			jQuery.getJSON(_this.options.filesMkDirPath, {
				folder: currentDir,
				name: name
			}, function (data) {
				if (data.error) {
					_this.error(data.error);
					return;
				}

				loadList(currentDir);
			});
			
			return false;
		});
		

		/**
		 * files quick filter
		 */
		
		var gallery = el.find("div.files-gallery");

		el.find("div.files-filter input").keyup(function () {
			var val = this.value;
			gallery.find(".gallery-item").each(function () {
				var item = $(this);

				if (val === "") {
					item.show();
					return;
				}

				if (item.find("span.name").text().indexOf(val) !== -1) {
					item.show();
				} else {
					item.hide();
				}
			});
		});
		

		function loadList(dir) {
			currentDir = dir;
			el.find("p.wait").show();
			el.find("div.toolbar, div.files-filter, div.files-gallery").hide();

			jQuery.ajax({
				type: "GET",
				dataType: "json",
				cache: false,
				url: _this.options.filesPath,
				data: {folder: currentDir},
				success: function (data) {
					if (data.error) {
						_this.error(data.error);
						return;
					}
					
					el.find("p.wait").hide();
					el.find("div.toolbar, div.files-filter, div.files-gallery").show();
					gallery.empty();

					// create files list

					var list = data.list;

					for (var i = 0; i < list.length; i++) {
						var type = list[i].type;

						var item = jQuery(
							'<table class="gallery-item ui-widget-content ui-corner-all"><tr>' +
								'<td class="image"></td><td class="label"></td>' +
							'</tr></table>'
						).appendTo(gallery);

						// icon
						if (type === "image") {
							item.find(".image").append('<image src="' + _this.expand(_this.options.filesThumbPath, list[i].thumbnailKey) + '">');
						} else {
							item.find(".image").append('<img src="' + _this.expand(_this.options.filesIconPath, list[i].type) + '" width="16" height="16" alt="">');
						}

						// text
						item.find(".label").append('<span class="name"><a href="">' + list[i].name + '</a></span>');
						if (list[i].description) {
							item.find(".label").append('<br><small class="description">' + list[i].description + '</small>');
						}

						// events
						var fnc;

						switch (list[i].type) {
							case "up":
							case "folder":
								fnc = function (dir) {
									return function () {
										loadList(dir.key)
										return false;
									}
								}(list[i]);
								break;
							case "image":
								fnc = function (img) {
									return function () {
										var winEl = _this.openWindow("img");
										winEl.find(".src").val(img.insertUrl);
										winEl.find(".alt").val(img.description).select();
										el.dialog("close");
										return false;
									}
								}(list[i]);
								break;
							case "file":
								fnc = function (file) {
									return function () {
										var winEl = _this.openWindow("link");
										winEl.find(".link-url").val(file.insertUrl);
										winEl.find(".link-text").val(file.description).select();
										el.dialog("close");
										return false;
									}
								}(list[i]);
								break;
						}
						
						item.find(".image img").click(fnc);
						item.find(".label span.name a").click(fnc);

						// buttons
						if (type !== "up") {
							var buttons = jQuery('<td class="buttons"></td>').appendTo(item.find("tr"));

							jQuery('<a href="" class="rename">rename</a>').button({
								icons: {
									primary: "ui-icon-pencil"
								},
								text: false
							}).click(function (file) {
								return function () {
									var newname = prompt(_this.lng.filesRename, file.name);

									if (!newname) return false;

									jQuery.getJSON(_this.options.filesRenamePath, {
										folder: currentDir,
										oldname: file.name,
										newname: newname
									}, function (data) {
										if (data.error) {
											_this.error(data.error);
											return;
										}

										loadList(currentDir);
									});

									return false;
								}
							}(list[i])).appendTo(buttons);

							jQuery('<a href="" class="delete">delete</a>').button({
								icons: {
									primary: "ui-icon-closethick"
								},
								text: false
							}).click(function (file) {
								return function () {
									if (!confirm(_this.lng.filesReallyDelete + " '" + file.name + "'?")) return false;

									jQuery.getJSON(_this.options.filesDeletePath, {
										folder: currentDir,
										name: file.name
									}, function (data) {
										if (data.error) {
											_this.error(data.error);
											return;
										}

										loadList(currentDir);
									});

									return false;
								}
							}(list[i])).appendTo(buttons);


						}
					}
				}
			});
		}

		loadList("");

		return el;
	}
});


/**
 * File upload
 */
jQuery.texyla.addWindow("upload", {
	dimensions: [330, 160],

	createContent: function () {
		return jQuery(
			'<div>' +
			'<form action="' + this.options.filesUploadPath + '" class="upload" method="post" enctype="multipart/form-data"><div>' +
				'<input type="hidden" name="folder" class="folder" value="">' +
				'<input type="file" name="file" class="file"> ' +
			'</div></form>' +
			'<p class="wait" style="display:none">' + this.lng.wait + '</p>' +
			'</div>'
		);
	},

	action: function (el) {
		var upload = el.find("form");

		if (!upload.find(".file").val()) return;

		el.ajaxStart(function () {
			upload.hide();
			el.find("p.wait").show();
		}).ajaxComplete(function () {
			el.dialog("close");
		});

		var _this = this;

		upload.ajaxUpload(function (data) {
			if (data.error) {
				_this.error(data.error);
			} else {
				if (data.type == "image") {
					var imgWin = _this.openWindow("img");
					imgWin.find(".src").val(data.filename);
					imgWin.find(".alt").focus();
				} else {
					var linkWin = _this.openWindow("link");
					linkWin.find(".link-url").val(data.filename);
					linkWin.find(".link-text").focus();
				}
			}
		});
	}
});