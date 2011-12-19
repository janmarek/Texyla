function Texy(texyla) {
	this.textarea = texyla.textarea.get(0);
	this.texyla = texyla;
};

// class Texy extends Selection
Texy.prototype = jQuery.extend({}, Selection.prototype, {

	// tučné písmo
	bold: function () {
		this.trimSelect();

		var text = this.text();

		if (text.match(/^\*\*.*\*\*$/)) {
			this.replace(text.substring(2, text.length - 2));
		} else {
			this.tag("**", "**");
		}
	},

	// kurzíva
	italic: function () {
		this.trimSelect();

		var text = this.text();

		if (text.match(/^\*\*\*.*\*\*\*$/) || text.match(/^\*[^*]+\*$/)) {
			this.replace(text.substring(1, text.length - 1));
		} else {
			this.tag("*", "*");
		}
	},

	// blok
	block: function(what) {
		this.tag('/--' + what + this.lf(), this.lf() + '\\--');
	},

	// odkaz
	link: function(addr) {
		if (addr) this.phrase('"', '":' + addr);
	},

	// acronym
	acronym: function(title) {
		this.update();
		if (title) {
			// Nejsou potřeba uvozovky. př.: slovo((titulek))
			if (this.text().match(/^[a-zA-ZěščřžýáíéúůĚŠČŘŽÝÁÍÉÚŮ]{2,}$/)) {
				this.tag('','((' + title + '))');

			// Jsou potřeba uvozovky. př.: "třeba dvě slova"((titulek))
			} else {
				this.phrase('"', '"((' + title + '))');
			}
		}
	},

	// čára
	line: function() {
		this.update();
		var lf = this.lf();

		// text
		var lineText = lf + lf + '-------------------' + lf + lf;

		// vložit
		if (this.isCursor()) this.tag(lineText, ''); else  this.replace(lineText);
	},

	// zarovnání
	align: function(type) {
		this.update();
		var lf = this.lf();

		var start = '.' + type + lf;
		var newPar = lf + lf;
		var found = this.textarea.value.substring(0, this.start).lastIndexOf(newPar);
		var beforePar = found + newPar.length;

		if (found ==- 1) {
			this.textarea.value = start + this.textarea.value;
		} else {
			this.textarea.value = this.textarea.value.substring(0, beforePar) + start + this.textarea.value.substring(beforePar);
		}
		this.select(this.start + start.length, this.length());
	},

	// original: Dougie Lawson, http://web.ukonline.co.uk/dougie.lawson/
	_toRoman: function(num) {
		num = Math.min(parseInt(num, 10), 5999);
		var mill = [ '', 'M', 'MM', 'MMM', 'MMMM', 'MMMMM' ],
		cent = [ '', 'C', 'CC', 'CCC', 'CD', 'D', 'DC', 'DCC', 'DCCC', 'CM' ],
		tens = [ '', 'X', 'XX', 'XXX', 'XL', 'L', 'LX', 'LXX', 'LXXX', 'XC' ],
		ones = [ '', 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX' ],
		m, c, t, r = function(n) {
			n = (num - (num % n)) / n;
			return n;
		};
		m = r(1000);
		num = num % 1000;
		c = r(100);
		num = num % 100;
		t = r(10);
		return mill[m] + cent[c] + tens[t] + ones[num % 10];
	},

	_toLetter: function(n) {
		var alphabet = [
			"a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m",
			"n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z"
		];
		return alphabet[Math.max(0, Math.min(n, alphabet.length) - 1)];
	},

	// vytvoří seznam - číslovaný (type == "ol"), s odrážkami (type == "ul"), blockquote (type == "bq")
	list: function(type) {
		this.selectBlock();
		var lf = this.lf();
		var lines = this.text().split(lf);
		var lineCt = this.isCursor() ? 3 : lines.length;
		var replacement = '';

		for (var i = 1; i <= lineCt; i++) {
			var bullet = {
				ul: '-',
				ol: i + ')',
				bq: '>',
				indent: '',
				romans: this._toRoman(i) + ')',
				smallRomans: this._toRoman(i).toLowerCase() + ')',
				smallAlphabet: this._toLetter(i) + ')',
				bigAlphabet: this._toLetter(i).toUpperCase() + ')'
			};

			replacement += bullet[type] + ' ' + (!this.isCursor() ? lines[i - 1] : '') + (i != lineCt ? lf : '');

			// seznam okolo kurzoru - pozice kurzoru
			if (this.isCursor() && i === 1)  var curPos = replacement.length - 1;
		}

		if (this.isCursor()) {
			this.tag(replacement.substring(0, curPos), replacement.substring(curPos));
		} else {
			this.replace(replacement);
		}
	},

	// odsazení o mezeru
	indent: function () {
		this.list("indent");
	},

	// zrušit odsazení
	unindent: function () {
		this.selectBlock();
		var lines = this.text().split(this.lf());
		var replacement = [];

		for (var i = 0; i < lines.length; i++) {
			var first = lines[i].substring(0, 1);
			if (first == " " || first == "\t") {
				replacement.push(lines[i].substring(1, lines[i].length));
			} else {
				replacement.push(lines[i]);
			}
		}

		this.replace(replacement.join(this.lf()));
	},

	// vytvoří nadpis, podtrhne podle type
	heading: function(type) {
		this.selectBlock();
		var lf = this.lf();

		// podtržení
		function underline(len, type) {
			var txt = '';
			for (var i = 0; i < Math.max(3, len); i++) {
				txt += type;
			}

			return txt;
		}

		// Nový nadpis
		if (this.isCursor()) {
			var headingText = prompt(this.texyla.lng.texyHeadingText, '');
			if (headingText) {
				this.tag(headingText + lf + underline(headingText.length, type) + lf, '');
			}

		// Vyrobí nadpis z výběru
		} else {
			this.tag('', lf + underline(this.length(), type));
		}
	},

	// obrázek
	img: function(src, alt, align, descr) {
		// Zarovnání na střed
		var imgT = '';

		if (align == '<>') {
			imgT += this.lf() + '.<>' + this.lf();
			align = false;
		}

		// Začátek
		imgT += '[* ' + src + ' ';

		// Popis
		imgT += alt ? '.('+ alt +') ' : '';

		// Zarovnání
		imgT += (align ? align : '*') + ']';

		// Popisek
		imgT += descr ? ' *** ' + alt : '';

		this.replace(imgT);
	},

	// tabulka
	table: function(cols, rows, header) {
		var lf = this.lf();
		var tabTxt = lf;

		for (var i = 0; i < rows; i++) {
			// Hlavička nahoře
			if (header === 'n' && i < 2) {
				tabTxt += '|';
				for (var j = 0; j < cols; ++j) {
					tabTxt += '--------';
				}
				tabTxt += lf;
			}

			// Buňky
			for (j = 0; j < cols; j++) {
				// Hlavička vlevo
				if (header === 'l' && j === 0) {
					tabTxt += "|* \t";

				// Buňka bez hlavičky
				} else {
					tabTxt += "| \t";
				}

				// pozice kurzoru
				if (i === 0 && j === 0) var curPos = tabTxt.length - 1;
			}
			tabTxt += '|' + lf;
		}
		tabTxt += lf;

		// Vloží tabulku
		this.tag(tabTxt.substring(0, curPos), tabTxt.substring(curPos));
	}
});