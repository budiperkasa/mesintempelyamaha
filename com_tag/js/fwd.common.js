/**
* Fiji Web Design Common JS lib
* (c) http://www.fijiwebdesign.com
*/

/**
* Common lib obj
*/
function fwd_commonLib(log_id) {
    var ver = '0.1';
	this.included_files = [];
	this.log_id = (log_id ? log_id : 'fwd_error_log');
    return true;
};

/**
* General Log Function
*/
fwd_commonLib.prototype.logger = function(txt) {
	if (el = document.getElementById(this.log_id)) {
		el.value = txt+"\r\n"+el.value;
		
		// pop the last line off when over [this.maxlen] lines
		var arr = el.value.split("\r\n");
		if (arr.length > this.maxlen) {
			arr.pop();
			el.value = arr.concat("\r\n");
		}
	}
};

/**
* General Object Dump - only works for shallow objects
*/
fwd_commonLib.prototype.dump = function(obj, depth) {
	var txt = '';
	if (typeof(depth) == 'undefined') { 
		depth = 0;
	} else {
		txt += "\r\n";
		depth++;
	}
	if (typeof(obj) == 'object' || typeof(obj) == 'array') {
		for(var x in obj) {
			txt += "".pad(depth, "\t", 0);
			if (typeof(obj[x]) == 'object' || typeof(obj[x]) == 'array') {
				txt += x+': '+this.dump(obj[x], depth)+"\r\n";
			} else {
				txt += x+': '+obj[x]+"\r\n";
			}
		}
	}
	return txt;
};

/**
* Array pop method for older browsers
*/
if (typeof(Array.prototype.pop) == 'undefined') {
	Array.prototype.pop = function() {
		var response = this[this.length - 1];
		this.length--;
		return response;
	};
}

/**
* Add events
* @param string element name to attach event to
* @param string name of trigger
* @param string name of function to trigger
* @param bool capture event bubbling?
*/
fwd_commonLib.prototype.addEvent = function(el, evType, fn, useCapture, overWrite) {
	if (overWrite) el['on' + evType] = false;
	if (el.addEventListener) {
		el.addEventListener(evType, fn, useCapture);
		return true;
	}
	else if (el.attachEvent) {
		var r = el.attachEvent('on' + evType, fn);
		return r;
	}
	else {
		el['on' + evType] = fn;
	}
};

/**
* Get elements by Class Name
* @param string className
* @param string parent node to search within, otherwise the whole document is searched
* @param string name of tag to limit search to, otherwise all tags are returned
*/
fwd_commonLib.prototype.getElementsByClass = function(searchClass,node,tag) {
	var classElements = new Array();
	if (!node) node = document;
	if (!tag) tag = '*';
	var els = node.getElementsByTagName(tag);
	var elsLen = els.length;
	var pattern = new RegExp("(^|\\s)"+searchClass+"(\\s|$)");
	for (var i = 0, j = 0; i < elsLen; i++) {
		if ( pattern.test(els[i].className) || pattern.test(els[i].getAttribute('class')) ) {
			classElements[j] = els[i];
			j++;
		}
	}
	return classElements;
};

/**
* Toggle the original property of an element with a new property value
* @param object htmlElement or the htmlElement id
* @param string property/attribute name
* @param string property/attribute value
*/
fwd_commonLib.prototype.toggleProperty = function(obj, property, value) {
	var el = typeof(obj) == 'object' ? obj : this.el(obj);
	var orig_value = this.style(el)['orig_' + property];
	if ( !orig_value ) {
		this.style(el)['orig_' + property] = value;
        this.style(el)[property] = value;
	} else {
		this.style(el)[property] = orig_value;
		this.style(el)['orig_' + property] = null;
	}
};

/**
* Insert an element after and element the dom withought parent node reference
* @param string element object node to insert after
* @param string element object to insert
*/
fwd_commonLib.prototype.insertAfter = function(nPrevious, nInsert) {
    var nParent = nPrevious.parentNode;
    if (nPrevious.nextSibling) {
        nParent.insertBefore(nInsert, nPrevious.nextSibling);
    } else {
        nParent.appendChild(nInsert);
    }
};

/**
* Check if a value exists in the Array and returns the key if exists or -1 otherwise.
* @param string array value
*/
fwd_commonLib.prototype.in_array = function(value, arr) {
	for (var x in arr) { 
		if (arr[x] == value) {
           return x;
		}
	}
	return -1;
};

/**
* Get a cookie value
* @param string cookie name
*/
fwd_commonLib.prototype.getCookie = function( name ) {
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ) {
		return null;
	}
	if ( start == -1 ) return null;
	var end = document.cookie.indexOf( ";", len );
	if ( end == -1 ) end = document.cookie.length;
	return unescape( document.cookie.substring( len, end ) );
};

