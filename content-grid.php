<?php
/**
 * @package activello
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'grid' ); ?>>
	<div class="blog-item-wrap">
		<div class="post-inner-content">
			<header class="entry-header page-header">
				<?php echo get_the_category_list(); ?>
				<h1 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>

				<?php if ( 'post' == get_post_type() ) : ?>
				<div class="entry-meta">
					<?php edit_post_link( esc_html__( 'Edit', 'activello' ), '<span class="edit-link">', '</span>' ); ?>
				</div><!-- .entry-meta -->
				<?php endif; ?>
			</header><!-- .entry-header -->
						
                        <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                                <?php the_post_thumbnail( 'activello-thumbnail', array( 'class' => 'single-featured' )); ?>
                        </a>
                        
			<?php if ( is_search() ) : // Only display Excerpts for Search ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
				<p><a class="btn btn-default read-more" href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read More', 'activello' ); ?></a></p>
			</div><!-- .entry-summary -->
			<?php else : ?>
			<div class="entry-content">

				<?php the_excerpt(); ?>
				
				<?php
				wp_link_pages( array(
					'before'            => '<div class="page-links">'.esc_html__( 'Pages:', 'activello' ),
					'after'             => '</div>',
					'link_before'       => '<span>',
					'link_after'        => '</span>',
					'pagelink'          => '%',
					'echo'              => 1
						) );
				?>
			</div><!-- .entry-content -->
			<?php endif; ?>
		</div>
	</div>
</article><!-- #post-## -->
