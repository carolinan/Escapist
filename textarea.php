<?php
/*
* A funciton for checking for esc_attr.
* Important: not all HTML attributes have been included, only those commonly found in themes.
*/

function escapist_textarea( $lines ) {
	$line_index = 0;
	foreach( $lines as $this_line ) {
		$line_number = __("Line ", "theme-check") . ( $line_index+1 ) .'<br>';
		$this_line=preg_replace('/<p>/', '', $this_line);

		if ( preg_match( '/esc_textarea/', $this_line ) ) {

			if ( preg_match( '/callback/', $this_line ) ) { 
				echo '<tr>
					<th style="border-left:2px solid #dc3232;">' . $line_number . '</th>
					<td> <span style="color:#dc3232;">' . __('ERROR: esc_textarea may not be used as a sanitize_callback. Use a sanitizing function.', 'escapist') . '</span><br> ' . htmlspecialchars( $this_line ) .'</td>
					</tr>';
			} elseif ( ! preg_match( '/textarea/', $this_line )  ) {
				echo '<tr>
				<th style="border-left:2px solid #dc3232;">' . $line_number . '</th>
				<td> <span style="color:#dc3232;">' . __('ERROR: esc_textarea was found, but no textarea element is present, a manual check is required.', 'escapist') . '</span><br>' . ' ' . 
				htmlspecialchars( $this_line ) .'</td>
				</tr>';
			} 
		} elseif ( preg_match( '/<textarea/', $this_line ) ) {
			echo '<tr>
				<th style="border-left:2px solid #00A0D2;">' . $line_number . '</th>
				<td> <span style="color:#00A0D2;">' . __('NOTICE: A textarea element was found. Check if it has content that needs to be escaped.', 'escapist') . '</span><br> ' . htmlspecialchars( $this_line ) .'</td>
				</tr>';
		}
		$line_index++;
	} //End foreach.
}
