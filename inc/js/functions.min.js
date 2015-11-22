// Bootstrap specific functions and styling
jQuery(document).ready(function(){

	// Now we'll add some classes for the WordPress default widgets - let's go
	jQuery( '.widget_rss ul' ).addClass( 'media-list' );

	// Add Bootstrap style for drop-downs
	jQuery( '.postform' ).addClass( 'form-control' );

	// Add Bootstrap styling for tables
	jQuery( 'table#wp-calendar' ).addClass( 'table table-striped');

	jQuery( '.widget_rss ul, .postform, table#wp-calendar' ).show( "fast" );

});

// jQuery powered scroll to top

jQuery(document).ready(function(){

	//Check to see if the window is top if not then display button
	jQuery(window).scroll(function(){
		if (jQuery(this).scrollTop() > 100) {
			jQuery('.scroll-to-top').fadeIn();
		} else {
			jQuery('.scroll-to-top').fadeOut();
		}
	});

	//Click event to scroll to top
	jQuery('.scroll-to-top').click(function(){
		jQuery('html, body').animate({scrollTop : 0},800);
		return false;
	});

});

// Slider functions
// Can also be used with $(document).ready()
jQuery(document).ready(function($){$(window).load(function(){if ($('.flexslider').length != 0) { $('.flexslider').flexslider({
      	animation: "fade",              //String: Select your animation type, "fade" or "slide"
	    easing: "swing",                //{NEW} String: Determines the easing method used in jQuery transitions. jQuery easing plugin is supported!
	    direction: "horizontal",        //String: Select the sliding direction, "horizontal" or "vertical"
	    reverse: false,                 //{NEW} Boolean: Reverse the animation direction
	    animationLoop: true,            //Boolean: Should the animation loop? If false, directionNav will received "disable" classes at either end
	    smoothHeight: true,            //{NEW} Boolean: Allow height of the slider to animate smoothly in horizontal mode
	    startAt: 0,                     //Integer: The slide that the slider should start on. Array notation (0 = first slide)
	    slideshow: true,                //Boolean: Animate slider automatically
	    slideshowSpeed: 7000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
	    animationSpeed: 600,            //Integer: Set the speed of animations, in milliseconds
	    initDelay: 0,                   //{NEW} Integer: Set an initialization delay, in milliseconds
	    randomize: false,               //Boolean: Randomize slide order
	    fadeFirstSlide: true,           //Boolean: Fade in the first slide when animation type is "fade"

	    // Usability features
	    pauseOnAction: true,            //Boolean: Pause the slideshow when interacting with control elements, highly recommended.
	    pauseOnHover: false,            //Boolean: Pause the slideshow when hovering over slider, then resume when no longer hovering
	    pauseInvisible: true,       		//{NEW} Boolean: Pause the slideshow when tab is invisible, resume when visible. Provides better UX, lower CPU usage.
	    useCSS: true,                   //{NEW} Boolean: Slider will use CSS3 transitions if available
	    touch: true,                    //{NEW} Boolean: Allow touch swipe navigation of the slider on touch-enabled devices

	    directionNav: true,             //Boolean: Create navigation for previous/next navigation? (true/false)
    	prevText: "",           //String: Set the text for the "previous" directionNav item
    	nextText: ""               //String: Set the text for the "next" directionNav item

})}})});
