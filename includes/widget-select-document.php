<?php

// This code is adapted from 
// https://stackoverflow.com/questions/31066314/reload-page-on-change-of-dropdown-and-pass-that-value
// *****************************
// Fields gathered in Widget
// *****************************
//$school_start // Numeric month that school begins. Default is 09 for September
//$cat_name     // Name of Category selected
// *****************************

$permalink = get_permalink();
   
if (!isset($get_meeting_year) || $get_meeting_year == 'all')
    {
    // Get all documents with specified category name  
    $selectDoc = get_posts(array(
    'posts_per_page' => -1,
    'post_type' => 'post',
    'post_status' => 'publish',
    'orderby' => 'meta_value',
    'meta_key'       => 'meeting_date',         
    'meta_type'      => 'DATE',
    'order' => 'ASC', // get all posts
    ));
    }

elseif (isset($get_meeting_year)) 
    { 
    // Get the school year based on date selected
    $meeting_get_year = date($_GET["syr"]); // Get the meeting year
    $meeting_year = date('Y', $meeting_get_year); // Filter the school year
    $test_start = $meeting_get_year . '-09-01'; // Set the beginning of school year date
    $test_end = $meeting_get_year+1; // Get the ending year
    $test_end = $test_end . '-08-31'; // Set the end of the school year date

    $test_start_date = strtotime($test_start);
    $test_start_date = date('Y-m-d', $test_start_date);

    $test_end_date = strtotime($test_end);
    $test_end_date = date('Y-m-d', $test_end_date);

    // Only documents with specified category name and school year
    $selectDoc = get_posts(array(
    'posts_per_page' => -1, // get all posts
    'post_type' => 'post',
    'post_status' => 'publish',
    'category_name' => $cat_name,
        'meta_query' => array(
            array(
                'key'           => 'meeting_date',
                'compare'       => 'BETWEEN',
                'value'         => array( $test_start_date, $test_end_date),
                'type'          => 'DATE',
            )),

    'order' => 'ASC',
    'orderby' => 'meta_value',
    'meta_key'       => 'meeting_date',         
    'meta_type'      => 'DATETIME',
    ));
    }

    // If posts exist...
    if ( !$selectDoc ) 
        {
        echo '<br> no posts found between ' . $test_start_date . ' and ' .  $test_end_date;
        }
    
    else
        {
        // Begin form to select a school year
        if (!isset($_GET['syr']) || $_GET['syr'] == 'all')
            {
            ?>
            <table class="full"><tr><td>Select a document:</td><td align="right"><select id="myselect" name="location" onchange="window.location='?syr='+'all'+'&yrpos='+0+'&id='+this.value+'&pos='+this.selectedIndex;"><option value="">-Select-</option>';
            <?php
            }
        
        else
            {
            $meeting_year = date($_GET["syr"]);
            ?>
            <table class="full"><tr><td>Select a document:</td><td align="right"><select id="myselect" name="location" onchange="window.location='?syr='+'<?php echo $meeting_year; ?>'+'&yrpos='+<?php echo $get_yrpos; ?>+'&id='+this.value+'&pos='+this.selectedIndex;"><option value="">-Select-</option>';
            <?php
            }
            
        // for each entry retreived... 
        foreach($selectDoc as $post)
            {
            // Get post data and assign to a variable
            setup_postdata( $post );

            // Determine which school year the document should be listed under
            
            $meeting = get_field('meeting_date');      // Get the meeting date
            //$meeting2 = get_field('meeting_date2');      // Get the meeting date
            //$meeting_date = strtotime(get_field('meeting_date2'));
            //$meeting_year = date('Y', $meeting); // Get meeting year
            //$meeting_month = date('m', $meeting_date); // Get meeting month

            $meeting_type = get_field('meeting_type');  // Meeting type
            $docu_type = get_field('document_type');    // Document type
            $cur_doc = get_field('document_pdf');
            $cur_id = $post->ID;
            $cur_title = get_the_title($cur_id);

            // Display the school year in the dropdown menu
            //echo '<option value="' . $cur_id . '">' . $meeting_month . ' ' . $meeting_year . ' ' . $meeting_type . ' ' . $docu_type . '</option>';
            echo '<option value="' . $cur_id . '">' . $cur_title . '</option>';
            } // end foreach
        // Submit button
        echo '</select></td></tr></table>';

        if(isset($_GET['id']))
            {
            ?>
            <script>
                var myselect = document.getElementById("myselect");
                myselect.options.selectedIndex = <?php echo $_GET["pos"]; ?>
            </script>
            <?php
            }
        } // end if document post exists
    //wp_reset_query();	 // Restore global post data stomped by the_post()
    
//if (isset($_GET['id'])) {$meeting = $_GET['id'];}
//$meeting1 = get_field('meeting_date', $meeting);
//$meeting2 = get_field('meeting_date2', $meeting);
//echo '<br>Meeting Date: ' . $meeting1 . ' and ' . $meeting2 . '<br>';
?>