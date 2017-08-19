/**
 * cbpFWTabs.js v1.0.0
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 *
 * Copyright 2014, Codrops
 * http://www.codrops.com
 */


;( function( window ) {

	'use strict';

	function extend( a, b ) {
		for( var key in b ) {
			if( b.hasOwnProperty( key ) ) {
				a[key] = b[key];
			}
		}
		return a;
	}

	function CBPFWTabs( el, options ) {
		this.el = el;
		this.options = extend( {}, this.options );
  		extend( this.options, options );
  		this._init();
	}

	CBPFWTabs.prototype.options = {
		start : 0
	};

	CBPFWTabs.prototype._init = function() {

		var tabsele = this.el.querySelectorAll( 'nav > ul > li' );
		 var itemsele = this.el.querySelectorAll( '.tabcontent > .tabsection' ) ;

		var aTabs = [];
		for (var i = 0; i < tabsele.length; i++) {
		    aTabs.push(tabsele[i]);
		}

		var aItems = [];
		for (var i = 0; i < itemsele.length; i++) {
		    aItems.push(itemsele[i]);
		}

		// tabs elemes
		//this.tabs = [].slice.call( this.el.querySelectorAll( 'nav > ul > li' ) );
		this.tabs = aTabs
		// content items
		//this.items = [].slice.call( this.el.querySelectorAll( '.tabcontent > section' ) );
		this.items = aItems
		// current index
		this.current = -1;
		// show current content item
		this._show();
		// init events
		this._initEvents();


	};

	CBPFWTabs.prototype._initEvents = function() {
		var self = this;

			jQuery.each( this.tabs, function( idx, tab ) {

				//tab.addEventListener( 'click', function( ev ) {
			jQuery(tab).on('click', function (ev) {
				ev.preventDefault();
				self._show( idx );
			} );

			} )

		/*this.tabs.forEach( function( tab, idx ) {
			tab.addEventListener( 'click', function( ev ) {
				ev.preventDefault();
				self._show( idx );
			} );
		} );*/
	};

	CBPFWTabs.prototype._show = function( idx ) {
		if( this.current >= 0 ) {
			this.tabs[ this.current ].className = '';
			this.items[ this.current ].className = '';
		}

		// change current
		this.current = idx != undefined ? idx : this.options.start >= 0 && this.options.start < this.items.length ? this.options.start : 0;
		this.tabs[ this.current ].className = 'tab-current';
		this.items[ this.current ].className = 'tabcontent-current';

	};


	// add to global namespace
	window.CBPFWTabs = CBPFWTabs;

})( window );
