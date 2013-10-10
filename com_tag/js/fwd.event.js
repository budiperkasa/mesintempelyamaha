/**
* Creates new Event
* Example Usage: 
*	var Evt = new fwd_Tag('id').Event();
*	Evt.attachEventListerner(function() { alert('hi im the listerner'); });
*	Evt.fireEventListerners();												
*/
fwd_Tag.Event = function() {
	var _handlers = [];
	return {
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
