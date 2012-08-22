<?php

if ( !defined('ABSPATH') )
	die('-1');

if( ! class_exists('WP_Query_Factory_Template_Tags') ) {
	class WP_Query_Factory_Template_Tags extends WP_Query_Factory {

		protected static $instance;

		function __construct(){}
		
		public function select($data, $selected = null, $field_name = array(), $placeholder = '', $deselect = true, $key_value = false, $classes = '' ){
			$multiple = strpos(current($field_name),'[]') !== false ? true : false;
			$field_name = !is_array($field_name) ? array($field_name=>$field_name) : $field_name;
			echo '<select ';
			echo !is_numeric(key($field_name)) ? 'id="' . key($field_name) . '" ' : '';
			echo 'name="' . current($field_name) . '" ';
			echo 'data-placeholder="' . $placeholder . '" ';
			echo 'class="';
			echo ($deselect) ? 'chzn-select-deselect ' : '';
			echo implode(" ", (array)$classes) . '" ';
			echo ($multiple) ? 'multiple>' : '>';
			echo ($deselect) ? '<option value></option>' : '';
			foreach($data as $id => $row ) {
				$val = ($key_value) ? $id : $row;
				echo '<option value="' . $val . '" ';
				if($multiple) {
					selected( in_array($val, $selected) );
				} else {
					selected( $val, $selected );
				}
				echo '>' . ucwords(str_replace('-', ' ', str_replace('_', ' ', $row))) . '</option>';
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