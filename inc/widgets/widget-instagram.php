<?php

/**
 * instagram  Widget
 * activello Theme
 */
class activello_instagram_widget extends WP_Widget
{
    function activello_instagram_widget(){

        $widget_ops = array('classname' => 'activello-instagram','description' => esc_html__( "activello instagram Widget" ,'activello') );
        parent::__construct('activello-instagram', esc_html__('activello instagram Widget','activello'), $widget_ops);
    }

    function widget($args , $instance) {
    	extract($args);
        $title = isset($instance['title']) ? $instance['title'] : esc_html__('Follow us' , 'activello');
        $instagram_id = isset($instance['instagram_id']) ? $instance['instagram_id'] : esc_html__('' , 'activello');
        $tag_name = isset($instance['tag_name']) ? $instance['tag_name'] : esc_html__('awesome' , 'activello');
        $limit = isset($instance['limit']) ? $instance['limit'] : 6;
     
        echo $before_widget;
        echo $before_title;
        echo $title;
        echo $after_title;

		/**
		 * Widget Content
		 */
    ?>

		<script type="text/javascript">
				var feed = new Instafeed({
						get: 'tagged',
						tagName: '<?php echo $tag_name; ?>',
						clientId: '<?php echo $instagram_id; ?>',
						limit: '<?php echo $limit; ?>',
                                            });
				feed.run();
		</script>		
		<div id="instafeed"></div>


		<?php

		echo $after_widget;
    }


    function form($instance) {
        if(!isset($instance['title']) ) $instance['title']='';
        if(!isset($instance['instagram_id'])) $instance['instagram_id']='';
        if(!isset($instance['tag_name'])) $instance['tag_name']='';
        if(!isset($instance['limit'])) $instance['limit']='';
    ?>

      <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title ','activello') ?></label>

      <input type="text" value="<?php echo esc_attr($instance['title']); ?>"
                          name="<?php echo $this->get_field_name('title'); ?>"
                          id="<?php $this->get_field_id('title'); ?>"
                          class="widefat" />
      </p>

      <p><label for="<?php echo $this->get_field_id('instagram_id'); ?>"><?php esc_html_e('Client ID ','activello') ?></label>

      <input type="text" value="<?php echo esc_attr($instance['instagram_id']); ?>"
                          name="<?php echo $this->get_field_name('instagram_id'); ?>"
                          id="<?php $this->get_field_id('instagram_id'); ?>"
                          class="widefat" />
      </p>		
			
      <p><label for="<?php echo $this->get_field_id('tag_name'); ?>"><?php esc_html_e('Tag Name ','activello') ?></label>

      <input type="text" value="<?php echo esc_attr($instance['tag_name']); ?>"
                          name="<?php echo $this->get_field_name('tag_name'); ?>"
                          id="<?php $this->get_field_id('tag_name'); ?>"
                          class="widefat" />
      </p>				

      <p><label for="<?php echo $this->get_field_id('limit'); ?>"><?php esc_html_e('Limit ','activello') ?></label>

      <input type="text" value="<?php echo esc_attr($instance['limit']); ?>"
                          name="<?php echo $this->get_field_name('limit'); ?>"
                          id="<?php $this->get_field_id('limit'); ?>"
                          class="" />
      </p>		
			
			<?php
    }

}

?>