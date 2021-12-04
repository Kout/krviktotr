// ================================================
// PHP image manager - iManager 
// ================================================
// iManager - resize dialog to content
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: LGPL - see license.txt
// (c)2005 All rights reserved.
// ================================================
// Revision: 1.0                   Date: 2005/04/27
// ================================================
	function resizeDialogToContent() {		
		if (iManager.isMSIE) {			
			var dw = parseInt(window.dialogWidth);
			if(dw) {				
				difw = dw - this.document.body.clientWidth;
				window.dialogWidth = this.document.body.scrollWidth + difw + 'px';	
				var dh = parseInt(window.dialogHeight);				
				difh = dh - this.document.body.clientHeight;
				window.dialogHeight = this.document.body.scrollHeight + difh + 'px';		
				//window.moveTo(self.screen.width/4,20);
			}
		} else if (iManager.isGecko) {			
			window.sizeToContent();			
			window.scrollTo(0,0);
			window.moveTo(0,0);
		}
	}
//-------------------------------------------------------------------------