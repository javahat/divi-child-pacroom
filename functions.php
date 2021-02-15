<?php

// theme name: divichild_pspac

function divichild_pspac_enqueue_styles() { 
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}
add_action( 'wp_enqueue_scripts', 'divichild_pspac_enqueue_styles' );

// Unregister some of the sidebars
unregister_sidebar( 'sidebar-5' );
// doesn't seem to be working


// Customize sidebar locations into theme.
function divichild_pspac_widgets_init() {
    
    register_sidebar( array(
        'name'          => __( 'Messages from PAC', 'divichild_pspac' ),
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
add_action( 'widgets_init', 'divichild_pspac_widgets_init' );

// remove Projects post type from Divi Theme
if( !function_exists( 'divichild_pspac_unregister_post_type' ) ) {
    function divi_child_pspac_unregister_post_type(){
        unregister_post_type( 'project' );
    }
}
add_action('init','divichild_pspac_unregister_post_type');