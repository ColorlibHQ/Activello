<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package activello
 */
?>

<?php
	$thumbnail_args = array(
		'class' => 'single-featured',
	);
	the_post_thumbnail( 'activello-featured', $thumbnail_args );
?>

<div class="post-inner-content">
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header page-header">
		<h1 class="entry-title"><?php the_title(); ?></h1>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php the_content(); ?>
		<?php
		wp_link_pages( array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'activello' ),
			'after'  => '</div>',
		) );
		?>
	</div><!-- .entry-content -->
	<?php edit_post_link( esc_html__( 'Edit', 'activello' ), '<footer class="entry-footer"><i class="fa fa-pencil-square-o"></i><span class="edit-link">', '</span></footer>' ); ?>
</article><!-- #post-## -->
</div>
