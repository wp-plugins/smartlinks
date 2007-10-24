<?php
/*
Plugin Name: <a href="http://www.adaptiveblue.com">SmartLinks</a>
Description: Lets you use SmartLinks on your blog!
Author: AdaptiveBlue
Version: 1.0
Author URI: http://adaptiveblue.com
*/

function smartlinks_init() {

    // add our (safe) javascript to your blog
    $serverIndex = rand(1, 10);
    echo '<script type="text/javascript" defer="true" src="http://s' . $serverIndex . '.smrtlnks.com/js/bluelink-window.js?auto=yes"></script>';

}

function add_sidebar_graphic() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our (safe) javascript
	function widget_smartlinks($args) {
		extract($args);

		$serverIndex = rand(1, 10);
		echo $before_widget . $before_title . $title . $after_title;
		echo '<div id="smartlinks_graphic"><a href="http://www.adaptiveblue.com"><img src="http://s' . $serverIndex . '.smrtlnks.com/images/widget/SmartLinksEnhanced.gif" border="0"></a></div>';
		echo $after_widget;
	}

	// no options yet
	function widget_smartlinks_control() {
	}

	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('SmartLinks Graphic', 'widgets'), 'widget_smartlinks');
}

add_action('wp_head', 'smartlinks_init');

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'add_sidebar_graphic');

?>