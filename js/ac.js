/*

	jsAC (JavaScript AutoComplete) v1.0

*/

function AbsolutePosition(el) {
  var SL = 0, ST = 0;
  var is_div = /^div$/i.test(el.tagName);
  if (is_div && el.scrollLeft)
    SL = el.scrollLeft;
  if (is_div && el.scrollTop)
    ST = el.scrollTop;
  var r = { x: el.offsetLeft - SL, y: el.offsetTop - ST };
  if (el.offsetParent) {
    var tmp = AbsolutePosition(el.offsetParent);
    r.x += tmp.x;
    r.y += tmp.y;
  }
  return r;
};

function autocomplete_auto_attach() {
	var acdb = [];
	var inputs = document.getElementsByTagName('input');
	for (i=0;input=inputs[i];i++) {
		if (input && input.className == 'autocomplete') {
			var uri = input.value;
			if (!acdb[uri]) {
				acdb[uri] = new ACDB_Remote(uri);
			}
			var id = input.id.substr(0,input.id.length - 13);
			input = document.getElementById(id);
			input.setAttribute('autocomplete','OFF');
			NewAutoComplete(input, acdb[uri]);
		}
	}
}

window.onload = autocomplete_auto_attach;

function NewAutoComplete(input, db) {
	if (!input.parentNode)
		input = document.getElementById(input);
	var ac = new jsAC(input, db);
}

/* === jsAC Class === */

function jsAC(input, db) {
	this.input = input;
	this.init();
	this.db = db;
};

jsAC.prototype.toString = function () {
	return '[AutoComplete Object]';
}

jsAC.prototype.init = function() {
	var ac = this;
	this.input.onkeydown = function (event) { return ac.onkeydown(this, event) }
	this.input.onkeyup = function (event) { ac.onkeyup(this, event) }
	this.input.onblur = function () { ac.onblur(this) }
	this.popup = document.createElement('div');
	this.popup.id = 'autocomplete';
}

jsAC.prototype.hidePopup = function () {
	if (this.selected)
		this.input.value = this.selected.innerHTML;
	if (this.popup.parentNode && this.popup.parentNode.tagName)
		this.popup.parentNode.removeChild(this.popup);
}

jsAC.prototype.onkeydown = function (input, e) {
	if (!e) e = window.event;

	switch (e.keyCode) {
		case 40:
			this.selectDown();
			return false;
		case 38:
			this.selectUp();
			return false;
		default:
			return true;
	}
}

jsAC.prototype.onkeyup = function (input, e) {
	if (!e) e = window.event;
	switch (e.keyCode) {
		case 38: // up arrow
		case 40: // down arrow
		case 37: // left arrow
		case 39: // right arrow
		case 33: // page up
		case 34: // page down
		case 36: // home
		case 35: // end
		case 27: // esc
		case 16: // shift
		case 17: // ctrl
		case 18: // alt
		case 20: // caps lock
			return true;

		case 9:  // tab
		case 13: // enter
			this.hidePopup();
			return true;

		default: // all other keys
			if (input.value.length > 0)
				this.populatePopup();
			else
				this.hidePopup();
			return true;
	}
}

jsAC.prototype.onblur = function (input) {
	this.hidePopup();
}

jsAC.prototype.select = function (node) {
	this.input.value = node.innerHTML;
}

jsAC.prototype.selectDown = function () {
	if (this.selected) {
		if (this.selected.nextSibling && this.selected.nextSibling.tagName)
			this.highlight(this.selected.nextSibling);
	} else {
		var ps = this.popup.getElementsByTagName('p');
		if (ps.length > 0)
			this.highlight(ps[0]);
	}
}

jsAC.prototype.selectUp = function () {
	if (this.selected && this.selected.previousSibling && this.selected.previousSibling.tagName) {
		this.highlight(this.selected.previousSibling);
	}
}

jsAC.prototype.template = function () { }

jsAC.prototype.highlight = function (node) {
	if (this.selected)
		this.selected.className = '';
	node.className = 'selected';
	this.selected = node;
	//this.input.value = node.innerHTML;
}

jsAC.prototype.unhighlight = function (node) {
	node.className = '';
	this.selected = false;
}

jsAC.prototype.populatePopup = function () {
	var ac = this;
	this.selected = false;
	var pos = AbsolutePosition(this.input);
	this.popup.style.top = (pos.y + this.input.offsetHeight) + 'px';
	this.popup.style.left = pos.x + 'px';
	this.popup.style.width = (this.input.offsetWidth - 4) + 'px';
	this.db.onmatch = function(matches) { ac.found(matches); }
	this.db.search(this.input.value);
}

jsAC.prototype.found = function (matches) {

	while (this.popup.hasChildNodes())
		this.popup.removeChild(this.popup.childNodes[0]);
	if (!this.popup.parentNode || !this.popup.parentNode.tagName)
		document.getElementsByTagName('body')[0].appendChild(this.popup);

	var div = document.createElement('div');
	var ac = this;
	if (matches.length > 0) {
		for (var i in matches) {
			var p = document.createElement('p');
			p.appendChild(document.createTextNode(matches[i]));
			p.onmousedown = function() { ac.select(this); }
			p.onmouseover = function() { ac.highlight(this); }
			p.onmouseout = function() { ac.unhighlight(this); }
			div.appendChild(p);
		}
		this.popup.appendChild(div);
	} else {
		this.hidePopup();
	}
}




/* === ACDB Base class */

function ACDB() {
	this.max = 15; // max returned results
	this.delay = 300; // milliseconds
	this.docache = false; // client side cache
	this.cache = {};
}

ACDB.prototype.toString = function () {
	return '[AutoComplete Database]';
}

ACDB.prototype.search = function(search_string) {
	if (!this.dosearch)
		return false;
	if (this.docache) {
		this.search_string = search_string;
		if (this.cache[search_string])
			return this.match(this.cache[search_string]);
	}
	var db = this;
	if (this.timer)
		clearTimeout(this.timer);
	this.timer = setTimeout(function() { db.dosearch(search_string) }, this.delay);
}

ACDB.prototype.match = function (matches) {
	if (this.docache)
		this.cache[this.search_string] = matches;
	if (this.onmatch)
		this.onmatch(matches);
}




/* === class ACDB_JS extends ACDB === */

function ACDB_JS(array_of_strings) {
	this.strings = array_of_strings;
	this.delay = 30;
}

ACDB_JS.prototype = new ACDB;

ACDB_JS.prototype.dosearch = function(search_string) {
	var search_length = search_string.length;
	var matches = [];
	var test_string = '';

	for (var i = 0; test_string = this.strings[i]; i++) {
		if (test_string.substr(0,search_length).toLowerCase() == search_string.toLowerCase())
			matches[matches.length] = test_string;
		if (matches.length >= this.max)
			break;
	}
	this.match(matches);
}




/* === class ACDB_Remote extends ACDB === */

function ACDB_Remote(uri) {
	this.uri = uri;
	this.delay = 0;
	this.docache = true;
}

ACDB_Remote.prototype = new ACDB;

ACDB_Remote.prototype.dosearch = function(search_string) {
	HTTPGet(this.uri + '/' + search_string + '/' + this.max, this.receive, this);
}

ACDB_Remote.prototype.receive = function(string, xmlhttp, acdb) {
	if (xmlhttp.status != 200)
		return alert('An HTTP error ' + xmlhttp.status + ' occured.\n' + acdb.uri);
	acdb.match(string.length > 0 ? string.split('|') : []);
}