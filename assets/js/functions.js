// Bootstrap specific functions and styling
jQuery( document ).ready(function() {

	// Now we'll add some classes for the WordPress default widgets - let's go
	jQuery( '.widget_rss ul' ).addClass( 'media-list' );

	// Add Bootstrap style for drop-downs
	jQuery( '.postform' ).addClass( 'form-control' );

	// Add Bootstrap styling for tables
	jQuery( 'table#wp-calendar' ).addClass( 'table table-striped' );

	jQuery( '.widget_rss ul, .postform, table#wp-calendar' ).show( 'fast' );

});

// JQuery powered scroll to top

function ActivelloIsMobile() {
    return (
        ( navigator.userAgent.match( /Android/i ) ) ||
        ( navigator.userAgent.match( /webOS/i ) ) ||
        ( navigator.userAgent.match( /iPhone/i ) ) ||
        ( navigator.userAgent.match( /iPod/i ) ) ||
        ( navigator.userAgent.match( /iPad/i ) ) ||
        ( navigator.userAgent.match( /BlackBerry/ ) )
    );
}

function generateMobileMenu() {
	var menu = jQuery( '#masthead .site-navigation-inner .navbar-collapse > ul.nav' );
	if ( ActivelloIsMobile() && jQuery( window ).width() > 768 ) {
		menu.addClass( 'activello-mobile-menu' );
	} else {
		menu.removeClass( 'activello-mobile-menu' );
	}
}

jQuery( document ).ready(function() {

	//Check to see if the window is top if not then display button
	jQuery( window ).scroll(function() {
		if ( jQuery( this ).scrollTop() > 100 ) {
			jQuery( '.scroll-to-top' ).fadeIn();
		} else {
			jQuery( '.scroll-to-top' ).fadeOut();
		}
	});

	//Click event to scroll to top
	jQuery( '.scroll-to-top' ).click(function() {
		jQuery( 'html, body' ).animate({ scrollTop: 0 }, 800 );
		return false;
	});

	jQuery( '.show-more-categories' ).click( function() {
		if ( ActivelloIsMobile() ) {
			jQuery( this ).toggleClass( 'active' );
		}
	});

	jQuery( '.activello-dropdown' ).click( function( evt ) {
		jQuery( this ).parent().find( '> ul' ).toggleClass( 'active' );
	});
	generateMobileMenu();
	jQuery( window ).resize(function() {
		generateMobileMenu();
	});

});

// Slider functions
// Can also be used with $(document).ready()
jQuery( document ).ready(function( $ ) {
	$( window ).load(function() {
		if ( 0 !== $( '.flexslider' ).length ) {
			$( '.flexslider' ).flexslider({
				animation: 'fade',
				easing: 'swing',
				direction: 'horizontal',
				reverse: false,
				animationLoop: true,
				smoothHeight: true,
				startAt: 0,
				slideshow: true,
				slideshowSpeed: 7000,
				animationSpeed: 600,
				initDelay: 0,
				randomize: false,
				fadeFirstSlide: true,
				pauseOnAction: true,
				pauseOnHover: false,
				pauseInvisible: true,
				useCSS: true,
				touch: true,
				directionNav: true,
				prevText: '',
				nextText: ''

			});
		}
	});
});
