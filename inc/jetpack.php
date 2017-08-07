<?php
/**
 * Jetpack Compatibility File
 * See: http://jetpack.me/
 *
 * @package activello
 */

/**
 * Add theme support for Infinite Scroll.
 * See: http://jetpack.me/support/infinite-scroll/
 */
function activello_jetpack_setup() {
	add_theme_support( 'infinite-scroll', array(
		'type'      => 'click',
		'container' => 'main',
		'footer'    => 'page',
		'render'	=> 'activello_jetpack_post_template',
	) );
}
add_action( 'after_setup_theme', 'activello_jetpack_setup' );

function activello_jetpack_post_template(){
	while( have_posts() ) {
	    the_post();
	    get_template_part( 'template-parts/content' );
	}
	
}