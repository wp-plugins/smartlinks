<?php
/*
Plugin Name: <a href="http://www.adaptiveblue.com">SmartLinks</a>
Description: Lets you use SmartLinks on your blog!
Author: AdaptiveBlue
Version: 1.0
Author URI: http://adaptiveblue.com
*/

function get_smartlinks_params() {
    return array("Amazon" => "blueAmazonId", "Barnes & Noble" => "blueBNId",
                    "Booksense" => "blueBooksenseId", "eBay" => "blueEbayId", "Google" => "blueGoogleId",
                    "Linkshare" =>  "blueLinkshareId", "Powells" =>  "bluePowellsId");
}

function smartlinks_init() {

    $params = get_smartlinks_params();

    $paramString = '';

    foreach($params as $label => $paramName) {
        $value = get_option($paramName);
        if(!empty($value)) {
            $paramString .= "&" . $paramName . '=' . $value;
        }
    }

    // add our (safe) javascript to your blog
    $serverIndex = rand(1, 10);
    echo '<script type="text/javascript" defer="defer" src="http://s' . $serverIndex . '.smrtlnks.com/js/bluelink-window.js?auto=yes' . $paramString . '"></script>';

}

function add_sidebar_graphic() {

	if ( !function_exists('register_sidebar_widget') )
		return;

	// This is the function that outputs our (safe) javascript
	function widget_smartlinks($args) {
		extract($args);

		$serverIndex = rand(1, 10);
		echo $before_widget . $before_title . $title . $after_title;
		echo '<div id="smartlinks_graphic"><a href="http://www.adaptiveblue.com"><img src="http://s' . $serverIndex . '.smrtlnks.com/images/widget/SmartLinksEnhanced.gif" alt="Enhanced By SmartLinks" border="0"/></a></div>';
		echo $after_widget;
	}

	// no options yet
	function widget_smartlinks_control() {
	}

	// This registers our widget so it appears with the other available
	// widgets and can be dragged and dropped into any active sidebars.
	register_sidebar_widget(array('SmartLinks Graphic', 'widgets'), 'widget_smartlinks');
}

function add_smartlinks_options() {
    // Add a new submenu under Options:
    add_options_page('SmartLinks Options', 'SmartLinks Options', 8, 'smartlinksoptions', 'smartlinks_options_page');
}

function smartlinks_options_page() { // variables for the field and option names
    $smartlinks_option_names = get_smartlinks_params();

    $hidden_field_name = "smartlinks_options_submit";

    $updated = false;


    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( $_POST[ $hidden_field_name ] == 'Y' ) {
        $updated = true;
        foreach($smartlinks_option_names as $label => $opt_name) {
            // Read in existing option value from database
            $opt_val = get_option( $opt_name );

            // Read their posted value
            $opt_val = $_POST[ $opt_name ];

            // Save the posted value in the database
            update_option( $opt_name, $opt_val );
        }
    }

    // Put an options updated message on the screen
    if($updated) {
        ?>
        <div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
        <?php
    }
    
    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'SmartLinks Affiliate Options') . "</h2>";

    // options form

    ?>

    <form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
    <?php wp_nonce_field('update-options'); ?>
    <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y"/>
    <?php
        foreach($smartlinks_option_names as $label => $opt_name) {
    ?>
            <div style="margin-bottom:5px;"><?php _e($label); ?>:</div>
            <div style="margin-bottom:10px;"><input type="text" name="<?=$opt_name?>" value="<?=get_option($opt_name)?>" size="20"></div>
    <?php
        }
    ?>
    <p class="submit">
        <input type="submit" name="Submit" value="<?php _e('Update Options &raquo;') ?>" />
    </p>

    </form>
</div>

    <?php

}

// Hook for adding admin menus
add_action('admin_menu', 'add_smartlinks_options');

add_action('wp_head', 'smartlinks_init');

// Run our code later in case this loads prior to any required plugins.
add_action('widgets_init', 'add_sidebar_graphic');

?>