/**
* Set a cookie
* @param string cookie name
* @param string cookie value
* @param string cookie expiration counter in days
* @param string cookie path
* @param string cookie domain
* @param bool secure?
*/
fwd_commonLib.prototype.setCookie = function( name, value, expires, path, domain, secure ) {
	var today = new Date();
	today.setTime( today.getTime() );
	if ( expires ) {
		expires = expires * 1000 * 60 * 60 * 24;
	}
	var expires_date = new Date( today.getTime() + (expires) );
	document.cookie = name+"="+escape( value ) +
		( ( expires ) ? ";expires="+expires_date.toGMTString() : "" ) +
		( ( path ) ? ";path=" + path : "" ) +
		( ( domain ) ? ";domain=" + domain : "" ) +
		( ( secure ) ? ";secure" : "" );
};

/**
* Remove a cookie
* @param string cookie name
* @param string cookie value
* @param string cookie path
* @param string cookie domain
*/
fwd_commonLib.prototype.deleteCookie = function( name, path, domain ) {
	if ( getCookie( name ) ) document.cookie = name + "=" +
			( ( path ) ? ";path=" + path : "") +
			( ( domain ) ? ";domain=" + domain : "" ) +
			";expires=Thu, 01-Jan-1970 00:00:01 GMT";
};

/**
* Return a list of elements objects by their id's
* @param string element id or list of element id's
* @note js is loosely typed, so a function can have any number of parameters/arguments
*/
fwd_commonLib.prototype.els = function() {
	var els = new Array();
	for (var i = 0; i < arguments.length; i++) {
		var id = arguments[i];
		if (typeof id == 'string') {
			el = this.el(id);
		} if (el.length == 1) {
			return el;
		}
		els.push(element);
	}
	return els;
};

/**
* Element to Object by Id.
* @param string element id
*/
fwd_commonLib.prototype.el = function(id) {
    if (document.getElementById) {
        return document.getElementById(id);
    } else if (document.all) {
        return document.all[id];
    } else if (document.layers) {
        return document.layers[id];
    } else {
        return false;
    }
};

/**
* HTML Element Style Property
* @param obj HTML Element
*/
fwd_commonLib.prototype.style = function(obj) {
    if (document.getElementById || document.all) {
        return obj.style;
    } else if (document.layers) {
        return obj;
    } else {
        return obj.style; // for now
    }
};

/**
* HTML Element Object Width and Height
* @param obj HTML Element
*/
fwd_commonLib.prototype.objDim = function(obj) {
	if (obj.offsetHeight) {
		this.h = obj.offsetHeight;
		this.w = obj.offsetWidth;
		this.mode = 'offsetHeight';
	} else if (obj.style && obj.style.pixelHeight) {
		this.h = obj.style.pixelHeight;
		this.w = obj.style.pixelWidth;
		this.mode = 'style.pixelHeight';
	} else if (obj.clip) {
		this.h = obj.clip.height;
		this.w = obj.clip.width;
		this.mode = 'clip';
	} else if (this.currStyle) {
		var c = new fwd_commonLib;
		this.h = c.currStyle(obj, 'height');
		this.w = c.currStyle(obj, 'width');
		c = null;
		this.mode = 'currStyle';
	} 
};

/**
* Window width and height
* @param bool account for horizontal scroll
* @param bool account for vertical scroll
*/
fwd_commonLib.prototype.winDim = function(scrollX, scrollY, parent) {
	var win = parent ? parent.window : window;
	var doc = parent ? parent.document : document;
	this.w = 0;
	this.h = 0;
	if (doc.documentElement && doc.documentElement.clientWidth) { /* FF/IE quirks mode */
		this.w = doc.documentElement.clientWidth;
		this.h = doc.documentElement.clientHeight;
	} else if (win && win.innerWidth) { /* NN/other */
		this.w = (scrollX) ? win.innerWidth - scrollX : win.innerWidth;
		this.h = (scrollY) ? win.innerHeight - scrollY : win.innerHeight;
	} else { /* IE non-quirks mode */
		var c = new fwd_commonLib();
		var Dim = new c.bodyDim();
		this.w = Dim.w;
		this.h = Dim.h;
		c = null;
	}
};

/**
* Document Body width and height
* @param bool account for horizontal scroll
* @param bool account for vertical scroll
*/
fwd_commonLib.prototype.bodyDim = function(scrollX, scrollY, parent) {
	var doc = parent ? parent.document : document;
	this.w = 0;
	this.h = 0;
	if (doc.body && doc.body.clientWidth) {
		this.w = (scrollX) ? doc.body.clientWidth - scrollX : doc.body.clientWidth;
		this.h = (scrollY) ? doc.body.clientHeight - scrollY : doc.body.clientHeight;
	} else if (doc.body && doc.body.offsetWidth) {
		this.w = (scrollX) ? doc.body.offsetWidth - scrollX : doc.body.offsetWidth;
		this.h = (scrollY) ? doc.body.offsetHeight - scrollY: doc.body.offsetHeight;
	}
};

