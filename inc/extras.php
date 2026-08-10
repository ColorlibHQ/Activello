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
	$label = 'pwbox-' . ( empty( $post->ID ) ? wp_rand() : $post->ID );
	$o = '<form class="protected-post-form" action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post">
			<div class="row">
				<div class="col-lg-10">
					<p>' . esc_html__( 'This post is password protected. To view it please enter your password below:' ,'activello' ) . '</p>
					<label for="' . esc_attr( $label ) . '">' . esc_html__( 'Password:' ,'activello' ) . ' </label>
					<div class="input-group">
						<input class="form-control" name="post_password" id="' . esc_attr( $label ) . '" type="password">
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
			wp_enqueue_script( 'activello-flexslider' );

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
							echo '<img src="' . esc_url( $photon_url ) . '">';
						} else {
							  echo get_the_post_thumbnail( get_the_ID(), 'activello-slider' );
						}
								echo '<div class="flex-caption">';
							  echo get_the_category_list();
						if ( get_the_title() != '' ) { echo '<a href="' . esc_url( get_permalink() ) . '"><h2 class="entry-title">' . esc_html( get_the_title() ) . '</h2></a>';
						}
								echo '<div class="read-more"><a href="' . esc_url( get_permalink() ) . '">' . esc_html__( 'Read More', 'activello' ) . '</a></div>';
								echo '</div>';
								echo '</li>';
						endif;
					endwhile;
				wp_reset_postdata();
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
	$attributes .= ' style="width: ' . ( (int) $attr['width'] + 10 ) . 'px"';

	$output  = '<figure' . $attributes . '>';
	$output .= do_shortcode( $content );
	$output .= '<figcaption class="caption wp-caption-text">' . wp_kses_post( $attr['caption'] ) . '</figcaption>';
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
		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
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
 * Validate a colour value at output time.
 *
 * Customizer sanitizers reject invalid input on save, but options stored by
 * older theme versions may hold arbitrary text, so never trust a stored value
 * when printing it into the inline <style> block.
 *
 * @param string $color Stored colour value.
 * @return string A safe hex colour, or '' if the value is not one.
 */
function activello_css_color( $color ) {
	$hex = sanitize_hex_color( $color );
	return $hex ? $hex : '';
}

/**
 * Get custom CSS from Theme setting panel and output in header
 */
if ( ! function_exists( 'get_activello_theme_setting' ) ) {
	function get_activello_theme_setting() {

		$accent_color       = activello_css_color( get_theme_mod( 'accent_color' ) );
		$social_color       = activello_css_color( get_theme_mod( 'social_color' ) );
		$social_hover_color = activello_css_color( get_theme_mod( 'social_hover_color' ) );

		echo '<style type="text/css">';
		if ( $accent_color ) {
			echo 'a:hover, a:focus, article.post .post-categories a:hover, article.post .post-categories a:focus, .entry-title a:hover, .entry-title a:focus, .entry-meta a:hover, .entry-meta a:focus, .entry-footer a:hover, .entry-footer a:focus, .read-more a:hover, .read-more a:focus, .social-icons a:hover, .social-icons a:focus, .flex-caption .post-categories a:hover, .flex-caption .post-categories a:focus, .flex-caption .read-more a:hover, .flex-caption .read-more a:focus, .flex-caption h2:hover, .flex-caption h2:focus-within, .comment-meta.commentmetadata a:hover, .comment-meta.commentmetadata a:focus, .post-inner-content .cat-item a:hover, .post-inner-content .cat-item a:focus, .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus, .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, blockquote:before, .navbar-default .navbar-nav > .open > a:focus, .cat-title a, .single .entry-content a, .site-info a:hover, .site-info a:focus {color:' . $accent_color . '}';

			echo 'article.post .post-categories:after, .post-inner-content .cat-item:after, #secondary .widget-title:after, .dropdown-menu>.active>a, .dropdown-menu>.active>a:hover, .dropdown-menu>.active>a:focus {background:' . $accent_color . '}';

			echo '.label-default[href]:hover, .label-default[href]:focus, .btn-default:hover, .btn-default:focus, .btn-default:active, .btn-default.active, #image-navigation .nav-previous a:hover, #image-navigation .nav-previous a:focus, #image-navigation .nav-next a:hover, #image-navigation .nav-next a:focus, .woocommerce #respond input#submit:hover, .woocommerce #respond input#submit:focus, .woocommerce a.button:hover, .woocommerce a.button:focus, .woocommerce button.button:hover, .woocommerce button.button:focus, .woocommerce input.button:hover, .woocommerce input.button:focus, .woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.alt:focus, .woocommerce a.button.alt:hover, .woocommerce a.button.alt:focus, .woocommerce button.button.alt:hover, .woocommerce button.button.alt:focus, .woocommerce input.button.alt:hover, .woocommerce input.button.alt:focus, .input-group-btn:last-child>.btn:hover, .input-group-btn:last-child>.btn:focus, .scroll-to-top:hover, .scroll-to-top:focus, button, html input[type=button]:hover, html input[type=button]:focus, input[type=reset]:hover, input[type=reset]:focus, .comment-list li .comment-body:after, .page-links a:hover span, .page-links a:focus span, .page-links span, input[type=submit]:hover, input[type=submit]:focus, .comment-form #submit:hover, .comment-form #submit:focus, .tagcloud a:hover, .tagcloud a:focus, .single .entry-content a:hover, .single .entry-content a:focus, .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover, .dropdown-menu> li> a:hover, .dropdown-menu> li> a:focus, .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus {background-color:' . $accent_color . '; }';

			echo 'input[type="text"]:focus, input[type="email"]:focus, input[type="tel"]:focus, input[type="url"]:focus, input[type="password"]:focus, input[type="search"]:focus, textarea:focus { outline-color: ' . $accent_color . '; }';

		}
		if ( $social_color ) {
			echo '#social a, .header-search-icon { color:' . $social_color . '}';
		}
		if ( $social_hover_color ) {
			echo '#social a:hover, #social a:focus, .header-search-icon:hover, .header-search-icon:focus  { color:' . $social_hover_color . '}';
		}

		if ( get_theme_mod( 'custom_css' ) ) {
			// Legacy path: modern versions migrate this mod to core Custom CSS on
			// setup. Strip tags so a stored value can never break out of <style>.
			echo wp_strip_all_tags( get_theme_mod( 'custom_css' ) );
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
		<script>
			document.addEventListener( 'DOMContentLoaded', function () {
				if ( window.innerWidth < 767 ) {
					return;
				}
				document.querySelectorAll( '.navbar-nav > li.menu-item > a' ).forEach( function ( link ) {
					link.addEventListener( 'click', function () {
						if ( link.getAttribute( 'target' ) !== '_blank' ) {
							window.location = link.getAttribute( 'href' );
						} else {
							var win = window.open( link.getAttribute( 'href' ), '_blank' );
							win.focus();
						}
					} );
				} );
			} );
		</script>
	<?php }
}
add_action( 'wp_footer', 'activello_make_top_level_menu_clickable', 1 );
