jQuery.texyla.addWindow("table", {
	dimensions: [320, 200],

	action: function (cont) {
		this.texy.table(cont.find(".cols").val(), cont.find(".rows").val(), cont.find(".header").val());
	},

	createContent: function () {
		var _this = this;

		var cont = jQuery(
			"<div style='position:relative'>" +
				'<table class="table"><tbody>' +
				'<tr><th><label>' + this.lng.tableCols + '</label></th><td><input type="number" class="cols" size="3" maxlength="2" min="1" value="2"></td></tr>' +
				'<tr><th><label>' + this.lng.tableRows + '</label></th><td><input type="number" class="rows" size="3" maxlength="2" min="1" value="2"></td></tr>' +
				'<tr><th><label>' + this.lng.tableTh + '</label></th><td><select class="header">' +
				'<option>' + this.lng.tableThNone + '</option>' +
				'<option value="n">' + this.lng.tableThTop + '</option>' +
				'<option value="l">' + this.lng.tableThLeft + '</option>' +
				'</select></td></tr></tbody></table>' +

				// vizuální tabulka - html
				'<div class="tab-background"><div class="tab-selection"></div><div class="tab-control"></div></div>' +
			"</div>"
		);

		// vizuální tabulka
		var resizing = true, posX, posY;

		// povolení nebo zakázání změny velikosti po kliku
		cont.find(".tab-control").click(function (e) {
			resizing = !resizing;

		// změny velikosti apos
		}).mousemove(function (e) {
			if (resizing) {
				posX = e.pageX;
				var el = this;
				while (el.offsetParent) {
					posX -= el.offsetLeft;
					el = el.offsetParent;
				}

				posY = e.pageY;
				el = this;
				while (el.offsetParent) {
					posY -= el.offsetTop;
					el = el.offsetParent;
				}

				var cols = Math.ceil(posX / 8);
				var rows = Math.ceil(posY / 8);

				cont.find(".tab-selection").css({
					width: cols * 8,
					height: rows * 8
				});

				cont.find(".cols").val(cols);
				cont.find(".rows").val(rows);
			}

		// vložení na dvojklik
		}).dblclick(function () {
			_this.getWindowAction("table").call(_this, cont);
			cont.dialog("close");
		});

		cont.find(".cols, .rows").bind("change click blur", function () {
			var cols = Math.min(cont.find(".cols").val(), 10);
			var rows = Math.min(cont.find(".rows").val(), 10);

			cont.find(".tab-selection").css({
				width: cols * 8,
				height: rows * 8
			});
		});

		return cont;
	}
});