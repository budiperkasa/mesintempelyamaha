/**
 * Tag Component JS Lib
 * @author gabe@fijiwebdesign.com
 * @copyright (c) fijiwebdesign.com
 * @license http://www.fijiwebdesign.com/
 * @dependancy fwd_XHR
 */
fwd_Tag = function(cid) {
	// allow us to have a single global namespace
	// http://fijiwebdesign.com/content/view/97/77/
	if (this instanceof fwd_Tag) {
		this.cid = cid;
		fwd_Tag.i[cid] = this;
	} else {
		return fwd_Tag.i[cid] || new fwd_Tag(cid);
	}
};

/**
* Static Methods and Properties
*/
fwd_Tag.i = {}; // instances
fwd_Tag.sep= ', '; // tags seperator regex
fwd_Tag.sep_pattern = /\s*,\s*/; // tags seperator regex

/**
* Extend the prototype with the methods in obj
* Note: This overwrites any existing prototypes with the same index
*/
fwd_Tag.extendProto = function(obj) {
	for(var x in obj) {
		if (obj.hasOwnProperty(x)) {
			fwd_Tag.prototype[x] = obj[x];
		}
	}
},

/**
* Prototype Methods
*/
fwd_Tag.prototype = {
	
	/**
	* Controls adding of tags to UI and Backend
	*/
	addTags: function(cid) {
		if (!fwd_Tag.config.user_id) {
			alert('Sorry, You have to log in to add tags');
			return;
		}
		this.ui.getTagsFromUser(cid);
	},
	
	// user interface
	ui: {
		/**
		* Shows the Input UI for adding Tags
		*/
		getTagsFromUser: function(cid) {
			
			// fire the event listerner for this method
			fwd_Tag.on.getTagsFromUser.fireEventListerners(cid);
			
			var tagForm = document.getElementById('tagform_'+cid);
			if (tagForm.childNodes && tagForm.childNodes.length > 1) {
				if (tagForm.style.display == 'block') {
					tagForm.style.display = 'none';	
				} else {
					tagForm.style.display = 'block';
				}
			} else {
				var input = document.createElement('input');
				input.id = 'tag_input_'+cid;
				input.name = 'tags';
				input.className = 'inputbox';
				// listen for enter key
				input.onkeypress = function(e) {
					e = (!e) ? window.event : e;
					if (e.keyCode == 13) {
						btn.onclick();	
					}
				};
				var btn = document.createElement('input');
				btn.type = 'button';
				btn.value = fwd_Tag.config.add_btn_txt ? fwd_Tag.config.add_btn_txt : 'Save';
				btn.id = 'tag_add_btn_'+cid;
				btn.className = 'button';
				// listen for btn click
				btn.onclick = function() {
					var cid = this.id.replace('tag_add_btn_', '');
					var input = document.getElementById('tag_input_'+cid);
					var tags = input.value;
					input.value = '';
					var tagForm = document.getElementById('tagform_'+cid);
					tagForm.style.display = 'none';
					fwd_Tag(cid).ui.handleTagsFromUser(cid, tags);
				};
				tagForm.appendChild(input);
				tagForm.appendChild(btn);
				tagForm.style.display = 'block';
				input.focus();
			}
		},
		/**
		* Handle the tags added by user
		*/
		handleTagsFromUser: function(cid, tags) {
			if (tags) {
				tags = tags.split(fwd_Tag.sep_pattern); // 4now
				fwd_Tag(cid).ui.addTags(tags, cid); // add ui instantly, remove if ss fails
				fwd_Tag(cid).ss.saveTags(tags, cid);
			}
		},
		/**
		* Adds the Tags to the UI
		*/
		addTags: function(tags, cid) {
			var tagList = document.getElementById('taglist_'+cid);
			if (tagList) {
				var sep = '';
				var addTagBtn = tagList.lastChild;
				var nodeName = tagList.childNodes[0].nodeName;
				if (tagList.childNodes.length > 1) {
					sep = fwd_Tag.sep;
				}
				for(var i = 0; i < tags.length; i++) {
					// create Nodes to add to 
					var tagEl = document.createElement(nodeName);
					var tagLink = document.createElement('a');
					var tagTxt = document.createTextNode(tags[i]);
					var sepEl = document.createElement('span');
					var sepTxt = document.createTextNode(sep);
					sepEl.appendChild(sepTxt);
					tagEl.appendChild(sepEl);
					tagLink.appendChild(tagTxt);
					tagLink.href = 'index.php?option=com_tag&tag='+tags[i];
					tagEl.appendChild(tagLink);					
					tagList.insertBefore(tagEl, addTagBtn);
					sep = fwd_Tag.sep;
				}
			} else {
				alert('no taglist:  taglist_'+this.cid);	
			}
		},
		/**
		* Removes Tags from the UI
		*/
		removeTags: function(cid, tags) {
			var tagList = document.getElementById('taglist_'+cid);
			if (tagList) {
				var items = tagList.getElementsByTagName('li');
				for(var i = 0; i < tags.length; i++) {
					// remove DOM Nodes
					for(var j = 0; j < items.length; j++) {
						// remove DOM Nodes
						var tagLink = items[j].getElementsByTagName('a')[0];
						if (tagLink) {
							if (tagLink.innerHTML == tags[i]) {
								tagList.removeChild(items[j]);
								break;
							}
						}
					}				
				}
			} else {
				alert('no taglist:  taglist_'+this.cid);	
			}
		}
	},
	
	// sever side
	ss: {
	
		/**
		* Makes an XMLHTTPRequest to the server, posting the tags to add
		*/
		saveTags: function(tags, cid) {
			var xhr = new fwd_XHR();
			var data = 'option=com_tag&task=author&act=add&cid='+cid+'&tags='+xhr.encode(tags)+'&f=json&no_html=1';
			if (xhr) {
				var url = 'index2.php';
				xhr.cid = cid;
				xhr.tags = tags;
				xhr.post(url, fwd_Tag(cid).ss.handle_saveTags).send(data);
				return true;
			} else {
				var url = 'index.php';
				window.location = url+'?'+data;	
			}
		},
		/**
		* Handles the Response from the Server when adding tags
		*/
		handle_saveTags: function(xhr) {
			if (xhr.xmlhttp.readyState == 4) {
				if (xhr.xmlhttp.status == 200) {
					var ui = fwd_Tag(xhr.cid).ui;
					var resp;
					try {
						resp = xhr.execJSON();
						if (resp.cid != xhr.cid) {
							ui.removeTags(xhr.cid, xhr.tags);
							if (resp.err_msg) {
								alert(resp.err_msg);
							} else {
								alert('An error occurred. Your Tags Could not be saved');
							}
						}
					} catch(e) { /* Invalid JSON */ }
					if (!resp) {
						ui.removeTags(xhr.cid, xhr.tags);
						alert('An Invalid Response occurred. Your Tags Could not be saved');	
					} else {
						// 
					}
				} else {
					alert('An HTTP Error occurred');
				}
			}
		}
	
	}
};

/** 
* Add Events to the existing UI methods
*/
fwd_Tag.on = {};
for (var x in fwd_Tag.prototype.ui) {
	// instantiate new Event Object
	fwd_Tag.on[x] = new fwd_commonLib.Event(x);
	/**
	* todo: fix problem with arguments passing
	fwd_Tag.on[x].attachEventListerner(
		(function() {
			var name = x;
			return function() {
				alert(name);
			}
		})()
	);
	(function() {
		//var x = x;
		var Event = fwd_Tag.on[x];
		var old = fwd_Tag.prototype.ui[x];
		fwd_Tag.prototype.ui[x] = function() {
			alert(x+' event fired with args: '+arguments);
			Event.fireEventListerners(arguments);
			//old(arguments);
		}
	})();
	*/
}