/**
* Objects Style Attribute value through Current Style applied by the document
* @param obj HTML Element
* @param string HTML Element Style Attribute
*/
fwd_commonLib.prototype.currStyle = function(obj, attribute) {
	if (obj.currentStyle) {
		return obj.currentStyle[attribute];
	} else if (window.getComputedStyle) {
		return document.defaultView.getComputedStyle(obj,null).getPropertyValue(attribute);
	}
};

/**
* HTML Elements top and left offset relative to the docuemnt
* @param object htmlElement
*/
fwd_commonLib.prototype.xyOffset = function(obj){
	this.x = 0;
	this.y = 0;
	this.levels = 0;
	if (obj.offsetParent) {
		while (obj.offsetParent) {
			this.x += parseInt(obj.offsetLeft);
			this.y += parseInt(obj.offsetTop);
			obj = obj.offsetParent;
			this.levels++;
		}
	} else if (obj.x) {
		this.x += obj.x;
		this.y += obj.y;
	}
};

/**
* Current x/y scroll offset of the window
*/
fwd_commonLib.prototype.winScroll = function(parent) {
	this.x = 0;
	this.y = 0;
	var doc = parent ? parent.document : document;
	var win = parent ? parent.window : window;
	
	if (doc.documentElement && doc.documentElement.scrollTop) {
		this.y = doc.documentElement.scrollTop; 
		this.x = doc.documentElement.scrollLeft; 
	} else if (doc.body) {
		  this.y =  doc.body.scrollTop; 
		  this.x = doc.body.scrollLeft; 
	} else if (win.innerHeight) {
		  this.y = win.pageYOffset; 
		  this.x = win.pageYOffset; 
	}
};

/**
* Image Preloading, takes any number of arguments
* @params string img src
*/
fwd_commonLib.prototype.preloadimages = function(){
    var imgs = new Array();
    var n = arguments.length;
	for (i = 0; i < n; i++){
    	imgs[i] = new Image();
    	imgs[i].src = arguments[i];
	}
	return imgs;
};

/**
* General Form Validation
* attachment of a method called js_valid to a form element to validate it on form submit
* @param object form element
*/
fwd_commonLib.prototype.js_validate = function(form, method) {
	if (!method) { method = 'js_valid'; }
	var els = form.elements;
	var n = els.length;
	for(var i = 0; i < n; i++) {
		var el = els[i];
		if (el[method]) {
			if (!el[method](el)) {
				return false;
			}
		}
	}
	return true;
};

/**
* Dynamic Loading of JavaScript
*/
fwd_commonLib.prototype.include_js = function(script_filename) {
	if (document.getElementsByTagName) {
		var html_doc = document.getElementsByTagName('head').item(0);
		var js = document.createElement('script');
		js.setAttribute('language', 'javascript');
		js.setAttribute('type', 'text/javascript');
		js.setAttribute('src', script_filename);
		html_doc.appendChild(js);
    	return true;
	}
	return false;
};

/**
* check if a file has already been loaded before loading
*/
fwd_commonLib.prototype.include_js_once = function(script_filename) {
    if (this.in_array(script_filename, this.included_files) == -1) {
        this.included_files[this.included_files.length] = script_filename;
        return this.include_js(script_filename);
    }
	return false;
};

/**************************************
 Jonas Raoni Soares Silva
 http://www.joninhas.ath.cx
**************************************/
/**
* Pads a string to a specific length
* @param int length of string
* @param string padding
* @param int type of padding (0: left, 1: right, 2: both sides)
*/
String.prototype.pad = function(l, s, t){
	return s || (s = " "), (l -= this.length) > 0 ? (s = new Array(Math.ceil(l / s.length)
		+ 1).join(s)).substr(0, t = !t ? l : t == 1 ? 0 : Math.ceil(l / 2))
		+ this + s.substr(0, l - t) : this;
};

/**
* Creates new Event
* Example Usage: 
*	var Evt = new fwd_commonLib('id').Event();
*	Evt.attachEventListerner(function() { alert('hi im the listerner'); });
*	Evt.fireEventListerners();												
*/
fwd_commonLib.Event = function(name) {
	// private event handlers
	var _handlers = []; 
	return {
		// event name
		name: name,
		/** 
		* Attach Event Handlers
		* @param Function The function to run when event is triggered
		*/
		attachEventListerner: function(fn) {
			_handlers.push(fn);
		},
		
		/** 
		* Fire event Handlers
		*/
		fireEventListerners: function() {
			var n = _handlers.length;
			for (var i = 0; i < n; i++) {
				if (_handlers.hasOwnProperty(i)) {
					try {
						(_handlers[i])(arguments);
					} catch(e) {
						/* ignore */
					}
				}
			}
		}
	}
};

