<?php
/**
 * @package activello
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="blog-item-wrap">
		<div class="post-inner-content">
			<header class="entry-header page-header">
				<h1 class="entry-title"><?php the_title(); ?></h1>

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
                    <?php the_post_thumbnail( 'activello-featured', array( 'class' => 'single-featured' )); ?>
            </a>
			
			<div class="entry-content">

				<?php the_content(); ?>
				
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
            <div class="entry-footer">
                <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
                <span class="comments-link"><?php comments_popup_link( esc_html__( 'Leave a comment', 'activello' ), esc_html__( 'Comment (1)', 'activello' ), esc_html__( 'Comments (%)', 'activello' ) ); ?></span>
                <?php endif; ?>	
                <?php if(has_tag()) : ?>
                <!-- tags -->
                <div class="tagcloud">

                    <?php
                        $tags = get_the_tags(get_the_ID());
                        foreach($tags as $tag){
                            echo '<a href="'.get_tag_link($tag->term_id).'">'.$tag->name.'</a> ';
                        } ?>

                </div>
                <!-- end tags -->
                <?php endif; ?>
            </div><!-- .entry-footer -->
		</div>
	</div>
</article><!-- #post-## -->
