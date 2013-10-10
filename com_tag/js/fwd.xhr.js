/**
* Fiji Web Design AJAX lib
* @copyright (c) http://www.fijiwebdesign.com
* @email fwd_ajax.class@fijiwebdesign.com
* @package Testing 
*/

fwd_XHR = function() {
	ver = '0.5';
	this.xmlhttp = this.createXMLHTTPRequest();
};

// static global properties
fwd_XHR._XMLHTTP_VER = false; // MSIE
fwd_XHR.ERR_NO_XMLHTTP = 'Your Browser does not support XMLHttpRequest!';
fwd_XHR.XML_HTTP_SUPORT_NATIVE = 'XMLHttpRequest Support: Native';
fwd_XHR.XML_HTTP_SUPORT_ACTIVEX = 'XMLHttpRequest Support: ';
fwd_XHR.ERR_CALLBACK = 'xmlHTTPRequest Callback Exception: ';
fwd_XHR.RESP_STATUS = 'Response Status: ';
fwd_XHR.RESP_STATE = 'Response State: ';
fwd_XHR.CACHED = 'Version Number Cached';

// dynamic properties
fwd_XHR.prototype.url; // url of request
fwd_XHR.prototype.method; // request method (GET, POST, HEAD)
fwd_XHR.prototype.callback; // callback function
fwd_XHR.prototype.data; // data to send in HTTP Request
fwd_XHR.prototype.async; // perform HTTP Request Asynchronous or not
fwd_XHR.prototype.user; // domain username (BasicAuth)
fwd_XHR.prototype.pass; // domain password

/**
* Get a Reference to the xmlHTTPRequest Object
*/
fwd_XHR.prototype.createXMLHTTPRequest = function() {
	if (window.XMLHttpRequest) {
		fwd_XHR.logger(fwd_XHR.XML_HTTP_SUPORT_NATIVE);
		return (new XMLHttpRequest());
    } else if (window.ActiveXObject) {
		// check cached msxml version
		if (fwd_XHR._XMLHTTP_VER) {
			fwd_XHR.logger(fwd_XHR._XMLHTTP_VER + ' ('+fwd_XHR.CACHED+')');
			return (new ActiveXObject(fwd_XHR._XMLHTTP_VER));	
		}
		// find latest XMLHTTP implementation on IE
		var versions = [
		"Msxml2.XMLHTTP.7.0", 
		"Msxml2.XMLHTTP.6.0", 
		"Msxml2.XMLHTTP.5.0", 
		"Msxml2.XMLHTTP.4.0", 
		"MSXML2.XMLHTTP.3.0", 
		"MSXML2.XMLHTTP",
		"Microsoft.XMLHTTP"];
		var n = versions.length;
		for (var i = 0; i <  n; i++) {
			try {
				fwd_XHR._XMLHTTP_VER = versions[i];
				if (new ActiveXObject(fwd_XHR._XMLHTTP_VER)) {
					fwd_XHR.logger(fwd_XHR.XML_HTTP_SUPORT_ACTIVEX+fwd_XHR._XMLHTTP_VER);
					return (new ActiveXObject(fwd_XHR._XMLHTTP_VER));
				}
			} catch (e) {
				fwd_XHR._XMLHTTP_VER = false;
			}
		}
		fwd_XHR.noXHRSupport();
    }
	return false;
};

/**
* A HTTP GET
*/
fwd_XHR.prototype.get = function(url, callback, async, user, pass) {
	return this.request(url, 'GET', callback, null, async, user, pass);
};

/**
* A HTTP POST
*/
fwd_XHR.prototype.post = function(url, callback, async, user, pass) {
	return this.request(url, 'POST', callback, null, async, user, pass);
};


/**
* Makes an xmlHTTPRequest ready for sending
*/
fwd_XHR.prototype.request = function(url, method, callback, data, async, user, pass) {
	
	var self = this;
	this.url = url;
	this.method = method;
	this.callback = callback;
	this.data = data;
	this.async = async;
	this.user = user;
	this.pass = pass;
	
	async = (typeof(async) != 'undefined' && !async) ? false : true;
	method = method.toUpperCase();
	
	this.xmlhttp.onreadystatechange = function() {
		try {
			callback(self);
			if (self.xmlhttp.readyState == 4) {
				self = null; // remove closure
			}
		} catch(e) { 
			fwd_XHR.logger(fwd_XHR.ERR_CALLBACK+e);
		}
	}
	
	this.xmlhttp.open(method, url, async, user, pass);
	if (method == 'POST') {
		this.setPostHeaders('1.1');
	}
	
	return this.xmlhttp;
};

/**
* Set HTTP POST Headers / URL-ENCODED
*/
fwd_XHR.prototype.setPostHeaders = function(http_ver) {
	http_ver = http_ver ? 'HTTP/'+http_ver : 'HTTP/1.1';
	this.xmlhttp.setRequestHeader("Method", "POST "+this.url+" "+http_ver);
    this.xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	return this.xmlhttp;
};

/**
* Execute the JSON text
*/
fwd_XHR.prototype.execJSON = function() {
	if (this.readyState()) { //alert(this.xmlhttp.responseText);
		return eval('('+this.xmlhttp.responseText+')');
	}
};

/**
* Check readystate and status
*/
fwd_XHR.prototype.readyState = function(readyState, status) {
	readyState = readyState || 4;
	status = status || 200;
	var xmlhttp = this.xmlhttp;
	if (xmlhttp.readyState == readyState) {
		//fwd_XHR.logger(fwd_XHR.RESP_STATUS+xmlhttp.status);
		//fwd_XHR.logger(fwd_XHR.RESP_STATE+xmlhttp.readyState);
		if (xmlhttp.status == status) {
			return true;
		} else {
			try {
				this.statusError(xmlhttp.status);
			} catch(e) { /* no status error set */ }
		}
	}
};

/**
* encode passed http vars
*/
fwd_XHR.prototype.encode = function(uri) {
    if (encodeURIComponent) {
        return encodeURIComponent(uri);
    }
    if (escape) {
        return escape(uri);
    }
};

/**
* dencode passed http vars
*/
fwd_XHR.prototype.decode = function(uri) {
    uri = uri.replace(/\+/g, ' ');

    if (decodeURIComponent) {
        return decodeURIComponent(uri);
    }
    if (unescape) {
        return unescape(uri);
    }
    return uri;
};

/** static function */

/**
* Set the id of the element to write logs to
*/
fwd_XHR.setLogger = function(id) {
	fwd_XHR.log_id = id;
};

/**
* Simple Logging utility
*/
fwd_XHR.logger = function(txt) {
	if (!fwd_XHR.log_id) return;
	var logger = document.getElementById(fwd_XHR.log_id);
	if (logger) {
		logger.value = txt  + "\r\n" + logger.value;
	}
};

/**
* Function to run when a browser does not support XHR
*/
fwd_XHR.noXHRSupport = function() {
	alert(fwd_XHR.ERR_NO_XMLHTTP);
};