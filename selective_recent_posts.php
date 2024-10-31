<?php
/*
Plugin Name: Selective Posts
Plugin URI: http://www.barryko.com/
Description: Display posts with category exclusions; select number of posts, post status, and post order.
Author: Barry Ko
Version: 1.1
Author URI: http://www.barryko.com/
*/
 
 
class RecentPostsSelective extends WP_Widget
{
  function RecentPostsSelective()
  {
    $widget_ops = array('classname' => 'RecentPostsSelective', 'description' => 'The most recent posts on your site with category exclusions' );
    $this->WP_Widget('RecentPostsSelective', 'Selective Posts', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
    $num_post_show = $instance['num_post_show'];
    $sel_post_type = $instance['sel_post_type'];
    $sel_post_type_order = $instance['sel_post_type_order'];
    $cat_ex = $instance['cat_ex'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('num_post_show'); ?>">Number of posts to show: <input size="3" value="<?php echo attribute_escape($num_post_show); ?>" id="<?php echo $this->get_field_id('num_post_show'); ?>" name="<?php echo $this->get_field_name('num_post_show'); ?>" type="text" value="<?php echo attribute_escape($num_post_show); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('sel_post_type'); ?>">Post Status: (<a href="http://codex.wordpress.org/Post_Status_Transitions">WP Codex</a>) <input input size="6" id="<?php echo $this->get_field_id('sel_post_type'); ?>" name="<?php echo $this->get_field_name('sel_post_type'); ?>" type="text" value="<?php echo attribute_escape($sel_post_type); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('sel_post_type_order'); ?>">Post Order: (DESC or ASC) <input input size="4" id="<?php echo $this->get_field_id('sel_post_type_order'); ?>" name="<?php echo $this->get_field_name('sel_post_type_order'); ?>" type="text" value="<?php echo attribute_escape($sel_post_type_order); ?>" /></label></p>
  <p><label for="<?php echo $this->get_field_id('cat_ex'); ?>">Categories To Exclude: <input class="widefat" id="<?php echo $this->get_field_id('cat_ex'); ?>" name="<?php echo $this->get_field_name('cat_ex'); ?>" type="text" value="<?php echo attribute_escape($cat_ex); ?>" /></label></p>

<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['num_post_show'] = $new_instance['num_post_show'];
    $instance['sel_post_type'] = $new_instance['sel_post_type'];
    $instance['sel_post_type_order'] = $new_instance['sel_post_type_order'];
    $instance['cat_ex'] = $new_instance['cat_ex'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // BEGIN WIDGET CODE

$numpostshow=$instance['num_post_show'];
$poststatustype=$instance['sel_post_type'];
$sel_post_type_order=$instance['sel_post_type_order'];
$excludecats=$instance['cat_ex'];
query_posts("showposts=$numpostshow&cat=$excludecats&post_status=$poststatustype&order=$sel_post_type_order");

if (have_posts()) : 
	echo "<ul>";
	while (have_posts()) : the_post(); 
		if($sel_post_type_order!="") {$showurl='#';}
		else {$showurl=get_permalink();}
		echo "<li><a href='".$showurl."'>".get_the_title()."</a></li>";
 
	endwhile;
	echo "</ul>";
endif; 
wp_reset_query();

    // END WIDGET CODE

    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("RecentPostsSelective");') );?>