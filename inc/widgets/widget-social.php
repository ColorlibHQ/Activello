<?php

/**
 * Social  Widget
 * activello Theme
 */
class activello_social_widget extends WP_Widget
{
	 function activello_social_widget(){

        $widget_ops = array('classname' => 'activello-social','description' => esc_html__( "Activello theme widget to display social media icons" ,'activello') );
		    parent::__construct('activello-social', esc_html__('Activello Social Widget','activello'), $widget_ops);
    }

    function widget($args , $instance) {
    	extract($args);
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Follow us' , 'activello');

      echo $before_widget;
      echo $before_title;
      echo $title;
      echo $after_title;

    /**
     * Widget Content
     */
    ?>

    <!-- social icons -->
    <div class="social-icons sticky-sidebar-social">


    <?php activello_social_icons(); ?>


    </div><!-- end social icons -->


		<?php

		echo $after_widget;
    }


    function form($instance) {
      if(!isset($instance['title'])) $instance['title'] = esc_html__('Follow us' , 'activello');
    ?>

      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title ','activello') ?></label>

      <input type="text" value="<?php echo esc_attr($instance['title']); ?>"
                          name="<?php echo $this->get_field_name('title'); ?>"
                          id="<?php $this->get_field_id('title'); ?>"
                          class="widefat" />
      </p>

    	<?php
    }

}

?>
