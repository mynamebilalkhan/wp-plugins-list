<?php

/**
 * Function to Enqueue JS
 */

add_action('wp_enqueue_scripts', 'add_ajax_script');
function add_ajax_script()
{
	wp_enqueue_script('ajax-script', get_stylesheet_directory_uri() . '/js/ajax-pagination.js', array('jquery'), null, true);
	wp_localize_script('ajax-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

/**
 * Function to load plugins list
 */

add_action('wp_ajax_load_plugins', 'load_plugins_callback');
add_action('wp_ajax_nopriv_load_plugins', 'load_plugins_callback');
function load_plugins_callback()
{
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$perPage = isset($_POST['per_page']) ? intval($_POST['per_page']) : 20;

	// Initialize cURL session
	$curl = curl_init();

	// Set cURL options
	curl_setopt_array($curl, array(
		CURLOPT_URL => 'https://api.wordpress.org/plugins/info/1.2/?action=query_plugins&request[page]=' . $page . '&request[per_page]=' . $perPage,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => '',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => 'GET',
	));

	// Execute cURL request and get response
	$response = curl_exec($curl);

	// Close cURL session
	curl_close($curl);

	// Check for cURL errors
	if ($response === false) {
		wp_send_json_error(array('message' => 'Unable to retrieve plugin information.'));
	} else {
		// Decode JSON response into an associative array
		$data = json_decode($response, true);

		// Check if plugins are available in the response
		if (!empty($data['plugins'])) {
			$plugins_html = '<table class="plugin-table">';
			$plugins_html .= '<thead>';
			$plugins_html .= '<tr>';
			$plugins_html .= '<th>Name</th>';
			$plugins_html .= '<th>Version</th>';
			$plugins_html .= '<th>Last Updated</th>';
			$plugins_html .= '</tr>';
			$plugins_html .= '</thead>';
			$plugins_html .= '<tbody>';
			foreach ($data['plugins'] as $plugin) {

				$last_updated = new DateTime($plugin['last_updated']);
				$cutoff_date = new DateTime('2022-01-01');

				if ($last_updated < $cutoff_date) {
					$date = new DateTime($plugin['last_updated']);
					$formatted_date = $date->format('Y');

					$plugins_html .= '<tr class="plugin-item">';
					// $plugins_html .= '<td><a href="' . esc_url($plugin['homepage']) . '">' . esc_html($plugin['name']) . '</a></td>';
					$plugins_html .= '<td><a href="' . esc_url('https://wordpress.org/plugins/' . $plugin['slug']) . '">' . esc_html($plugin['name']) . '</a></td>';
					$plugins_html .= '<td>' . esc_html($plugin['version']) . '</td>';
					$plugins_html .= '<td>' . esc_html($formatted_date) . '</td>';
					$plugins_html .= '</tr>';
				}
			}
			$plugins_html .= '</tbody>';
			$plugins_html .= '</table>';

			// Pagination controls
			$total_plugins = $data['info']['results']; // Total number of plugins
			$total_pages = ceil($total_plugins / $perPage); // Total number of pages

			$pagination_html = '<div class="pagination">';
			if ($page > 1) {
				$pagination_html .= '<a href="#" data-page="' . ($page - 1) . '">&laquo; Previous</a>';
			}

			$start_page = max(1, $page - 2);
			$end_page = min($total_pages, $page + 2);

			if ($start_page > 1) {
				$pagination_html .= '<a href="#" data-page="1">1</a>';
				if ($start_page > 2) {
					$pagination_html .= '<span class="ellipsis">&hellip;</span>';
				}
			}

			for ($i = $start_page; $i <= $end_page; $i++) {
				if ($i == $page) {
					$pagination_html .= '<span class="current-page">' . $i . '</span>';
				} else {
					$pagination_html .= '<a href="#" data-page="' . $i . '">' . $i . '</a>';
				}
			}

			if ($end_page < $total_pages) {
				if ($end_page < $total_pages - 1) {
					$pagination_html .= '<span class="ellipsis">&hellip;</span>';
				}
				$pagination_html .= '<a href="#" data-page="' . $total_pages . '">' . $total_pages . '</a>';
			}

			if ($page < $total_pages) {
				$pagination_html .= '<a href="#" data-page="' . ($page + 1) . '">Next &raquo;</a>';
			}
			$pagination_html .= '</div>';

			wp_send_json_success(array('plugins_html' => $plugins_html, 'pagination_html' => $pagination_html));
		} else {
			wp_send_json_error(array('message' => 'No plugins found.'));
		}
	}
}
