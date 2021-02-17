<?php
/* *****************************
// Description of code snippet:
// 1. A dynamic select field populated with school years from posts in category.
// 2. A dynamic select field populated with documents from the selected year.
// The code is adapted from:
// https://stackoverflow.com/questions/31066314/reload-page-on-change-of-dropdown-and-pass-that-value

// *****************************
// Fields gathered in Widget:
// $school_start // Numeric month that school begins. Default is 09 for September
// $cat_name     // Name of Category selected
// *****************************/ 


/* *****************************
// 1. Create first select field with school years
// *****************************/

// Check if School Start Month has been selected in teh widget
if (!isset($school_start) || $school_start == '') 
    { 
    $school_start = '00';
    echo '<span class="important"><i>You must select the first month of your year in your widget.</i></span>';
    }

if (!isset($cat_name) || $cat_name == '') 
    { 
    $cat_name = 'Meeting Document';
    echo '<br><span class="important"><i>You must select a category in your widget.</i></span>';
    }
   
// Get school years from existing document posts  
$queryYear = get_posts(array(
    'posts_per_page' => -1, // get all posts
    'post_type' => 'post',
    'post_status' => 'publish',
    'category_name' => $cat_name,
    'orderby' => 'meta_value',
    'meta_key'       => 'meeting_date',         
    'meta_type'      => 'DATE',
    'order' => 'ASC',
    ));

// If posts exist...
if ( ! $queryYear ) 
    {
    echo '<h2>Let\'s get started</h2><p class="important">There are no ' . $cat_name . 's to select.<br><br><em>Have the website admin upload a document as a post and assign it to the "' . $cat_name . '" category.</em></p>'; 
    }

else
    {
    // Begin form to select a school year
    ?>
    <table class="full"><tr><td>Select a school year:</td><td align="right"><select id="selectyr" name="schoolyr" onchange="window.location='?syr='+this.value+'&yrpos='+this.selectedIndex;"><option value="all">All Years</option>';
    <?php
    // Create an array to filter posts
    $get_years = array();
    // for each entry retreived... 
    foreach($queryYear as $post)
        {
        // Get post data and assign to a variable
        // setup_postdata( $post );

        // Determine which school year the document should be listed under
        $cur_id = $post->ID; // Get post id
        $meeting_date = strtotime(get_field('meeting_date', $cur_id)); // Convert meeting date
        $meeting_year = date('Y', $meeting_date); // Get meeting year
        $meeting_month = date('m', $meeting_date); // Get meeting month

        if ($meeting_month < $school_start) { $meeting_year = $meeting_year -1; } // Offset the year if needed
        $meeting_year2 = $meeting_year+1;           // Set end of school year variable
        
        if( in_array($meeting_year, $get_years) ) { continue; } //If in array, skip iteration

        // Display the school year in the dropdown menu
        echo '<option value="' . $meeting_year . '">' . $meeting_year . ' / ' . $meeting_year2 . '</option>';
    $get_years[] = $meeting_year;
        } // end foreach
    echo '</select></td></tr></table>';
    
    if(isset($_GET['syr']))
        {
        $get_meeting_year=$_GET['syr'];
        $get_yrpos = $_GET["yrpos"];
        
        if ($get_meeting_year == 'all' && !isset($get_meeting_year))
            {
            ?>
            <script>
                var selectyr = 'all';
                selectyr.options.selectedIndex = 'all';
            </script>
            <?php
            }
        else
            {
            ?>
            <script>
                var selectyr = document.getElementById("selectyr");
                selectyr.options.selectedIndex = <?php echo $get_yrpos; ?>
            </script>
            <?php
            }
        }
    
    } // end if document post exists

/* *****************************
// 2. Create second select field with school years
// *****************************/
   
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

// If no posts exist, display a message
if ( !$selectDoc ) 
    {
    echo '<br> no posts found between ' . $test_start_date . ' and ' .  $test_end_date;
    }

// if posts exist list documents
else
    {
    // If no school year is selected let's display all documents
    if (!isset($_GET['syr']) || $_GET['syr'] == 'all')
        {
        ?>
        <table class="full"><tr><td>Select a document:</td><td align="right"><select id="myselect" name="location" onchange="window.location='?syr='+'all'+'&yrpos='+0+'&id='+this.value+'&pos='+this.selectedIndex;"><option value="">-Select-</option>';
        <?php
        }

    // If a school year is selected we will filter our seach by year
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
        $cur_id = $post->ID;
        $meeting_date = strtotime(get_field('meeting_date', $cur_id));
        $meeting_year = date('Y', $meeting_date); // Get meeting year
        $meeting_month = date('F', $meeting_date); // Get meeting month

        $meeting_type = get_field('meeting_type', $cur_id);  // Meeting type
        $docu_type = get_field('document_type', $cur_id);    // Document type
        $cur_doc = get_field('document_pdf', $cur_id);
        $cur_title = get_the_title($cur_id);
        if ($cur_title == '') { $cur_title = 'This document has no title.'; }

        // Display each document by year month meeting type and document type  
        echo '<option value="' . $cur_id . '">' . $meeting_year . ' ' . $meeting_month . ' ' . $meeting_type . ' ' . $docu_type . '</option>';
        } // end foreach
    echo '</select></td></tr></table>'; // end the table with the dynamic select field

    // If a document is selected...
    if(isset($_GET['id']))
        {
        ?>
        <script>
            var myselect = document.getElementById("myselect");
            myselect.options.selectedIndex = <?php echo $_GET["pos"]; ?>
        </script>
        <?php
        } // end if document is selected
    } // end if document post exists

//wp_reset_query();	 // Restore global post data stomped by the_post()
?>