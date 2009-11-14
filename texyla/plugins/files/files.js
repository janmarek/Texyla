// nahrávání obrázků

// nastavení cesty
$.texyla.setDefaults({
	filesPath: "%texyla_base%/../php/plugins/files/files.php",
	filesThumbPath: "%texyla_base%/../php/plugins/files/thumbs.php?image=%var%",
	filesIconPath: "%texyla_base%/plugins/files/icons/%var%.png",
	filesUploadPath: "%texyla_base%/../php/plugins/files/upload.php"
});

$.texyla.initPlugin(function () {
	this.options.filesPath = this.expand(this.options.filesPath);
	this.options.filesUploadPath = this.expand(this.options.filesUploadPath);
});

// okno
$.texyla.addWindow("files", {
	// rozměry
	dimensions: [400, 350],

	// obsah okna
	createContent: function () {
		var _this = this;

		var upload = $(
			'<form action="' + this.options.filesUploadPath + '" class="upload" method="post" enctype="multipart/form-data"><div>' +
				'<input type="hidden" name="folder" class="folder" value="">' +
				'<input type="file" name="file" class="file"> ' +
				'<input type="button" value="Nahrát" class="btn ui-state-default ui-corner-all">' +
			'</div></form>'
		);
		var gallery = $('<div class="gallery"></div>');

		var el = $('<div />').append(upload).append(gallery);

		// kliknutí na obrázek
		function createInsertImageFunc(img) {
			return function () {
				var winEl = _this.openWindow("img");
				winEl.find(".src").val(img.insertUrl);
				winEl.find(".alt").val(img.description).select();
				el.dialog("close");
			}
		};

		// kliknutí na soubor
		function createInsertFileFunc(img) {
			return function () {
				var winEl = _this.openWindow("link");
				winEl.find(".link-url").val(img.insertUrl);
				winEl.find(".link-text").val(img.description).select();
				el.dialog("close");
			}
		};

		// změna adresáře
		function createChangeDir(dir) {
			return function () {
				galleryReload(dir.key)
			}
		};

		// nahrávání souboru
		upload.find(".btn").hover(function () {
			$(this).addClass("ui-state-hover");
		}, function () {
			$(this).removeClass("ui-state-hover");
		}).click(function () {
			if (!upload.find(".file").val()) return;

			el.ajaxStart(function () {
				el.html('<p class="wait">' + _this.lng.wait + '</p>');
			}).ajaxComplete(function () {
				el.dialog("close");
			});

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
		});

		function galleryReload(currentDir) {
			gallery.empty().append('<p class="wait">' + _this.lng.wait + '</p>');
			upload.hide().find(".folder").val(currentDir);

			$.ajax({
				type: "GET",
				dataType: "json",
				cache: false,
				url: _this.options.filesPath,
				data: {folder: currentDir},
				success: function (data) {
					gallery.empty();
					upload.show();

					var list = data.list;

					for (var i = 0; i < list.length; i++) {
						var item = $(
							'<div class="gallery-item ui-state-default ui-corner-all">' +
								'<table><tr>' +
									'<td class="image"></td><td class="label"></td>' +
								'</tr></table>' +
							'</div>'
						).hover(function () {
								$(this).addClass("ui-state-hover");
						}, function () {
								$(this).removeClass("ui-state-hover");
						}).appendTo(gallery);


						switch (list[i].type) {
							case "up":
							case "folder":
								item.click(createChangeDir(list[i]));

								item.find(".image").append(
									'<img src="' + _this.expand(_this.options.filesIconPath, list[i].type) +
									'" width="16" height="16" alt="">'
								);

								item.find(".label").text(list[i].name);

								break;
							case "image":
								item.click(createInsertImageFunc(list[i]));

								item.find(".image").append(
									'<image src="' + _this.expand(_this.options.filesThumbPath, list[i].thumbailKey) + '">'
								);

								item.find(".label").append(
									list[i].name + '<br>' +
									'<small>' + list[i].description + '</small>'
								);

								break;
							case "file":
								item.click(createInsertFileFunc(list[i]));

								item.find(".image").append(
									'<img src="' + _this.expand(_this.options.filesIconPath, "file") +
									'" width="16" height="16" alt="">'
								);

								item.find(".label").append(
									list[i].name + '<br>' +
									'<small>' + list[i].description + '</small>'
								);

								break;
						}
					}
				}
			});
		}

		galleryReload("");

		return el;
	}
});