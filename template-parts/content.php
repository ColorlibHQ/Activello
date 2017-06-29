<?php
/**
 * @package activello
 */


global $wp_query;
$index = $wp_query->current_post + 1;
$layout = get_theme_mod( 'activello_sidebar_position' );
$blog_layout = get_theme_mod( 'activello_blog_layout', 'default' );

$image_size = 'activello-big';
if ( $index > 2 && is_home() && 'default' == $blog_layout ) {
	if ( 'full-width' == $layout ) {
		$image_size = 'activello-medium';
	} else {
		$image_size = 'activello-thumbnail';
	}
} elseif ( 'full-width' == $layout ) {
	$image_size = 'activello-featured';
}


?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-item-wrap">
		<div class="post-inner-content">
			<header class="entry-header page-header">
				<?php echo activello_get_single_category( get_the_ID() ); ?>
				<h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>

				<?php if ( 'post' == get_post_type() ) : ?>
				<div class="entry-meta">
					<?php activello_posted_on(); ?>

					<?php
						edit_post_link(
							sprintf(
								/* translators: %s: Name of current post */
								esc_html__( 'Edit %s', 'activello' ),
								the_title( '<span class="screen-reader-text">"', '"</span>', false )
							),
							'<span class="edit-link">',
							'</span>'
						);
					?>

				</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->

			<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
				<?php
					$thumbnail_args = array(
						'class' => 'single-featured',
					);
					the_post_thumbnail( $image_size, $thumbnail_args );
				?>
			</a>

			<?php if ( is_search() ) : // Only display Excerpts for Search ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
				<p><a class="btn btn-default read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'activello' ); ?></a></p>
			</div><!-- .entry-summary -->
			<?php else : ?>
			<div class="entry-content">

				<?php
				if ( get_theme_mod( 'activello_excerpts', 1 ) && '' != get_the_excerpt() ) :
					the_excerpt();
				else :
					the_content();
				endif;
			?>

				<?php
				wp_link_pages( array(
					'before'            => '<div class="page-links">' . esc_html__( 'Pages:', 'activello' ),
					'after'             => '</div>',
					'link_before'       => '<span>',
					'link_after'        => '</span>',
					'pagelink'          => '%',
					'echo'              => 1,
				) );
				?>

				<?php if ( ! is_single() && get_theme_mod( 'activello_excerpts', 1 ) ) : ?>
				<div class="read-more">
					<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php esc_html_e( 'Read More', 'activello' ); ?></a>
				</div>
				<?php endif; ?>

				<?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
					<div class="entry-footer">
						<span class="comments-link"><?php comments_popup_link( esc_html__( 'No comments yet', 'activello' ), esc_html__( '1 Comment', 'activello' ), esc_html__( '% Comments', 'activello' ) ); ?></span>
					</div><!-- .entry-footer -->
				<?php endif; ?>
			</div><!-- .entry-content -->
			<?php endif; ?>
		</div>
	</div>
</article><!-- #post-## -->
