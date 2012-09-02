<?php

if ( !defined('ABSPATH') )
	die('-1');

if( ! class_exists('WP_Query_Factory_Shortcode') ) {
  class WP_Query_Factory_Shortcode extends WP_Query_Factory {

  	protected static $instance;

  	function __construct() {
		add_action( 'init', array( $this, 'shortcode_button' ) );
		add_shortcode( 'query_factory', array( $this, 'shortcode' ) );
		// load shortcode button query select
		add_action( 'wp_ajax_wp_query_factory_select_query', array($this, 'mce_select_query'));
  	}

	function shortcode_button() {
		// Don't bother doing this stuff if the current user lacks permissions
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;

		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
			wp_enqueue_style(parent::DOMAIN.'-shortcode-css', parent::instance()->base_url . 'assets/css/shortcode_editor.css');
			add_filter( 'mce_external_plugins', array( $this, 'add_tinymce_plugin' ) );
			add_filter( 'mce_buttons', array( $this, 'register_button' ) );
			add_action( 'admin_footer', array( $this, 'mce_select_query'));
		}
    }

    public function mce_select_query(){
      $available_queries = parent::available_queries();
      $queries = array();
      foreach($available_queries as $query){
      	$queries[$query->post_name] = $query->post_title;
      }
      include parent::instance()->get_view('mce_select_query');
      // exit;
    }
     
    function register_button($buttons) {
		array_push( $buttons, 'separator', parent::DOMAIN );
		return $buttons;
    }
     
    function add_tinymce_plugin( $plugin_array ) {
		$plugin_array[ parent::DOMAIN ] = parent::instance()->base_url . 'assets/js/editor_plugin.js';
		return $plugin_array;
    }

    function shortcode( $atts ) {
		extract( shortcode_atts( array(
			'id' => 'something',
			'param' => null,
			'template' => null
			), $atts ) );

		$wp_query = parent::query( $id );
		$load_template = !is_null($wp_query) ? $wp_query->default_template : null;
		$wp_query_factory = parent::instance();

		ob_start();
		// echo '<pre>';
		// var_dump($wp_query->args);
		// echo '</pre>';
		if( !empty($load_template) && $wp_query_factory->get_template( $load_template ) && count($wp_query->results) > 0 ) {
			global $post;
			wp_enqueue_style(parent::DOMAIN . '-front-css', $wp_query_factory->base_url . 'assets/css/front.css');
			$request_page_id = get_the_ID();
			foreach( $wp_query->results as $post ) {
				setup_postdata($post);
				$post->request_page_id = $request_page_id;
				include $wp_query_factory->get_template( $load_template );
			}
			wp_reset_postdata();
		}
		
		$r = ob_get_clean();

		return $r;
    }

    /* Static Singleton Factory Method */
	public static function instance() {
	  if ( !isset( self::$instance ) ) {
	    $className = __CLASS__;
	    self::$instance = new $className;
	  }
	  return self::$instance;
	}
  }
}