<?php
/*
* A funciton for checking for esc_attr.
* Important: not all HTML attributes have been included, only those commonly found in themes.
*/

function escapist_html( $lines ) {
	$line_index = 0;
	foreach( $lines as $this_line ) {
		$line_number = __("Line ", "theme-check") . ( $line_index+1 ) .'<br>';
		$this_line=preg_replace('/<p>/', '', $this_line);

		if ( preg_match( '/esc_html/', $this_line ) ) {

			if ( preg_match( '/callback/', $this_line ) ) { 
				echo '<tr>
					<th style="border-left:2px solid #dc3232;">' . $line_number . '</th>
					<td> <span style="color:#dc3232;">' . __('ERROR: esc_html may not be used as a sanitize_callback. Use a sanitizing function.', 'escapist') . '</span><br> ' . htmlspecialchars( $this_line ) .'</td>
					</tr>';
			} elseif ( preg_match( '/title=|placeholder=|value=|alt=|name=|id=|class=|aria-label=|for=|type=|data|datetime=|width=|height=|sizes=|srcset=/', $this_line ) &&
				! preg_match( '/%/', $this_line ) ) {
				echo '<tr>
				<th style="border-left:2px solid #dc3232;">' . $line_number . '</th>
				<td> <span style="color:#dc3232;">' . __('ERROR: A HTML attribute was found, a manual check is required.', 'escapist') . '</span><br>' . ' ' . 
				htmlspecialchars( $this_line ) .'</td>
				</tr>';

			} elseif ( preg_match( '/title=|placeholder=|alt=|name=|id=|class=|aria-label=|for=|type=|data|datetime=|width=|height=|sizes=|srcset=/', $this_line ) &&
				preg_match( '/%/', $this_line ) ) {
				echo '<tr>
				<th style="border-left:2px solid #F56E28;">' . $line_number . '</th>
				<td> <span style="color:#F56E28;">' . __('WARNING: A HTML attribute and a placeholder was found, a manual check is required.', 'escapist') . '</span><br>' . ' ' . 
				htmlspecialchars( $this_line ) .'</td>
				</tr>';

			} elseif ( preg_match( '/%/', $this_line ) ) {
				echo '<tr>
				<th style="border-left:2px solid #F56E28;">' . $line_number . '</th>
				<td> <span style="color:#F56E28;">' . __('Warning: A placeholder was found, a manual check is required.', 'escapist') . '</span><br>' . ' ' . 
				htmlspecialchars( $this_line ) .'</td>
				</tr>';

			} else {
				echo '<tr>
				<th style="border-left:2px solid #46B450;">' . $line_number . '</th>
				<td> <span style="color:#46B450;">' . __('PASS:', 'escapist') . '</span><br> ' . htmlspecialchars( $this_line ) .'</td>
				</tr>';
			}
		}
		$line_index++;
	} //End foreach.
}
