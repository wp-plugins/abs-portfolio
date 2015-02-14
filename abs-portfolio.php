<?php

/*
Plugin Name: ABS Portfolio
Plugin URI: http://www.absiddik.net/demo/wp/plugins/abs-portfolio/
Description: This plugin will enable portfolio in your wordpress theme. You can embed portfolio via shortcode in everywhere you want, even in theme files. 
Author: AB Siddik
Version: 1.0.0
Author URI: http://absiddik.net
*/



/*
 *Latest Jquery For ABS Portfolio Plugin.
 */
function abs_portfolio_latest_jquery() {
    wp_enqueue_script( 'jquery' );
}

add_action( 'init', 'abs_portfolio_latest_jquery' );



/*Some Set-up*/
define('ABS_PORTFOLIO_PLUGIN_WP', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );



/**
 * Main Jquery and Style for ABS Portfolio Plugin,
 */
function abs_accorfion_main_jquery() {
	
	wp_enqueue_script( 'abs-mod-js',ABS_PORTFOLIO_PLUGIN_WP.'js/modernizr.custom.97074.js',true);
	
	wp_enqueue_script( 'abs-portfolio-js',ABS_PORTFOLIO_PLUGIN_WP.'js/jquery.hoverdir.js', array('jquery'));
	
	wp_enqueue_script( 'abs-portfolio-activ-js',ABS_PORTFOLIO_PLUGIN_WP.'js/activ.js', array('jquery'));

	wp_enqueue_style( 'abs-portfolio-css',ABS_PORTFOLIO_PLUGIN_WP.'css/abs-portfolio.css');
	
}

add_action( 'init', 'abs_accorfion_main_jquery' );



/*Thumbnails support for ABS Portfolio*/
add_theme_support( 'post-thumbnails', array( 'post', 'abs-portfolio-items') );
add_image_size( 'abs_por_image',200,145, true );



/*This custom post for ABS Portfolio*/
add_action( 'init', 'abs_portfolio_custompost' );

function abs_portfolio_custompost() {
	$labels = array(
		'name'               => _x( 'Portfolio Item', 'abs-portfolio-panel' ),
		'singular_name'      => _x( 'Portfolio Item',  'abs-portfolio-panel' ),
		'menu_name'          => _x( 'Portfolio Items', 'abs-portfolio-panel' ),
		'name_admin_bar'     => _x( 'Portfolio Item',  'abs-portfolio-panel' ),
		'add_new'            => _x( 'Add New Portfolio', 'abs-portfolio-panel' ),
		'add_new_item'       => __( 'Add New Portfolio', 'abs-portfolio-panel' ),
		'new_item'           => __( 'New Portfolio', 'abs-portfolio-panel' ),
		'edit_item'          => __( 'Edit Portfolio', 'abs-portfolio-panel' ),
		'view_item'          => __( 'View Portfolio', 'abs-portfolio-panel' ),
		'all_items'          => __( 'All Portfolios', 'abs-portfolio-panel' ),
		'search_items'       => __( 'Search Portfolios', 'abs-portfolio-panel' ),
		'parent_item_colon'  => __( 'Parent Portfolios:', 'abs-portfolio-panel' ),
		'not_found'          => __( 'No Portfolio found.', 'abs-portfolio-panel' ),
		'not_found_in_trash' => __( 'No Portfolio found in Trash.', 'abs-portfolio-panel' )
	);

	$args = array(
		'labels'             => $labels,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'portfolio-item' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title','custom-fields','thumbnail' )
	);

	register_post_type( 'abs-portfolio-items', $args );
}



/* ----This Code for ABS Portfolio Item Custom texonomy------*/
function abs_portfolio_custom_post_taxonomy() {
	register_taxonomy(
		'portfolio_cat',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
		'abs-portfolio-items',                  //post type name
		array(
			'hierarchical'          => true,
			'label'                         => 'Portfolio Catagory',  //Display name
			'query_var'             => true,
			'show_admin_column'             => true,
			'rewrite'                       => array(
				'slug'                  => 'portfolio-cat', // This controls the base slug that will display before each term
				'with_front'    => true // Don't display the category base before
				)
			)
	);
	
}

add_action( 'init', 'abs_portfolio_custom_post_taxonomy'); 



/* This sortcode use for ABS Portfolio  */
function abs_portfolio_shortcode($atts){
	extract( shortcode_atts( array(
		'category' => '',
		'link' => 'www.absiddik.net',
	), $atts, 'category_post' ) );
	
    $q = new WP_Query(
        array( 'portfolio_cat' => $category, 'posts_per_page' => -1, 'post_type' => 'abs-portfolio-items')
        );
		
	$list = '<section class="main_waraper"><ul id="da-thumbs" class="da-thumbs">';

	while($q->have_posts()) : $q->the_post();
		//get the ID of your post in the loop
		$idd = get_the_ID();
		
		$tn_id = get_post_thumbnail_id();
		$abs_por_image = wp_get_attachment_image_src( $tn_id, 'abs_por_image');
		$portfolio_link = get_post_meta($idd, 'portfolio_link', true);
		
		$list .= '
				<li>
					<a href="http://'.$portfolio_link.'" target="_blank">
						<img src="'.$abs_por_image[0].'" />
						<div>
							<span>'.get_the_title().'</span>
						</div>
					</a>
				</li>
				';        
	endwhile;
	$list.= '</ul></section>';
	wp_reset_query();
	return $list;
}
add_shortcode('abs_portfolio', 'abs_portfolio_shortcode');



/* ABS Portfolio shortcode button*/
function abs_portfolio_buttons() {
	add_filter ("mce_external_plugins", "abs_portfolio_external_js");
	add_filter ("mce_buttons", "abs_portfolio_awesome_buttons");
}

function abs_portfolio_external_js($plugin_array) {
	$plugin_array['absportfolio'] = plugins_url('js/custom-button.js', __FILE__);
	return $plugin_array;
}

function abs_portfolio_awesome_buttons($buttons) {
	array_push ($buttons, 'abs_portfolio');
	return $buttons;
}

add_action ('init', 'abs_portfolio_buttons');













?>