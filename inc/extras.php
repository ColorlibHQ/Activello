<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package activello
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function activello_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'activello_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function activello_body_classes( $classes ) {
	// Adds a class of group-blog to blogs with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( get_theme_mod( 'activello_sidebar_position' ) == 'pull-right' ) {
		$classes[] = 'has-sidebar-left';
	} elseif ( get_theme_mod( 'activello_sidebar_position' ) == 'no-sidebar' ) {
		$classes[] = 'has-no-sidebar';
	} elseif ( get_theme_mod( 'activello_sidebar_position' ) == 'full-width' ) {
		$classes[] = 'has-full-width';
	} else {
		$classes[] = 'has-sidebar-right';
	}

	$blog_layout = get_theme_mod( 'activello_blog_layout', 'default' );
	if ( is_home() && 'default' == $blog_layout ) {
		$classes[] = 'half-posts';
	}

	return $classes;
}
add_filter( 'body_class', 'activello_body_classes' );


// Mark Posts/Pages as Untiled when no title is used
add_filter( 'the_title', 'activello_title' );

function activello_title( $title ) {
	if ( '' == $title ) {
		return __( 'Untitled', 'activello' );
	} else {
		return $title;
	}
}

/**
 * Password protected post form using Boostrap classes
 */
add_filter( 'the_password_form', 'activello_custom_password_form' );

function activello_custom_password_form() {
	global $post;
	$label = 'pwbox-' . ( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<form class="protected-post-form" action="' . get_option( 'siteurl' ) . '/wp-login.php?action=postpass" method="post">
			<div class="row">
				<div class="col-lg-10">
					<p>' . esc_html__( 'This post is password protected. To view it please enter your password below:' ,'activello' ) . '</p>
					<label for="' . $label . '">' . esc_html__( 'Password:' ,'activello' ) . ' </label>
					<div class="input-group">
						<input class="form-control" value="' . get_search_query() . '" name="post_password" id="' . $label . '" type="password">
						<span class="input-group-btn"><button type="submit" class="btn btn-default" name="submit" id="searchsubmit" value="' . esc_attr__( 'Submit','activello' ) . '">' . esc_html__( 'Submit' ,'activello' ) . '</button></span>
					</div>
				</div>
			</div>
		</form>';
	return $o;
}

// Add Bootstrap classes for table
add_filter( 'the_content', 'activello_add_custom_table_class' );
function activello_add_custom_table_class( $content ) {
	return str_replace( '<table>', '<table class="table table-hover">', $content );
}

if ( ! function_exists( 'activello_header_menu' ) ) :
	/**
 * Header menu (should you choose to use one)
 */
	function activello_header_menu() {

		// display the WordPress Custom Menu if available
		wp_nav_menu( array(
			'menu'              => 'primary',
			'theme_location'    => 'primary',
			'container'         => 'div',
			'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
			'menu_class'        => 'nav navbar-nav',
			'fallback_cb'       => 'Activello_Wp_Bootstrap_Navwalker::fallback',
			'walker'            => new Activello_Wp_Bootstrap_Navwalker(),
		));
	}
endif;

if ( ! function_exists( 'activello_featured_slider' ) ) :
	/**
 * Featured image slider, displayed on front page for static page and blog
 */
	function activello_featured_slider() {
		if ( ( is_home() || is_front_page() ) && get_theme_mod( 'activello_featured_hide' ) == 1 ) {

			wp_enqueue_style( 'flexslider-css' );
			wp_enqueue_script( 'flexslider-js' );

			echo '<div class="flexslider">';
			echo '<ul class="slides">';

			$slidecat = get_theme_mod( 'activello_featured_cat' );
			$slidelimit = get_theme_mod( 'activello_featured_limit', -1 );
			$slider_args = array(
				'cat' => $slidecat,
				'posts_per_page' => $slidelimit,
				'meta_query' => array(
					array(
						'key' => '_thumbnail_id',
						'compare' => 'EXISTS',
					),
				),
			);
			$query = new WP_Query( $slider_args );
			if ( $query->have_posts() ) :

				while ( $query->have_posts() ) : $query->the_post();
					if ( ( function_exists( 'has_post_thumbnail' ) ) && ( has_post_thumbnail() ) ) :
						echo '<li>';
						if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'photon' ) ) {
							$feat_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
							$args = array(
								'resize' => '1920,550',
							);
							$photon_url = jetpack_photon_url( $feat_image_url[0], $args );
							echo '<img src="' . $photon_url . '">';
						} else {
							  echo get_the_post_thumbnail( get_the_ID(), 'activello-slider' );
						}
								echo '<div class="flex-caption">';
							  echo get_the_category_list();
						if ( get_the_title() != '' ) { echo '<a href="' . get_permalink() . '"><h2 class="entry-title">' . get_the_title() . '</h2></a>';
						}
								echo '<div class="read-more"><a href="' . get_permalink() . '">' . __( 'Read More', 'activello' ) . '</a></div>';
								echo '</div>';
								echo '</li>';
						endif;
					endwhile;
				wp_reset_query();
			endif;
			echo '</ul>';
			echo ' </div>';
		}// End if().
	}
