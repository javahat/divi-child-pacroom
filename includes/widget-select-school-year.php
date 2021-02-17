<?php
// *****************************
// Fields gathered in Widget
// *****************************
//$school_start // Numeric month that school begins. Default is 09 for September
//$cat_name     // Name of Category selected
// *****************************

// Get variable from widget
//$school_start = get_query_var('year_start');
//$school_start = get_query_var('year_start');

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
//wp_reset_query();	 // Restore global post data stomped by the_post()
?>