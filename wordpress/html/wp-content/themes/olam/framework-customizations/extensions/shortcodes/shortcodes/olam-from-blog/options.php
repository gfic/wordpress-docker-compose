<?php if (!defined('FW')) die('Forbidden');

$options = array(

	'noposts'   => array(
		'type'  => 'text',
		'label' => esc_html__('Number of Posts', 'olam'),
		'desc'  => esc_html__("Number of blog post to show","olam"),
		),
	'specificcats'   => array(
		'type'  => 'text',
		'label' => esc_html__('Categories', 'olam'),
		'desc'  => esc_html__("Enter list of comma seperated category IDs to show posts from specific categories (example: 1,2,3). To exclude a category add a minus sign in front of the ID (example: 1,2,-3). Leave the field blank to show posts from all categories","olam"),
		),
	);