endif;

/**
 * function to show the footer info, copyright information
 */
function activello_footer_info() {
	global $activello_footer_info;
	printf( esc_html__( 'Theme by %1$s Powered by %2$s', 'activello' ) , '<a href="http://colorlib.com/" target="_blank">Colorlib</a>', '<a href="http://wordpress.org/" target="_blank">WordPress</a>' );
}


/**
 * Add Bootstrap thumbnail styling to images with captions
 * Use <figure> and <figcaption>
 *
 * @link http://justintadlock.com/archives/2011/07/01/captions-in-wordpress
 */
function activello_caption( $output, $attr, $content ) {
	if ( is_feed() ) {
		return $output;
	}

	$defaults = array(
		'id'      => '',
		'align'   => 'alignnone',
		'width'   => '',
		'caption' => '',
	);

	$attr = shortcode_atts( $defaults, $attr );

	// If the width is less than 1 or there is no caption, return the content wrapped between the [caption] tags
	if ( $attr['width'] < 1 || empty( $attr['caption'] ) ) {
		return $content;
	}

	// Set up the attributes for the caption <figure>
	$attributes  = ( ! empty( $attr['id'] ) ? ' id="' . esc_attr( $attr['id'] ) . '"' : '' );
	$attributes .= ' class="thumbnail wp-caption ' . esc_attr( $attr['align'] ) . '"';
	$attributes .= ' style="width: ' . ( esc_attr( $attr['width'] ) + 10) . 'px"';

	$output  = '<figure' . $attributes . '>';
	$output .= do_shortcode( $content );
	$output .= '<figcaption class="caption wp-caption-text">' . $attr['caption'] . '</figcaption>';
	$output .= '</figure>';

	return $output;
}
add_filter( 'img_caption_shortcode', 'activello_caption', 10, 3 );

/**
 * Skype URI support for social media icons
 */
function activello_allow_skype_protocol( $protocols ) {
	$protocols[] = 'skype';
	return $protocols;
}
add_filter( 'kses_allowed_protocols' , 'activello_allow_skype_protocol' );

/*
 * This display blog description from wp customizer setting.
 */
function activello_cats() {
	$cats = array();
	$cats[0] = 'All';

	foreach ( get_categories() as $categories => $category ) {
		$cats[ $category->term_id ] = $category->name;
	}
	return $cats;
}

/**
 * Custom comment template
 */
function activello_cb_comment( $comment, $args, $depth ) {

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
	<<?php echo $tag ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>

	<div class="comment-author vcard asdasd">
		<?php if ( 0 != $args['avatar_size'] ) {
			echo get_avatar( $comment, $args['avatar_size'] );
} ?>
		<?php printf( __( '<cite class="fn">%s</cite> <span class="says">says:</span>', 'activello' ), get_comment_author_link() ); ?>
		<?php
			$comments_reply_args = array(
				'add_below' => $add_below,
				'depth' => $depth,
				'max_depth' => $args['max_depth'],
			);
			comment_reply_link( array_merge( $args, $comments_reply_args ) ); ?>
		<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
			/* translators: 1: date, 2: time */
			printf( __( '%1$s at %2$s', 'activello' ), get_comment_date(), get_comment_time() ); ?></a><?php edit_comment_link( __( 'Edit', 'activello' ), '  ', '' );
			?>
		</div>
	</div>

	<?php if ( '0' == $comment->comment_approved ) : ?>
		<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'activello' ); ?></em>
		<br />
	<?php endif; ?>

	<?php comment_text(); ?>

	<?php if ( 'div' != $args['style'] ) : ?>
		</div>
	<?php endif; ?>
<?php
}

/**
 * Get custom CSS from Theme setting panel and output in header
 */
