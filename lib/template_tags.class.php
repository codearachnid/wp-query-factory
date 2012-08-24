<?php

if ( !defined('ABSPATH') )
	die('-1');

if( ! class_exists('WP_Query_Factory_Template_Tags') ) {
	class WP_Query_Factory_Template_Tags extends WP_Query_Factory {

		protected static $instance;

		function __construct(){}
		
		public function select( $field ){
			$default = array(
				'css'=>array(),
				'deselect'=> false,
				'id' => null,
				'name' => null,
				'label' => null,
				'key_value' => false,
				'value' => null,
				'format_option' => true
				);
			$field = wp_parse_args( $field, $default );
			extract( $field, EXTR_SKIP );
			$multiple = strpos($name,'[]') !== false ? true : false;
			echo '<select ';
			echo !is_null($id) ? 'id="' . $id . '" ' : '';
			echo 'name="' . $name . '" ';
			echo 'data-placeholder="' . $label . '" ';
			echo 'class="';
			echo $deselect ? 'chzn-select-deselect ' : '';
			echo implode(" ", (array)$css) . '" ';
			echo $multiple ? 'multiple>' : '>';
			echo $deselect ? '<option value></option>' : '';
			foreach($options as $option_id => $option_label ) {
				$option_value = $key_value ? $option_id : $option_label;
				echo '<option value="' . $option_value . '" ';
				if(is_array($value)) {
					selected( in_array( $option_value, $value ) );
				} else {
					selected( $option_value, $value );
				}
				echo $format_option ? '>' . ucwords(str_replace('-', ' ', str_replace('_', ' ', $option_label))) . '</option>' : '>' . $option_label . '</option>';
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

	if( ! function_exists('wpqf_tag_select')) {
		function wpqf_tag_select( $field, $args = array() ){
			WP_Query_Factory_Template_Tags::select($field, $args);
		}
	}
}