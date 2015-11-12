<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package activello
 */
?><!doctype html>
	<!--[if !IE]>
	<html class="no-js non-ie" <?php language_attributes(); ?>> <![endif]-->
	<!--[if IE 7 ]>
	<html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
	<!--[if IE 8 ]>
	<html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
	<!--[if IE 9 ]>
	<html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
	<!--[if gt IE 9]><!-->
<html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
		<nav class="navbar navbar-default" role="navigation">
			<div class="container">
				<div class="row">
					<div class="site-navigation-inner col-sm-12">
						<div class="navbar-header">
							<button type="button" class="btn navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
						</div>
						<?php activello_header_menu(); // main navigation ?>

						<div class="nav-search">
							<form method="get">
								<input type="text" name="s" placeholder="<?php echo esc_attr_x( 'Search and hit enter...', 'search placeholder', 'activello' ); ?>">
								<button type="submit" class="header-search-icon" name="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button', 'activello' ); ?>"><i class="fa fa-search"></i></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</nav><!-- .site-navigation -->

		<?php if( get_header_image() != '' ) : ?>

		<div id="logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php header_image(); ?>"  height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php bloginfo( 'name' ); ?>"/></a>
		<?php if( get_bloginfo( 'description' ) != "" ) : ?>
		<div class="tagline"><?php bloginfo( 'description' ); ?></div>
		<?php endif; ?>
		</div><!-- end of #logo -->



		<?php endif; // header image was removed ?>

		<?php if( !get_header_image() ) : ?>



		<div class="container">
			<div id="logo">
				<span class="site-name"><a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
					<?php if( activello_logo() != "" ) : ?>
						<img src="<?php echo activello_logo(); ?>" alt="logo">
					<?php else : ?>
						<?php bloginfo( 'name' ); ?>
					<?php endif; ?>
				</a></span><!-- end of .site-name -->
				
				<?php if( get_bloginfo( 'description' ) != "" ) : ?>
				<div class="tagline"><?php bloginfo( 'description' ); ?></div>
				<?php endif; ?>
			</div><!-- end of #logo -->
			
			<?php if( ! is_front_page() || ! is_home() ) : ?>
			<div id="line"></div>
			<?php endif; ?>
		</div>
		<?php endif; // header image was removed (again) ?>	
	</header><!-- #masthead -->

	
	<div id="content" class="site-content">

		<div class="top-section">
			<?php activello_featured_slider(); ?>
		</div>

		<div class="container main-content-area">
		
			<?php if( is_single() && has_category() ) : ?>
			<div class="cat-title">
				<?php echo get_the_category_list(); ?>
			</div>		
			<?php endif; ?>
                        <?php
                            global $post;
                            
                            if( is_home() && is_sticky( $post->ID ) ){
                                    $layout_class = get_theme_mod( 'activello_sidebar_position' );
                            } 
                            elseif( get_post_meta($post->ID, 'site_layout', true) ){
                                    $layout_class = get_post_meta($post->ID, 'site_layout', true);
                            }
                            else{
                                    $layout_class = get_theme_mod( 'activello_sidebar_position' );
                            }?>
		
			<div class="row">
				<div class="main-content-inner <?php echo activello_main_content_bootstrap_classes(); ?> <?php echo $layout_class; ?>">