if ( ! function_exists( 'get_activello_theme_setting' ) ) {
	function get_activello_theme_setting() {

		echo '<style type="text/css">';
		if ( get_theme_mod( 'accent_color' ) ) {
			echo 'a:hover, a:focus,article.post .post-categories a:hover,
			  .entry-title a:hover, .entry-meta a:hover, .entry-footer a:hover,
			  .read-more a:hover, .social-icons a:hover,
			  .flex-caption .post-categories a:hover, .flex-caption .read-more a:hover,
			  .flex-caption h2:hover, .comment-meta.commentmetadata a:hover,
			  .post-inner-content .cat-item a:hover,.navbar-default .navbar-nav > .active > a,
			  .navbar-default .navbar-nav > .active > a:hover,
			  .navbar-default .navbar-nav > .active > a:focus,
			  .navbar-default .navbar-nav > li > a:hover,
			  .navbar-default .navbar-nav > li > a:focus, .navbar-default .navbar-nav > .open > a,
			  .navbar-default .navbar-nav > .open > a:hover, blockquote:before,
			  .navbar-default .navbar-nav > .open > a:focus, .cat-title a,
			  .single .entry-content a, .site-info a:hover {color:' . esc_html( get_theme_mod( 'accent_color' ) ) . '}';

			echo 'article.post .post-categories:after, .post-inner-content .cat-item:after, #secondary .widget-title:after, .dropdown-menu>.active>a, .dropdown-menu>.active>a:hover, .dropdown-menu>.active>a:focus {background:' . esc_html( get_theme_mod( 'accent_color' ) ) . '}';

			echo '.btn-default:hover, .label-default[href]:hover,
			  .label-default[href]:focus, .btn-default:hover,
			  .btn-default:focus, .btn-default:active,
			  .btn-default.active, #image-navigation .nav-previous a:hover,
			  #image-navigation .nav-next a:hover, .woocommerce #respond input#submit:hover,
			  .woocommerce a.button:hover, .woocommerce button.button:hover,
			  .woocommerce input.button:hover, .woocommerce #respond input#submit.alt:hover,
			  .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover,
			  .woocommerce input.button.alt:hover, .input-group-btn:last-child>.btn:hover, .scroll-to-top:hover,
			  button, html input[type=button]:hover, input[type=reset]:hover, .comment-list li .comment-body:after, .page-links a:hover span, .page-links span,
			  input[type=submit]:hover, .comment-form #submit:hover, .tagcloud a:hover,
			  .single .entry-content a:hover,  
			  .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover,
			  .dropdown-menu> li> a:hover, .dropdown-menu> li> a:focus,
			  .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus{background-color:' . esc_html( get_theme_mod( 'accent_color' ) ) . '; }';
		}
		if ( get_theme_mod( 'social_color' ) ) {
			echo '#social a, .header-search-icon { color:' . esc_html( get_theme_mod( 'social_color' ) ) . '}';
		}
		if ( get_theme_mod( 'social_hover_color' ) ) {
			echo '#social a:hover, .header-search-icon:hover { color:' . esc_html( get_theme_mod( 'social_hover_color' ) ) . '}';
		}

		if ( get_theme_mod( 'custom_css' ) ) {
			echo html_entity_decode( esc_html( get_theme_mod( 'custom_css', 'no entry' ) ) );
		}

		echo '</style>';
	}
} // End if().
add_action( 'wp_head', 'get_activello_theme_setting', 10 );

/**
 * Adds the URL to the top level navigation menu item
 */
function activello_add_top_level_menu_url( $atts, $item, $args ) {
	if ( ! wp_is_mobile() && isset( $args->has_children ) && $args->has_children ) {
		$atts['href'] = ! empty( $item->url ) ? $item->url : '';
	}
	return $atts;
}
add_filter( 'nav_menu_link_attributes', 'activello_add_top_level_menu_url', 99, 3 );

/**
 * Makes the top level navigation menu item clickable
 */
function activello_make_top_level_menu_clickable() {
	if ( ! wp_is_mobile() ) { ?>
		<script type="text/javascript">
			jQuery( document ).ready( function( $ ){
				if ( $( window ).width() >= 767 ){
					$( '.navbar-nav > li.menu-item > a' ).click( function(){
						if( $( this ).attr('target') !== '_blank' ){
							window.location = $( this ).attr( 'href' );
						}else{
							var win = window.open($( this ).attr( 'href' ), '_blank');
							win.focus();
						}
					});
				}
			});
		</script>
	<?php }
}
add_action( 'wp_footer', 'activello_make_top_level_menu_clickable', 1 );
