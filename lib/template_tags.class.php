<?php

if ( !defined('ABSPATH') )
	die('-1');

if( ! class_exists('WP_Query_Factory_Template_Tags') ) {
	class WP_Query_Factory_Template_Tags extends WP_Query_Factory {

		protected static $instance;

		function __construct(){}
		
		public function select($data, $selected = null, $field_name = array(), $placeholder = '', $multiple = false, $deselect = false, $key_value = false ){
			$field_name = !is_array($field_name) ? array($field_name=>$field_name) : $field_name;
			echo '<select id="' . key($field_name) . '" name="' . current($field_name) . '" ';
			echo 'data-placeholder="' . $placeholder . '" ';
			echo ($multiple) ? 'multiple ' : '';
			echo '>';
			foreach($data as $id => $row ) {
				$val = ($key_value) ? $id : $row;
				echo '<option value="' . $val . '" ';
				if(strpos(current($field_name),'[]') !== false) {
					selected( in_array($val, $selected) );
				} else {
					selected( $val, $selected );
				}
				echo '>' . $row . '</option>';
			}
			echo '</select>';
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