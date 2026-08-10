/**
 * Front-page slider initialisation.
 *
 * FlexSlider is a jQuery plugin, so this file keeps its jQuery dependency.
 * Initialised on window load so every slide image has dimensions before the
 * slider measures itself. Uses .on( 'load' ) rather than the .load() shorthand
 * jQuery 3 removed, so it no longer needs jQuery Migrate to run.
 */
( function ( $ ) {
	'use strict';

	$( window ).on( 'load', function () {
		if ( 0 === $( '.flexslider' ).length ) {
			return;
		}

		$( '.flexslider' ).flexslider( {
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
		} );
	} );
}( jQuery ) );
