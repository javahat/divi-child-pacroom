<?php
// This field should be automatically populated with widget
$cat_name = 'Meeting Document';

include('widget-select-school-year.php');
include('widget-select-document.php');

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
?>