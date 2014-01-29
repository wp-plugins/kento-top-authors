<?php
/**
* Plugin Name: Kento Top Authors
* Plugin URI: http://kentothemes.com
* Description: Top Authors List by Post Numbers.
* Version: 1.0
* Author: KentoThemes
* Author URI: http://kentothemes.com
*License: GPLv2 or later
*License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
 
define('KENTO_TOP_AUTHOR_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );

function kento_top_author_scripts($hook) {
        /* Register our script. */
		wp_enqueue_style( 'KENTO_TOP_AUTHOR_STYLE', KENTO_TOP_AUTHOR_PLUGIN_PATH.'css/style.css' );		 
        wp_enqueue_script( 'jquery');

}
add_action('wp_enqueue_scripts', 'kento_top_author_scripts'); 


//////////////////kento_top_authors widget//////////////////////////////////
//add_action( 'widgets_init', 'kento_latest_tabs_plugin' );

wp_register_sidebar_widget(
    'kento_top_authors_widget',          // your unique widget id
    'Kento: Top Authors',                 // widget name
    'kento_top_authors_widget_display',  // callback function to display widget
    array(                      // options
        'description' => 'Top Authors List'
    )
);

wp_register_widget_control(
	'kento_top_authors_widget',		// id
	'kento_top_authors_widget',		// name
	'kento_top_authors_widget_control'	// callback function
);

function kento_top_authors_widget_control($args=array(), $params=array()) {
    	//the form is submitted, save into database
    	if (isset($_POST['submitted'])) {
    		update_option('kento_top_authors_widget_title', $_POST['widgettitle']);
			update_option('kento_top_authors_widget_number', $_POST['number']);	
			update_option('kta_style', $_POST['kta_style']);			 		
    	}
    	//load options
    	$widgettitle = get_option('kento_top_authors_widget_title');
		$number = get_option('kento_top_authors_widget_number');
		$kta_style = get_option('kta_style');
    	?>
		<br /><br />
    	<strong>Widget Title:</strong>
    	<input type="text" class="widefat" name="widgettitle" value="<?php echo stripslashes($widgettitle); ?>" />
		<br /><br />
        <strong>How Many Author to show:</strong><br />
    	<input type="text" class="widefat" name="number" value="<?php echo stripslashes($number); ?>" />
        <br /><br />
        <strong>Select Style:</strong><br /><br />
        <label><input type="radio" name="kta_style" value="style1" <?php if( $kta_style == 'style1') echo 'checked'; ?> />Style 1</label>
    	<br /><br />
         <label><input type="radio" name="kta_style" value="style2" <?php if( $kta_style == 'style2') echo 'checked'; ?> />Style 2</label>
    	<br /><br />
         <label><input type="radio" name="kta_style" value="style3" <?php if( $kta_style == 'style3') echo 'checked'; ?> />Style 3</label>
    	<br /><br />
    	<input type="hidden" name="submitted" value="1" />
    	<?php
    }




function kento_top_authors_widget_display($args=array(), $params=array()) {
    	//load options
    	$widgettitle = get_option('kento_top_authors_widget_title');
		$number = get_option('kento_top_authors_widget_number');
		$kta_style = get_option('kta_style');
    	//widget output
    	echo stripslashes($args['before_widget']);
		echo '<div class="top-authors">';
		if ($widgettitle != '') {
			echo stripslashes($args['before_title']);
			echo stripslashes($widgettitle);
			echo stripslashes($args['after_title']);
		}
    	else {
			echo "";
			}
			
			
			?>
            <ol class="top-authors-list <?php echo $kta_style; ?>">
			<?php foreach ( get_users('order=DESC&orderby=post_count&number='.$number.'') as $user ) : ?>
            
            <li>
                <a href="<?php echo bloginfo( url )."/author/".$user->user_login; ?>">
                <div class="top-authors-image"><?php echo get_avatar($user->ID, 50); ?></div> 
                <span class="top-authors-name" > <?php echo $user->display_name; ?></span>
                <span class="top-authors-post" > (<?php echo count_user_posts($user->ID); ?> Posts)</span></a>
            </li>
            
            <?php endforeach; ?>
            </ol><!--top-authors-list -->
            
<?php
    	echo '</div> <!--kento_top_authors_widget -->';//close div.socialwidget
      echo stripslashes($args['after_widget']);
    }

//////////////////kento_top_authors widget end//////////////////////////////////