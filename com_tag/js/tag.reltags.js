/**
 * Tag Component JS Lib
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 * @dependancy fwd_XHR
 */

/**
* Tag Cloud Tooltip Functions
* Retrieves tag clouds (related tags) for a tag, caches, and displays as tooltip
*/
fwd_Tag.prototype.reltags = ({
							 
	/**
	* Static Properties
	*/
	timeout: [],
	
	/**
	* Adds the event handlers
	*/
	addEventHandlers: function() {
		var el = document.getElementById('com_tag_cloud');
		if (!el) {
			return false;	
		}
		var taglinks = el.getElementsByTagName('a');
		for(var i = taglinks.length-1; i >= 0 ; i--) {
			var taglink = taglinks[i];
			taglink.onmouseover = function(e) {
				var tag = this.firstChild.innerHTML;
				fwd_Tag('rel').reltags.ui.handleMouseOver(tag, e);
			};
			taglink.onmouseout = function(e) {
				var tag = this.firstChild.innerHTML;
				fwd_Tag('rel').reltags.ui.handleMouseOut(tag, e);
			};
		}
	},
	/**
	* Server Side functions
	*/
	ss: {
		getTagCloud: function(tag) {
			var xhr = new fwd_XHR();
			var data = 'option=com_tag&task=view&act=tagged&tag='+tag+'&f=xhtml&no_html=1&Itemid='+fwd_Tag.config.Itemid+'&limit=10';
			if (xhr) {
				var url = 'index2.php';
				xhr.tag = tag;
				xhr.post(url, fwd_Tag('rel').reltags.ss.handleTagCloud).send(data);
				return true;
			} else {
				var url = 'index.php';
				window.location = url+'?'+data;	
			}
		},
		handleTagCloud: function(xhr) {
			if (xhr.xmlhttp.readyState == 4) {
				if (xhr.xmlhttp.status == 200) {
					var resp = xhr.xmlhttp.responseText;
					fwd_Tag('rel').reltags.ui.saveToCache(xhr.tag, resp);
					fwd_Tag('rel').reltags.ui.displayRelTags(resp);
				} else {
					alert('An HTTP Error occurred');
				}
			}
		}
	},
	/**
	* User Interface functions
	*/
	ui: {
		cache: {},
		saveToCache: function(id, value) {
			fwd_Tag('rel').reltags.ui.cache[id] = value;
		},
		getFromCache: function(id) {
			return fwd_Tag('rel').reltags.ui.cache[id];
		},
		handleMouseOver: function(tag, e) {
			clearTimeout(fwd_Tag('rel').reltags.timeout['ui.remove']);
			fwd_Tag('rel').reltags.ui.displayTooltip(e);
			var str_cloud = fwd_Tag('rel').reltags.ui.getFromCache(tag);
			if (str_cloud) {
				fwd_Tag('rel').reltags.ui.displayRelTags(str_cloud);
			} else {
				(function(tag) {
					fwd_Tag('rel').reltags.timeout['ss.get'] = setTimeout(
						function() {
							fwd_Tag('rel').reltags.ss.getTagCloud(tag);
						}, 500
					);
				})(tag);
			}
		},
		handleMouseOut: function(tag, e) {
				clearTimeout(fwd_Tag('rel').reltags.timeout['ss.get']);
				fwd_Tag('rel').reltags.ui.setRemoveTooltipTimer();
		},
		displayTooltip: function(e) {
			fwd_Tag('rel').reltags.ui.removeTooltip();
			var el = document.createElement('div');
			el.id = 'rel_tooltip';
			el.style.position = 'absolute';
			el.style.display = 'block';
			//el.style.backgroundColor = '#fff';
			el.style.width = '250px';
			el.style.height = 'auto';
			var pos = fwd_Tag('rel').reltags.ui.getMousePos(e);
			el.style.left = pos.x+'px';
			el.style.top = pos.y+'px';
			el.onmouseover = function() {
				clearTimeout(fwd_Tag('rel').reltags.timeout['ui.remove']);
			};
			el.onmouseout = function() {
				fwd_Tag('rel').reltags.ui.setRemoveTooltipTimer();
			};
			el.innerHTML = '<b>loading...</b>';
			document.body.appendChild(el);
		},
		setRemoveTooltipTimer: function() {
			fwd_Tag('rel').reltags.timeout['ui.remove'] = setTimeout(
				function() {
					fwd_Tag('rel').reltags.ui.removeTooltip();
				}, 500
			);
		},
		removeTooltip: function() {
			var el = document.getElementById('rel_tooltip');
			if (el) {
				document.body.removeChild(el);
			}
		},
		displayRelTags: function(str) {
			var el = document.getElementById('rel_tooltip');
			if (el) {
				el.innerHTML = str;	
			}
		},
		getMousePos: function(e) {
			var x = 0;
			var y = 0;
			if (!e) var e = window.event;
			if (e.pageX || e.pageY) {
				x = e.pageX;
				y = e.pageY;
			} else if (e.clientX || e.clientY) 	{
				x = e.clientX + document.body.scrollLeft
					+ document.documentElement.scrollLeft;
				y = e.clientY + document.body.scrollTop
					+ document.documentElement.scrollTop;
			}
			return {x:x, y:y};
		}
	}
});

/* 
* Inherit commonLib prototype
*/
fwd_Tag.extendProto(
	fwd_commonLib.prototype
);
// add event handlers when window loads
fwd_Tag('rel').addEvent(window, 'load', 
	function() {
		fwd_Tag('rel').reltags.addEventHandlers();
		// add event handler to sanitize user input
		fwd_Tag.on.getTagsFromUser.attachEventListerner(
			function(args) {
				var cid = args[0];
				//alert(cid);
			}
		);
																 
	}
);
						   
						   
