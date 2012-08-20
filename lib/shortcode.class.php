<?php

if ( !defined('ABSPATH') )
	die('-1');

if( ! class_exists('WP_Query_Factory_Shortcode') ) {
  class WP_Query_Factory_Shortcode extends WP_Query_Factory {
  	function __construct() {
		add_action( 'init', array( $this, 'shortcode_button' ) );
		add_shortcode( 'query_factory', array( $this, 'shortcode' ) );
  	}

	function shortcode_button() {
		// Don't bother doing this stuff if the current user lacks permissions
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;

		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
			add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'register_button' ) );
		}
    }
     
    function register_button($buttons) {
		array_push( $buttons, 'separator', parent::DOMAIN );
		return $buttons;
    }
     
    function add_tinymce_plugin( $plugin_array ) {
		$plugin_array[ parent::DOMAIN ] = parent::instance()->base_url . 'assets/query_factory/editor_plugin.js';
		return $plugin_array;
    }

    function shortcode( $atts ) {
		extract( shortcode_atts( array(
			'id' => 'something',
			'param' => null,
			'template' => null
			), $atts ) );

		$wp_query = parent::query( $id );

		ob_start();
		echo '<pre>';
		var_dump($wp_query->args);
		echo '</pre>';
		if( count($wp_query->results) > 0 ) {
			foreach( $wp_query->results as $post ) {
				echo $post->post_title;
				echo '<br />';
			}
		}
		
		$r = ob_get_clean();

		return $r;
    }
  }
}