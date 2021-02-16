<?php
/**
 * Display meeting documents
 *
 * @package Divi Child - PACroom
 * @since Divi Child - PACroom 1.0
 */
 $page_url = get_permalink();
echo 'This is a custom snippet<br>';


// Documents posted within the time specified below are considered 'new'
$date = get_the_date();
$new_date = date('Y-m-d', strtotime('-1 week +1 day') );

// Get base url for internal links

// Set previous type to NULL when page is loaded
$previous_type = NULL;

// get category
$category = the_category( ' ' );

echo 'Today: ' . $date . '<br>1 Wk ago: ' . $new_date . '<br>URL: ' . $url;

// if user can read private posts, query all posts labelled as 'minutes'
if ( current_user_can('read_private_posts') )
	{
	$args = array(
		'post_type' => 'post',
		'post_status' => 'publish',
        'category_name' => 'Meeting Document',
		'orderby' => 'meeting_date',
		'order' => 'DESC',
		'posts_per_page' => -1
		);

	$document_posts = get_posts($args); 
	if ( $document_posts ) 
		{
		foreach($document_posts as $post)
			{
			setup_postdata( $post );
			
			$add_doc = get_field('document_doc');
			$add_xls = get_field('document_xls');
			
			// Get the meeting year and set the state of this post as current type
			$meeting = get_field('meeting_date');
			$meeting_year = new DateTime($meeting);
			$meeting_year = $meeting_year->format('Y');
			$current_type = $meeting_year;
			
			// If this post doesn't have the same 'Document Type' as the last one, let's give this one a heading and open the <ul>
			if ($current_type != $previous_type) 
				{ 
				// $previous_type isn't set until the end of this foreach, so the first one doesn't have this variable
				// If this isn't the first list, close the last list's <ul>
				if ($previous_type) 
					{ ?> </tbody></table></div> <?php } ?>
		
				<!-- Set up the table to display group of documents -->
				<div class="document-section">
					<table class="document-list" title="<?php echo $current_type . ' Meeting Minutes'; ?>">
						<thead>
							<?php
							
							?>
							<tr><td colspan="3"><h2><?php echo $current_type . ' Meeting Minutes '; ?></h2></td></tr>
								<tr style="background-color: #000; color: #fff">
								<th class="doc-title"><?php echo $current_type . ' Meetings'; ?></th>
								<th class="doc-date" scope="col">Meeting Held</th>
								<th class="doc-link" scope="col">Printable Files</th>
							</tr>
						</thead>
						<tbody>
				<?php
				}
			$meeting_type = get_field('meeting_type');
			$add_pdf = get_field('document_pdf');
			$add_doc = get_field('document_doc');
			$add_translation = get_field('document_translation');
			$permalink = get_permalink();
			
			// Format the Meeting Date to not include the year for the title
			$meeting_new = new DateTime($meeting);
			$meeting_date = $meeting_new->format('F d, Y');
			$meeting_month = $meeting_new->format('F');
			$meeting_title = $meeting_month . ' ' . $meeting_type;
			
			// Format the posted date to compare withe the 'new date' specified above
			$posted = get_the_date();
			$posted_new = new DateTime($posted);
			$posted_date = $posted_new->format('Y-m-d');
			
							// Add a 'new' class to the row if the document is new
							echo '<tr ';
							if ( $meeting_type == 'Annual General Meeting' ) { echo 'aria-label="AGM" class="agm" '; } 
							if ( $meeting_type == 'Special General Meeting' ) { echo 'aria-label="SGM" class="sgm" '; }
							if ( $new_date <= $posted_date ) { echo 'class="new" '; }  
							echo '>';
							?>
								<td class="doc-title" scope="row">
									<?php
									if ( $new_date <= $posted_date ) { echo ' <span class="new">«NEW»</span> '; } 
									echo '<a href="' . $permalink . '">' . $meeting_title . '</a>'; ?>
								</td>
								<td class="doc-date" aria-label="Meeting Held "><?php echo $meeting_date; ?></td>
								
								<td class="doc-link" aria-label="Printable Documents ">
									<?php 
									if ( $add_pdf ) { echo ' <a href="' . $add_pdf . '"><img src="' . $page_url . '/wp-content/themes/divi-child-pacroom/images/icon-pdf-en.png" width="25" height="25" alt="icon for English PDF - ' . $title . '"></a> '; }
									if ( $add_translation ) { echo ' <a href="' . $add_translation . '"><img src="' . $url . '/wp-content/themes/divi-child-pacrooma/images/icon-pdf-ch.png" width="25" height="25" alt="icon for chinese translation PDF - ' . $title . '"></a> '; }
									if ( $add_doc ) { echo '<a href="' . $add_doc . '"><img src="' . $url . '/wp-content/themes/divi-child-pacroom/images/icon-doc.png" width="25" height="25" alt="icon for DOC - ' . $title . '"></a> '; }
									if ( $add_xls ) { echo '<a href="' . $add_xls . '"><img src="' . $url . '/wp-content/themes/divi-child-pacroom/images/icon-xls.png" width="25" height="25" alt="icon for XLS - ' . $title . '"></a>'; } ?>
								</td>
							</tr>	
			<?php 
			// Remember, we closed the <ul> before the <li>'s, clever huh?
			// Set the 'State' of the this post, to compare to the next post
			$previous_type = $meeting_year;
		
			wp_reset_postdata(); 
			}	
			?>
				</tbody>
			</table>
		</div>
		
		<?php 
		} // end foreach
	} 
		?>