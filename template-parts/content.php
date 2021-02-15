<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package JavaHat
 * @subpackage Simply_Strata
 * @since Simply Strata 1.0
 */
 
 global $post;
 $page_slug = $post->post_name;

global $wp_roles;
$current_user = wp_get_current_user();
$roles = $current_user->roles;
$current_role = implode(',',$roles);
?>

<!-- Page Title -->
<div class="entry-header">
	<?php  
	the_title( sprintf( '<h1 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h1>' );
	?>
</div><!-- .entry-header -->


<!-- Page Content -->
<div class="entry-content">
	<?php
	
// if front page, get home page script
if (is_front_page() ) 
	{
	get_template_part( 'template-parts/content-home', get_post_format() );
	}
    
// if Huntly Wynd Documents 
elseif ($page_slug == 'hwcommunity') 
	{
	get_template_part( 'template-parts/post-documents-hw', get_post_format() );
	}
    
// if Huntly Wynd Important Documents 
elseif ($page_slug == 'hwimportant') 
	{
	get_template_part( 'template-parts/post-documents-hw-important', get_post_format() );
	}

else
	{
	// UPDATED MAY 1, 2020
    //display documents at top of page if not Council Documents Page
    if ($page_slug != 'council-documents') 
      { 
      get_template_part( 'template-parts/post-page-documents', get_post_format() ); 
      }
    
	// if the user is logged in
	$securecontent = get_post_meta( get_the_ID(), 'simplystrata_securecontent', true );
	$securecontent2 = get_field('secure_content'); 
	if ( is_user_logged_in() && current_user_can('read_private_pages') && ( $securecontent != '' || $securecontent2 != '' ))		 
		{
		echo $securecontent . $securecontent2;
		}
			
	else 
		{	
		the_tags();
		the_content( sprintf(
			__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'simply-strata' ),
			get_the_title()
		) );
		
		wp_link_pages( array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'simply-strata' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>',
			'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'simply-strata' ) . ' </span>%',
			'separator'   => '<span class="screen-reader-text">, </span>',
		) );
		}	
	}
    
    // ADDEDED MAY 1, 2020
    //display documents under content if Council Documents Page
    if ($page_slug == 'council-documents' && (strstr($current_role, 'council')) ) 
        { 
        get_template_part( 'template-parts/post-council-documents', get_post_format() ); 
        }

// embed document if not empty
$url = get_field('embed_pdf');
$public = get_field('embed_pdf_public');
$embed_pdf = '<object data="' . $url . '#pagemode=thumbs&scrollbar=1&toolbar=1&statusbar=1&messages=1&navpanes=1" 
	        type="application/pdf" 
	        width="100%" 
	        height="700px">
	<p>This browser does not support inline PDFs. Please download the PDF to view it: <a href="' . $url . '">Download PDF</a></p>
	</object>';



//<object class="embed-pdf" data="' . $atts['url'] . '" type="application/pdf">This browser does not support PDFs. Please download the PDF to view it: <a href="' . $url . '"><span data-mce-type="bookmark" style="display: inline-block; width: 0px; overflow: hidden; line-height: 0;"></span>Download PDF</a>.</object>';

if ($url != '' && ( $public || current_user_can('read_private_posts'))) { echo $embed_pdf; }

// Display documents on Minutes and Other Documents Page
if ( $page_slug == 'documents') { get_template_part( 'template-parts/post-documents', get_post_format() ); }
if ( $page_slug == 'meeting-minutes') { get_template_part( 'template-parts/post-minutes', get_post_format() ); }
if ( $page_slug == 'community-update') { get_template_part( 'template-parts/post-community-updates', get_post_format() ); }
?>
</div><!-- .entry-content -->
