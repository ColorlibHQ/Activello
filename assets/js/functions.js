/**
 * Activello theme scripts.
 *
 * Plain DOM APIs -- no jQuery. The two globals (ActivelloIsMobile and
 * generateMobileMenu) are kept on window because child themes may call them.
 */
( function () {
	'use strict';

	/**
	 * Run a callback once the DOM is ready.
	 *
	 * @param {Function} fn Callback.
	 */
	function ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		} else {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
	}

	/**
	 * Add class names to every element matching a selector.
	 *
	 * @param {string} selector CSS selector.
	 * @param {Array}  classes  Class names to add.
	 */
	function addClasses( selector, classes ) {
		document.querySelectorAll( selector ).forEach( function ( el ) {
			el.classList.add.apply( el.classList, classes );
		} );
	}

	/**
	 * jQuery's "swing" easing, so the scroll-to-top motion is unchanged.
	 *
	 * @param {number} p Progress from 0 to 1.
	 * @return {number} Eased progress.
	 */
	function swing( p ) {
		return 0.5 - Math.cos( p * Math.PI ) / 2;
	}

	function prefersReducedMotion() {
		return window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	}

	/**
	 * The display value jQuery's .show() would restore for an element.
	 *
	 * @param {Element} el Target element.
	 * @return {string} CSS display value.
	 */
	function defaultDisplay( el ) {
		if ( el.tagName === 'TABLE' ) {
			return 'table';
		}

		if ( el.tagName === 'SELECT' ) {
			return 'inline-block';
		}

		return 'block';
	}

	/**
	 * Fade an element in or out, matching jQuery's fadeIn()/fadeOut().
	 *
	 * @param {Element} el       Target element.
	 * @param {boolean} into     True to fade in, false to fade out.
	 * @param {number}  duration Animation length in ms.
	 */
	function fade( el, into, duration ) {
		if ( ! el || el.dataset.activelloFading === String( into ) ) {
			return;
		}

		el.dataset.activelloFading = String( into );

		if ( prefersReducedMotion() ) {
			el.style.opacity = into ? '1' : '';
			el.style.display = into ? defaultDisplay( el ) : 'none';
			return;
		}

		var start = null;
		var from  = parseFloat( window.getComputedStyle( el ).opacity );

		if ( isNaN( from ) ) {
			from = into ? 0 : 1;
		}

		if ( into ) {
			el.style.display = defaultDisplay( el );
		}

		function step( ts ) {
			if ( start === null ) {
				start = ts;
			}

			var p = Math.min( ( ts - start ) / duration, 1 );
			var v = from + ( ( into ? 1 : 0 ) - from ) * swing( p );

			el.style.opacity = String( v );

			if ( p < 1 ) {
				window.requestAnimationFrame( step );
			} else if ( ! into ) {
				el.style.display = 'none';
				el.style.opacity = '';
			}
		}

		window.requestAnimationFrame( step );
	}

	/**
	 * Detect a mobile user agent.
	 *
	 * Kept as-is (including the UA sniff) because the mobile menu styling below
	 * depends on it and child themes may call it.
	 *
	 * @return {boolean} True on a mobile user agent.
	 */
	window.ActivelloIsMobile = function () {
		return (
			navigator.userAgent.match( /Android/i ) ||
			navigator.userAgent.match( /webOS/i ) ||
			navigator.userAgent.match( /iPhone/i ) ||
			navigator.userAgent.match( /iPod/i ) ||
			navigator.userAgent.match( /iPad/i ) ||
			navigator.userAgent.match( /BlackBerry/ )
		);
	};

	/**
	 * Toggle the tap-friendly menu class on touch devices at tablet width and up.
	 */
	window.generateMobileMenu = function () {
		var menu = document.querySelector( '#masthead .site-navigation-inner .navbar-collapse > ul.nav' );

		if ( ! menu ) {
			return;
		}

		if ( window.ActivelloIsMobile() && window.innerWidth > 768 ) {
			menu.classList.add( 'activello-mobile-menu' );
		} else {
			menu.classList.remove( 'activello-mobile-menu' );
		}
	};

	/**
	 * Apply Bootstrap classes to markup WordPress generates, then reveal the
	 * elements style.css keeps hidden until they have been dressed up.
	 */
	function styleCoreMarkup() {
		addClasses( '.widget_rss ul', [ 'media-list' ] );
		addClasses( '.postform', [ 'form-control' ] );
		addClasses( 'table#wp-calendar', [ 'table', 'table-striped' ] );

		document.querySelectorAll( '.widget_rss ul, .postform, table#wp-calendar' ).forEach( function ( el ) {
			// 200ms matches the jQuery .show('fast') this replaced.
			fade( el, true, 200 );
		} );
	}

	/**
	 * Scroll-to-top button: reveal past 100px, and smooth-scroll on click.
	 */
	function initScrollToTop() {
		var button = document.querySelector( '.scroll-to-top' );

		if ( ! button ) {
			return;
		}

		function onScroll() {
			fade( button, ( window.pageYOffset || document.documentElement.scrollTop ) > 100, 400 );
		}

		window.addEventListener( 'scroll', onScroll, { passive: true } );
		onScroll();

		button.addEventListener( 'click', function ( event ) {
			event.preventDefault();

			var from = window.pageYOffset || document.documentElement.scrollTop;

			if ( ! from ) {
				return;
			}

			if ( prefersReducedMotion() ) {
				window.scrollTo( 0, 0 );
				return;
			}

			var duration = 800;
			var start    = null;

			function step( ts ) {
				if ( start === null ) {
					start = ts;
				}

				var p = Math.min( ( ts - start ) / duration, 1 );

				window.scrollTo( 0, from * ( 1 - swing( p ) ) );

				if ( p < 1 ) {
					window.requestAnimationFrame( step );
				}
			}

			window.requestAnimationFrame( step );
		} );
	}

	/**
	 * "..." toggle for the extra-categories list, mobile user agents only.
	 */
	function initShowMoreCategories() {
		document.querySelectorAll( '.show-more-categories' ).forEach( function ( el ) {
			el.addEventListener( 'click', function () {
				if ( window.ActivelloIsMobile() ) {
					el.classList.toggle( 'active' );
				}
			} );
		} );
	}

	/**
	 * Sub-menu toggles the nav walker renders next to parent menu items.
	 */
	function initDropdowns() {
		document.querySelectorAll( '.activello-dropdown' ).forEach( function ( el ) {
			el.addEventListener( 'click', function () {
				var parent = el.parentNode;

				if ( ! parent ) {
					return;
				}

				Array.prototype.forEach.call( parent.children, function ( child ) {
					if ( child.tagName === 'UL' ) {
						child.classList.toggle( 'active' );
					}
				} );
			} );
		} );
	}

	ready( function () {
		styleCoreMarkup();
		initScrollToTop();
		initShowMoreCategories();
		initDropdowns();
		window.generateMobileMenu();

		window.addEventListener( 'resize', window.generateMobileMenu );
	} );
}() );
