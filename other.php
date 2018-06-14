<?php
/*
* A funciton for checking for other escaping functions.
*/

function escapist_other( $lines ) {
	$line_index = 0;
	foreach( $lines as $this_line ) {
		$line_number = __("Line ", "theme-check") . ( $line_index+1 ) .'<br>';
		$this_line=preg_replace('/<p>/', '', $this_line);

		if ( preg_match( '/esc_/', $this_line ) ) {

			if ( preg_match( '/callback/', $this_line ) && preg_match( '/esc_url/', $this_line )  ) { 
				echo '<tr>
					<th style="border-left:2px solid #dc3232;">' . $line_number . '</th>
					<td> <span style="color:#dc3232;">' . __('ERROR: Use esc_url_raw instead of esc_url for the sanitize_callback.', 'escapist') . '</span><br> ' . htmlspecialchars( $this_line ) .'</td>
					</tr>';
			} else	if ( preg_match( '/callback/', $this_line ) && preg_match( '/esc_js/', $this_line )  ) { 
				echo '<tr>
					<th style="border-left:2px solid #dc3232;">' . $line_number . '</th>
					<td> <span style="color:#dc3232;">' . __('ERRORS: Themes should not provide options for users to add JavaScript since it is not considered safe.', 'escapist') . '</span><br> ' . htmlspecialchars( $this_line ) .'</td>
					</tr>';
			} else if ( preg_match( '/esc_url/', $this_line )  ) { 
				echo '<tr>
					<th style="border-left:2px solid #826EB4;">' . $line_number . '</th>
					<td> <span style="color:#826EB4;">' . __('<3: This link is escaped, and you are awesome.', 'escapist') . '</span><br> ' . htmlspecialchars( $this_line ) .'</td>
					</tr>';
			}
		}
		$line_index++;
	} //End foreach.
}
