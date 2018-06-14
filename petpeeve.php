<?php
/*
* A funciton for checking for other escaping functions.
*/

function escapist_petpeeve( $lines ) {
	$line_index = 0;
	foreach( $lines as $this_line ) {
		$line_number = __("Line ", "theme-check") . ( $line_index+1 ) .'<br>';
		$this_line=preg_replace('/<p>/', '', $this_line);

		if ( preg_match( '/home_url/', $this_line ) && ! preg_match( '/esc_url/', $this_line ) ) {
			echo '<tr>
				<th style="border-left:2px solid #dc3232;">' . $line_number . '</th>
				<td> <span style="color:#dc3232;">' . __('ERROR: home_url needs to be escaped with esc_url.', 'escapist') . '</span><br> ' . 
				htmlspecialchars( $this_line ) .'</td>
				</tr>';
		} elseif ( preg_match( '/home_url/', $this_line ) && preg_match( '/esc_url/', $this_line ) ) {
			echo '<tr>
				<th style="border-left:2px solid #826EB4;">' . $line_number . '</th>
				<td> <span style="color:#826EB4;">' . __('<3: This link is escaped, and you are awesome.', 'escapist') . '</span><br> ' . htmlspecialchars( $this_line ) .'</td>
				</tr>';
		} elseif ( preg_match( '/placeholder/', $this_line ) && ! preg_match( '/esc_attr/', $this_line ) ) {
			echo '<tr>
				<th style="border-left:2px solid #dc3232;">' . $line_number . '</th>
				<td> <span style="color:#dc3232;">' . __('ERROR: The placeholder attribute needs to be escaped with esc_attr.', 'escapist') . '</span><br> ' . 
				htmlspecialchars( $this_line ) .'</td>
				</tr>';
		}

		$line_index++;
	} //End foreach.
}
