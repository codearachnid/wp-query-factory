<?php

/*
Plugin Name: WP Query Factory
Plugin URI: 
Description: Build powerful queries with the WYSIWYG editor for using as shortcodes, widgets or in your code.
Version: 1.0
Author: Timothy Wood (@codearachnid)
Author URI: http://www.codearachnid.com	
Author Email: tim@imaginesimplicity.com
Text Domain: wp-query-factory
License: GPLv2 or later

Notes:

License:

  Copyright 2011 Imagine Simplicity (tim@imaginesimplicity.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

if( ! class_exists('WP_Query_Factory') ) {
  class WP_Query_Factory {

    protected static $instance;

    const VERSION = 1.0;
    const DOMAIN = 'wp-query-factory';

    function __construct() {
      add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
    }

    public function admin_menu() {
      add_menu_page( __('WP Query Factory', 'wp-query-factory'), __('Query Factory', 'wp-query-factory'), 'manage_options', self::DOMAIN, array($this,'factory'), plugins_url('wp-query-factory/assets/script_gear.png'), 79.5);
    }

    function factory(){}

    /* Static Singleton Factory Method */
    public static function instance() {
      if ( !isset( self::$instance ) ) {
        $className = __CLASS__;
        self::$instance = new $className;
      }
      return self::$instance;
    }
  }

  /**
   * Instantiate class and set up WordPress actions.
   *
   * @return void
   */
  function Load_WP_Query_Factory() {
    $run_or_not = class_exists( 'WP_Query_Factory' ) && defined( 'WP_Query_Factory::DOMAIN' );
    if ( apply_filters( 'wp_query_factory_run_or_not', $run_or_not ) ) {
      $wp_query_factory = WP_Query_Factory::instance();
    }
  }
  add_action( 'plugins_loaded', 'Load_WP_Query_Factory', 1); // high priority so that it's not too late for addon overrides
}