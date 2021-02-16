<?php

/**
 * Display meeting documents
 *
 * @package Divi Child - PACroom
 * @since Divi Child - PACroom 1.0
 *
 */

// https://developer.wordpress.org/themes/advanced-topics/child-themes/
// https://stackoverflow.com/questions/62911281/mysterious-divi-child-theme-css-being-ignored
function pacroom_enqueue_styles() 
    { 
    wp_register_style( 
    'child-style', 
    get_stylesheet_directory_uri() . '/style.css', 
    array(), 
    filemtime( get_stylesheet_directory() . '/style.css' ) 
    );

    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style') );
    }
add_action( 'wp_enqueue_scripts', 'pacroom_enqueue_styles' );

// Unregister some of the sidebars
unregister_sidebar( 'sidebar-5' );
// doesn't seem to be working


// Customize sidebar locations into theme.
function pacroom_widgets_init() {
    
    register_sidebar( array(
        'name'          => __( 'Messages from PAC', 'pacroom' ),
        'id'            => 'sidebar-messages',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
 
    register_sidebar( array(
        'name'          => __( 'Recent Activity', 'theme_name' ),
        'id'            => 'sidebar-recent',
        'before_widget' => '<ul class=><li id="%1$s" class="widget %2$s">',
        'after_widget'  => '</li></ul>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ) );
}
add_action( 'widgets_init', 'pacroom_widgets_init' );

// remove Projects post type from Divi Theme. The code below throws an error
//if( !function_exists( 'pacroom_unregister_post_type' ) ) {
//    function divi_child_pacroom_unregister_post_type(){
//        unregister_post_type( 'project' );
//    }
//}
//add_action('init','pacroom_unregister_post_type');


// Automatically get school year
// https://www.advancedcustomfields.com/resources/acf-save_post/
//add_action('acf/save_post', 'my_acf_save_post', 5);
/*add_filter('acf/update_value/name=hero_text', 'my_acf_update_value', 10, 4);
function my_acf_save_post($post_id)
    {
    // Get newly saved values
    $values = get_fields( $post_id );
    // Get submitted values.
    $values = $_POST['acf'];
    // check if post type is a meeting document
    $cat_type = get_cat_name($post_id); // Get Category Name
    if ($cat_type == 'Meeting Document') 
        {
        // Check the new value of meeting date
        if( isset($_POST['acf']['field_5a8471b2c7139']) )
            {    
            // Determine which school year the date falls in
            $meeting = $_POST['acf']['field_5a8471b2c7139']; // Get meeting date
            $meeting_date = new DateTime($meeting); // Set meeting date as date field
            $meeting_year = $meeting_date->format('Y'); // Set meeting year
            $meeting_month = $meeting_date->format('m'); // Set meeting month    
            if ($meeting_month < '09') { $meeting_year = $meeting_year -1; } // Update year if needed
            $meeting_year2 = $meeting_year +1; // Get end of school year

            $school_year = $meeting_year . '/' . $meeting_year2; // Set value of school year

            // update the age field
            update_acf('field_6026163b649a6', $school_year, $post_id);
            }
        }
    }*/

function my_acf_update_value( $value, $post_id, $field, $original ) {
    if( is_string($value) ) {
        // Determine which school year the date falls in
            $meeting = $_POST['acf']['field_5a8471b2c7139']; // Get meeting date
            $meeting_date = new DateTime($meeting); // Set meeting date as date field
            $meeting_year = $meeting_date->format('Y'); // Set meeting year
            $meeting_month = $meeting_date->format('m'); // Set meeting month    
            if ($meeting_month < '09') { $meeting_year = $meeting_year -1; } // Update year if needed
            $meeting_year2 = $meeting_year +1; // Get end of school year

            $school_year = $meeting_year . '/' . $meeting_year2; // Set value of school year
        $value = str_replace( '', $school_year,  $value );
    }
    return $value;
}

// Apply to fields named "hero_text".
add_filter('acf/update_value/name=school_year', 'my_acf_update_value', 10, 4);



/*****************************************************
 * Custom Widgets
   * Related Posts - Not working
   * Embed PDF Post - Non ACF widget - Functioning
   * PACroom Posts - ACF Widget - not working
 ****************************************************/
// Embed PDF Posts Widget
// If the custom widget class does not exist, load it
if(!class_exists('pdfembed_widget')) 
    {
    /**
     * Custom Widgets Without Using ACF
     * https://developer.wordpress.org/themes/functionality/widgets/#developing-widgets
     * @see classes/class-widget-embed-posts.php
     */
    add_action( 'widgets_init', 'pdfembed_load_widget' );
    require get_stylesheet_directory() . '/classes/class-widget-embed-posts.php';
    // Register and load the widget
    function pdfembed_load_widget() { register_widget( 'pdfembed_widget' ); }
    }
