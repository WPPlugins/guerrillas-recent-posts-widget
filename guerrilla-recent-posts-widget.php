<?php
/*
Plugin Name: Guerrilla's Recent Posts Widget
Plugin URI: http://madebyguerrilla.com
Description: This is a plugin that adds a widget you can use to showcase your most recent posts in the sidebar of your WordPress powered website.
Version: 1.1
Author: Mike Smith
Author URI: http://www.madebyguerrilla.com
*/

/*  Copyright 2012-2014  Mike Smith (email : hi@madebyguerrilla.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// This code adds the default stickybar stylesheet to your website
function guerrilla_recentposts_style() {
	// Register the style like this for a plugin:
	wp_register_style( 'guerrillas-recent-posts-widget', plugins_url( '/style.css', __FILE__ ), array(), '20140529', 'all' );
	// For either a plugin or a theme, you can then enqueue the style:
	wp_enqueue_style( 'guerrillas-recent-posts-widget' );
}

add_action( 'wp_enqueue_scripts', 'guerrilla_recentposts_style' );


class PostWidget extends WP_Widget
{
    function PostWidget(){
		$widget_ops = array('description' => 'Displays Your Post Updates');
		$control_ops = array('width' => 300, 'height' => 300);
		parent::WP_Widget(false,$name='Post Updates Widget',$widget_ops,$control_ops);
    }

  /* Displays the Widget in the front-end */
    function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? 'Post Updates' : $instance['title']);
		$PostCount = empty($instance['PostCount']) ? '' : $instance['PostCount'];

		echo $before_widget;

		if ( $title )
		echo $before_title . $title . $after_title;
?>
		<?php $my_query = new WP_Query("showposts=$PostCount"); while ($my_query->have_posts()) : $my_query->the_post(); $do_not_duplicate = $post->ID; ?>
		<div class="PostWrap">
			<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( array(75,75) ); ?></a>
			<p class="postwidgettitle"><strong><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></strong></p>
			<p class="postwidgetinfo"><?php the_time('m/d/Y'); ?> | <?php comments_popup_link('0 Comments','1 Comment','% Comments'); ?></p>
		</div><!-- END PostWrap -->
		<?php endwhile; ?>
		<div style="clear:both;"></div>

<?php
		echo $after_widget;
	}

  /*Saves the settings. */
    function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = stripslashes($new_instance['title']);
		$instance['PostCount'] = stripslashes($new_instance['PostCount']);

		return $instance;
	}

  /*Creates the form for the widget in the back-end. */
    function form($instance){
		//Defaults
		$instance = wp_parse_args( (array) $instance, array('title'=>'Post Updates', 'PostCount'=>'', 'PostID'=>'') );

		$title = htmlspecialchars($instance['title']);
		$PostCount = htmlspecialchars($instance['PostCount']);
		$PostID = htmlspecialchars($instance['PostID']);

		# Title
		echo '<p><label for="' . $this->get_field_id('title') . '">' . 'Title:' . '</label><input class="widefat" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></p>';
		# Post Update Count
		echo '<p><label for="' . $this->get_field_id('PostCount') . '">' . 'Update Count (ex: 3):' . '</label><input class="widefat" id="' . $this->get_field_id('PostCount') . '" name="' . $this->get_field_name('PostCount') . '" type="text" value="' . $PostCount . '" /></p>';	
	}

}// end PostWidget class

function PostWidgetInit() {
  register_widget('PostWidget');
}

add_action('widgets_init', 'PostWidgetInit');

?>