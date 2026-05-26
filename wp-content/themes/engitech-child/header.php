<?php
/**
 * The header for our theme - Engitech Child
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Engitech Child
 * @version Vertex Ray - Optimized
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>
	<!-- Tracking codes managed by functions.php - vertexray_insert_google_analytics() -->
</head>

<body <?php body_class(); ?>>
	<!-- GTM noscript managed by functions.php - vertexray_gtm_noscript() -->
<?php wp_body_open(); ?>
<div id="page" class="site">
	<?php if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'header' ) ) { 
		get_template_part( 'template-parts/content', 'header' );
	} ?>

	<div id="content" class="site-content">
	<?php engitech_page_header(); ?>
