/**
 *	Selection
 *	obsluhuje výběr v textaree
 *	@author Jan Marek
 */
function Selection(ta) {
	this.textarea = ta;
};

Selection.prototype = {

	// oddělovač řádků
	lineFeedFormat: null,

	// jestli jsme si jisti s formátem oddělovače řádků
	lineFeedKnown: false,

	/**
	 *	Zjišťuje, zdali je prohlížeč internet explorer
	 */
	isIe: function () {
		// Opera, Firefox
		if (this.textarea.selectionStart || this.textarea.selectionStart === 0) {
			return false;

		// IE
		} else if (document.selection) {
			return true;
		}

		return null;
	},

	// obalí výběr (firstTexy + výběr + secondText)
	tag: function (firstText, secondText) {
		this.update();

		this.changeSelection(firstText + this.text() + secondText);

		// je li obalen kurzor
		if (this.isCursor()) {
			this.select(this.start + firstText.length, 0);

		// či výběr
		} else {
			this.select(this.start, firstText.length + this.length() + secondText.length);
		}


	},

	// nahradí výběr proměnnou replacement
	replace: function (replacement) {
		if (replacement === null) return;

		this.update();

		this.changeSelection(replacement);

		this.select(this.start, replacement.length);
	},

	// odstraní případnou jednu mezeru vpravo z výběru
	trimSelect: function () {
		this.update();

		if (this.text().substring(this.length(), this.length() - 1) == " ") {
			this.select(this.start, this.length() - 1);
		}

		return this.update();
	},

	// odstraní případnou jednu mezeru vpravo z výběru a zavolá funkci this.tag()
	// FF & IE fix (po dvojkliku na slovo vybere i mezeru za ním)
	phrase: function (firstText, secondText) {
		this.trimSelect().tag(firstText, secondText ? secondText : firstText);
	},

	// změna výběru
	changeSelection: function (replacement) {
		// Kolik je odrolováno
		var scrolled = this.textarea.scrollTop;

		// Změna textu v textaree
		var val = this.textarea.value;
		this.textarea.value = val.substring(0, this.start) + replacement + val.substring(this.end);

		// Odrolovat na původní pozici
		this.textarea.scrollTop = scrolled;
	},

	// Funkce zjistí pravděpodobnou podobu formátu nového řádku.
	lf: function() {
		if (this.lineFeedKnown) return this.lineFeedFormat;

		// Pokusí se ho nalézt:
		var unix = this.textarea.value.indexOf('\n');
		var mac = this.textarea.value.indexOf('\r');
		var win = this.textarea.value.indexOf('\r\n');

		var lineFeed = null;
		if (unix >= 0) lineFeed = '\n';
		if (mac >= 0) lineFeed = '\r';
		if (win >= 0) lineFeed = '\r\n';

		// V případě úspěchu nastaví proměnnou this.lineFeedKnown na true a funkce již později hledání neopakuje.
		if (lineFeed) {
			this.lineFeedFormat = lineFeed;
			this.lineFeedKnown = true;
			return lineFeed;
		}

		// Jinak se nový řádek vrátí provizorně podle prohlížeče. (O, IE -> win, FF -> unix)
		return document.selection ? '\r\n' : '\n';
	},

	// Ulož vlastnosti výběru
	update: function() {
		this.textarea.focus();

		// IE
		if (this.isIe()) {
			// Copyright (c) 2005-2007 KOSEKI Kengo
			var range = document.selection.createRange();
			var bookmark = range.getBookmark();

			var contents = this.textarea.value;
			var originalContents = contents;
			var marker = "[~M~A~R~K~E~R~]";
			while (contents.indexOf(marker) != -1) {
				marker = marker + Math.random();
			}

			range.text = marker + range.text + marker;
			contents = this.textarea.value;

			this.start = contents.indexOf(marker);
			contents = contents.replace(marker, "");
			this.end = contents.indexOf(marker);

			this.textarea.value = originalContents;
			range.moveToBookmark(bookmark);
			range.select();
		// O, FF
		} else {
			this.start = this.textarea.selectionStart;
			this.end = this.textarea.selectionEnd;
		}

		return this;
	},

	length: function () {
		return this.end - this.start;
	},

	text: function () {
		return this.textarea.value.substring(this.start, this.end);
	},

	isCursor: function () {
		return this.start == this.end;
	},

	// vybere od pozice from text o délce length
	select: function(from, length) {
		if (this.isIe()) {
			var lfCount = this.textarea.value.substring(0, from).split("\r\n").length - 1;
			from -= lfCount;
			this.textarea.focus();
			this.textarea.select();
			var ieSelected = document.selection.createRange();
			ieSelected.collapse(true);
			ieSelected.moveStart("character", from);
			ieSelected.moveEnd("character", length);
			ieSelected.select();
		} else {
			this.textarea.selectionStart = from;
			this.textarea.selectionEnd = from + length;
		}

		this.textarea.focus();
	},

	// vybrat blok
	selectBlock: function() {
		this.update();

		var lf = this.lf();
		var ta = this.textarea;

		// začátek
		var workFrom = ta.value.substring(0, this.start).lastIndexOf(lf);
		if (workFrom !== -1) workFrom += lf.length;
		var from = Math.max(0, workFrom);

		// konec
		var len = ta.value.substring(from, this.start).length + this.length();
		var fromSelectionEnd = ta.value.substring(this.end, ta.value.length);
		var lineFeedPos = fromSelectionEnd.indexOf(lf);
		len += lineFeedPos == -1 ? fromSelectionEnd.length : lineFeedPos;

		this.select(from, len);
		return this.update();
	}
};