<?php if (!defined('FW')) die('Forbidden');

$shortcodes_extension = fw_ext('shortcodes');
wp_enqueue_style(
	'fw-shortcode-table',
	$shortcodes_extension->get_declared_URI('/shortcodes/table/static/css/styles.css')
);
fw_ext('shortcodes')->get_shortcode('button')->_enqueue_static();