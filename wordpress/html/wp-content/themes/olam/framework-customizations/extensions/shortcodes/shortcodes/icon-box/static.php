<?php if (!defined('FW')) die('Forbidden');

$shortcodes_extension = fw_ext('shortcodes');
wp_enqueue_style(
	'fw-shortcode-icon-box',
	$shortcodes_extension->get_declared_URI('/shortcodes/icon-box/static/css/styles.css'),
	array('fw-font-awesome')
);

