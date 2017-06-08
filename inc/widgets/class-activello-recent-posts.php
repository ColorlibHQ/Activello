<?php

class Activello_Recent_Posts extends WP_Widget {

	function __construct() {

		$widget_ops = array(
			'classname' => 'activello-recent-posts',
			'description' => esc_html__( 'Activello recent posts widget with thumbnails', 'activello' ),
		);
		  parent::__construct( 'activello_recent_posts', esc_html__( 'Activello Recent Posts Widget','activello' ), $widget_ops );
	}

	function widget( $args, $instance ) {
		$title = isset( $instance['title'] ) ? esc_html( $instance['title'] ) : esc_html__( 'Recent Posts', 'activello' );
		$limit = isset( $instance['limit'] ) ? esc_html( $instance['limit'] ) : 5;

		echo $args['before_widget'];
		echo $args['before_title'];
		echo $title;
		echo $args['after_title'];

		/**
		 * Widget Content
		 */
	?>

	<!-- recent posts -->
		  <div class="recent-posts-wrapper">

				<?php

				  $featured_args = array(
					  'posts_per_page' => $limit,
					  'ignore_sticky_posts' => 1,
				  );

				  $featured_query = new WP_Query( $featured_args );

				  /**
				   * Check if zilla likes plugin exists
				   */
				if ( $featured_query->have_posts() ) : while ( $featured_query->have_posts() ) : $featured_query->the_post();

					?>

					<?php if ( get_the_content() != '' ) : ?>

						<!-- post -->
						<div class="post">

						  <!-- image -->
						  <div class="post-image <?php echo get_post_format(); ?>">

								<a href="<?php echo get_permalink(); ?>"><?php
								if ( get_post_format() != 'quote' ) {
									echo get_the_post_thumbnail( get_the_ID() , 'thumbnail' );
								}
									?></a>

						  </div> <!-- end post image -->

						  <!-- content -->
						  <div class="post-content">

							  <a href="<?php echo get_permalink(); ?>"><?php echo get_the_title(); ?></a>
							  <span class="date">- <?php echo get_the_date( 'd M , Y' ); ?></span>

						  </div><!-- end content -->
						</div><!-- end post -->

						<?php endif; ?>

					<?php

				  endwhile;
endif;
				wp_reset_query();

					?>

		  </div> <!-- end posts wrapper -->

		<?php

		echo $args['after_widget'];
	}

	function form( $instance ) {

		if ( ! isset( $instance['title'] ) ) {
			$instance['title'] = esc_html__( 'Recent Posts', 'activello' );
		}
		if ( ! isset( $instance['limit'] ) ) {
			$instance['limit'] = 5;
		}

		?>

	  <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php esc_html_e( 'Title', 'activello' ) ?></label>

	  <input  type="text" value="<?php echo esc_html( $instance['title'] ); ?>"
			  name="<?php echo $this->get_field_name( 'title' ); ?>"
			  id="<?php $this->get_field_id( 'title' ); ?>"
			  class="widefat" />
	  </p>

	  <p><label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php esc_html_e( 'Limit Posts Number', 'activello' ) ?></label>

	  <input  type="number" value="<?php echo esc_html( $instance['limit'] ); ?>"
			  name="<?php echo $this->get_field_name( 'limit' ); ?>"
			  id="<?php $this->get_field_id( 'limit' ); ?>"
			  class="widefat" />
	  <p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? esc_html( $new_instance['title'] ) : '';
		$instance['limit'] = ( ! empty( $new_instance['limit'] ) && is_numeric( $new_instance['limit'] )  ) ? esc_html( $new_instance['limit'] ) : '';

		return $instance;
	}
}
?>
