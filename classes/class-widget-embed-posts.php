<?php

// Create widget class by extending the standard WP_Widget class and some of its functions.
class pdfembed_widget extends WP_Widget 
    {
    // Actual widget processes
    public function __construct() 
        {
        // Set up your widget with a description, name, and display width in your admin.
        parent::__construct(
            'pdfembed_widget', // Base ID
            'Embed PDF Post', // Name
            array( 'description' => __( 'Embeds a pdf into a post page', 'text_domain' ), ) // Args
            );
        } // end of public function __construct
  
    // Output the content of the widget in the front-end
    public function widget( $args, $instance ) 
        {
        $title = apply_filters( 'widget_title', $instance['title'] );
        
        // This is where you run the code and display the output
        //echo 'Using template_part to get widget front end code.<br>';
        // set the variable to use in template part
        //set_query_var('year_start', $school_start);

        get_template_part( 'template_parts/widget-select-school-year', get_post_format() );
        get_template_part( 'template_parts/widget-select-document', get_post_format() );
        //include('template_parts/widget-select-school-year.php');
        //include('template_parts/widget-select-document.php');

        if (!isset($cur_doc)) 
            {
            //Query posts for latest post with category 'Meeting Document' 
            $document_posts = get_posts(array(
                'post_type' => 'post',
                'post_status' => 'publish',
                'category_name' => $cat_name,
                'orderby' => 'meeting_date',
                'order' => 'DESC',
                'posts_per_page' => 1
                ));   

            if ( $document_posts ) // If post is found...
                {
                foreach($document_posts as $post) // For each post found...
                    {
                    setup_postdata( $post ); // Get the field data					
                    $cur_title = get_the_title();
                    $cur_doc = get_field('document_pdf'); // Get Custom field data
                    }
                }
            }
        if (isset($_GET["id"]))
            {
            $pid = $_GET["id"];
            $cur_title = get_the_title($pid);
            $cur_doc = get_field('document_pdf', $pid); 
            }

        echo $cur_title;
        // Display embedded pdf document on page
        echo '<embed src="' . $cur_doc . '" type="application/pdf" width="100%" height="auto"></embed>';
        
        
        
        
        //get_template_part( 'template-parts/widget-embed-pdf', get_post_format() );

        //echo 'Using include to get widget front end code.<br>';
        // include php file
        //include('/home/pacroom2demo/public_html/wp-content/themes/divi-child-pspac/inc/widget-embed-pdf.php');
        } // end of public function widget
          
    // Output the options form in the admin area 
    public function form( $instance ) 
        {
        if ( isset( $instance[ 'title' ] ) ) { $title = $instance[ 'title' ]; }
        else { $title = __( 'New title', 'text_domain' ); }
        
        // Widget admin form
        echo '<p><label for="' . $this->get_field_id( 'title' ) . '">' . _e( 'Title:' ) . '</label><input class="widefat" id="' . $this->get_field_id( 'title' ) . '" name="' . $this->get_field_name( 'title' ) . '"type="text" value="' . esc_attr( $title ) . '"></p>';
        
        } // end of public function form

    // Process widget options to be saved
    public function update( $new_instance, $old_instance ) 
        {
        $instance = array();
        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        return $instance;
        }
    } // end of class widget

?>