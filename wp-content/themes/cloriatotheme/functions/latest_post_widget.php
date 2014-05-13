<?php
function widget_myRecentPosts_init() {
	if ( !function_exists('register_sidebar_widget') )
		return;
		function widget_myRecentPosts($args) {
		
			// "$args is an array of strings that help widgets to conform to
			// the active theme: before_widget, before_title, after_widget,
			// and after_title are the array keys." - These are set up by the theme
			extract($args);
			// These are our own options
			$options = get_option('widget_myRecentPosts');
			$title = $options['title'];  // Title in sidebar for widget
			$show = $options['show'];  // # of Posts we are showing
			$excerpt = $options['excerpt'];  // Showing the excerpt or not
			$exclude = $options['exclude'];  // Categories to exclude
            if ($show<1) $show = 1;
			if ($exclude=="") $exclude = "0"; 
		
		// Output
		echo $before_widget . $before_title . $title . $after_title;
                                // GET POSTS
                global $wpdb;
                                $posts = $wpdb->get_results;
                                       $sql= ("SELECT * FROM $wpdb->posts WHERE post_status = 'publish' AND
                post_type = 'post' AND ID IN (SELECT DISTINCT post_parent FROM $wpdb->posts WHERE post_parent > 0
                AND
                post_type = 'attachment'
                AND
                post_mime_type IN ('image/jpeg', 'image/png')
                )
                ORDER BY post_date DESC LIMIT $show
                ");
			$posts = $wpdb->get_results($sql);
			
			// start list
			echo '<ul class="recent_post">';
				// were there any posts found?
				if (!empty($posts)) {
					// posts were found, loop through them
					 foreach ($posts as $post) {
					 
							// format a date for the posts
							$post->post_date = date('M j, Y',strtotime($post->post_date));
							
							// if we want to display an excerpt, get it/generate it if no excerpt found
							if ($excerpt) {
								 if (empty($post->post_excerpt)) {
									 $post->post_excerpt = explode(" ",strrev(substr(strip_tags($post->post_content), 0, 100)),2);
									 $post->post_excerpt = strrev($post->post_excerpt[1]);
									 $post->post_excerpt.= " [...]";
								 }
							}
                                                            $homeLink = get_bloginfo('template_directory');
                                                            $first_img = '';
                                                            ob_start();
                                                            ob_end_clean();
                                                            $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
                                                            $first_img = $matches [1] [0];
                                                            if (empty($first_img)) { //Defines a default image
                                                                //$first_img = "/images/default.jpg";
                                                            }            
							//output to screen
							echo '<li>';
                                                        echo '<a class="post" rel="bookmark" href="'.get_permalink($post->ID).'"><img src="'.$first_img.'"/>';
                                                        $tit=$post->post_title;
							echo '<span class="inner">';
                                                                 echo substr($tit, 0, 10); if (strlen($tit) > 10) echo " ...";                                                                							
							echo '</span></a><br/>';
                                                        echo '<span class="recent-date">'.$post->post_date.'</span>
                                                        </li>';
					 }
				} else echo "<li>No recent Posts</li>";
		// end list
		echo '</ul>';
		
		// echo widget closing tag
		echo $after_widget;
	} 
	// Settings form
	function widget_myRecentPosts_control() {
		// Get options
		$options = get_option('widget_myRecentPosts');
		// options exist? if not set defaults
		if ( !is_array($options) )
			$options = array('title'=>'Recent Posts', 'show'=>'5', 'excerpt'=>'1','exclude'=>'');
		
		// form posted?
		if ( $_POST['myRecentPosts-submit'] ) {
			// Remember to sanitize and format use input appropriately.
			$options['title'] = strip_tags(stripslashes($_POST['myRecentPosts-title']));
			$options['show'] = strip_tags(stripslashes($_POST['myRecentPosts-show']));
			$options['excerpt'] = strip_tags(stripslashes($_POST['myRecentPosts-excerpt']));
			$options['exclude'] = strip_tags(stripslashes($_POST['myRecentPosts-exclude']));
			update_option('widget_myRecentPosts', $options);
		}
		// Get options for form fields to show
		$title = htmlspecialchars($options['title'], ENT_QUOTES);
		$show = htmlspecialchars($options['show'], ENT_QUOTES);
		$excerpt = htmlspecialchars($options['excerpt'], ENT_QUOTES);
		$exclude = htmlspecialchars($options['exclude'], ENT_QUOTES);
		
		// The form fields
		echo '<p style="text-align:right;">
				<label for="myRecentPosts-title">' . __('Title:','cloriato') . ' 
				<input style="width: 150px;" id="myRecentPosts-title" name="myRecentPosts-title" type="text" value="'.$title.'" />
				</label></p>';
		echo '<p style="text-align:right;">
				<label for="myRecentPosts-show">' . __('Show:','cloriato') . ' 
				<input style="width: 150px;" id="myRecentPosts-show" name="myRecentPosts-show" type="text" value="'.$show.'" />
				</label></p>';			
		echo '<input type="hidden" id="myRecentPosts-submit" name="myRecentPosts-submit" value="1" />';
	}
	
	// Register widget for use
	register_sidebar_widget(array('Recent Posts With Thumbnail', 'widgets'), 'widget_myRecentPosts');
	// Register settings for use, 300x100 pixel form
	register_widget_control(array('Recent Posts With Thumbnail', 'widgets'), 'widget_myRecentPosts_control', 260, 200);
}
// Run code and init
add_action('widgets_init', 'widget_myRecentPosts_init');
?>
