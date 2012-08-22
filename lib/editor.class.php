<?php

if ( !defined('ABSPATH') )
	die('-1');

if( ! class_exists('WP_Query_Factory_Editor') ) {
	class WP_Query_Factory_Editor extends WP_Query_Factory {

		protected static $instance;

		public $query_builder_default;
		public $query_builder_unset;

		function __construct() {
			add_action( 'admin_menu', array( $this, 'add_meta_box' ));
			add_action( 'save_post', array( $this, 'save_post' ));
			add_filter( 'user_can_richedit', array( $this, 'disable_richedit') );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts') );

			add_action( 'wp_ajax_' . parent::DOMAIN . '_lookup', array( $this,'ajax_lookup' ) );

			$this->query_builder_unset = apply_filters( parent::DOMAIN . '-query_builder_unset', array('post_name','query_type','include_author','exclude_author','offset','order','orderby','year','monthnum','day','hour','minute','second','w','s'));
			$this->query_builder_default =  apply_filters( parent::DOMAIN . '-query_builder_default', array('post_type','post_status'));
		}

		public function add_meta_box(){
			add_meta_box(
				'meta_query_template', 
				__('Template','wp-query-factory'), 
				array( $this, 'template_tools' ), 
				parent::FACTORY_TYPE, 
				'side'
				);
			add_meta_box(
				'meta_query_builder', 
				__('Query Builder','wp-query-factory'), 
				array( $this, 'query_builder' ), 
				parent::FACTORY_TYPE, 
				'normal',
				'high'
				);

			add_meta_box(
				'meta_template_assistance', 
				__('Help','wp-query-factory'), 
				array( $this, 'template_assistance' ), 
				parent::FACTORY_TEMPLATE, 
				'side'
				);
			add_meta_box(
				'meta_template_attributes', 
				__('Template Attributes','wp-query-factory'), 
				array( $this, 'template_attributes' ), 
				parent::FACTORY_TEMPLATE, 
				'side'
				);
		}

		public function query_builder( $post ){

			$wp_query_factory = parent::instance();

			// load saved data
			$saved_arguments = unserialize(base64_decode($post->post_content));
			// echo '<pre>'; print_r($saved_arguments); echo '</pre>';

			// setup forced defaults from param
			foreach($this->query_builder_default as $default ) {
				$saved_arguments[$default] = isset($saved_arguments[$default]) ? $saved_arguments[$default] : (array) array_shift(array_values($wp_query_factory->wp_query_param[$default]));
			}

			// setup blank defaults
			foreach(array_merge($this->query_builder_unset,array('author')) as $blank_default) {
				$saved_arguments[$blank_default] = isset($saved_arguments[$blank_default]) ? $saved_arguments[$blank_default] : '';
			}

			// setup values
			$query_types = $wp_query_factory->wp_query_param['query_type'];
			$wp_list_users = array();
			$wp_list_users_exclude = $wp_list_users;
			foreach(get_users() as $user ) {
				$wp_list_users[$user->ID] = $user->display_name;
				$wp_list_users_exclude['-'.$user->ID] = $user->display_name;
			}
			$users = apply_filters( parent::DOMAIN . '_editor_users', $wp_list_users );
			$exclude_users = apply_filters( parent::DOMAIN . '_editor_users_exclude', $wp_list_users_exclude );
			$post_types = $wp_query_factory->wp_query_param['post_type'];
			$post_status = $wp_query_factory->wp_query_param['post_status'];
			$order = $wp_query_factory->wp_query_param['order'];
			$orderby = $wp_query_factory->wp_query_param['orderby'];
			$offset = isset($saved_arguments['offset']) ? $saved_arguments['offset'] : '';
			$year = isset($saved_arguments['year']) ? $saved_arguments['year'] : '';
			$monthnum = array();
			for($i=1;$i<13;$i++){
				$monthnum[$i] = date( 'F', mktime(0, 0, 0, $i) );
			}
			$day = array();
			for($i=1;$i<32;$i++){
				$day[$i] = $this->ordinal($i);
			}
			$hour = array();
			for($i=1;$i<25;$i++){
				$hour[$i] = $i;
			}
			$minute = array();
			$second = array();
			for($i=1;$i<61;$i++){
				$minute[$i] = $i;
				$second[$i] = $i;
			}
			$w = array();
			for($i=1;$i<53;$i++){
				$w[$i] = $this->ordinal($i);
			}
			$s = isset($saved_arguments['s']) ? $saved_arguments['s'] : '';

			// setup ordinal formatting if I can figure out why PHP 5.3+ throws class not found err
			// $ordinal = new NumberFormatter( (WPLANG != '') ? WPLANG : 'en_US', NumberFormatter::ORDINAL);
			
			include parent::instance()->get_view('meta.query_builder');
		}

		public function template_tools( $post ){
			include parent::instance()->get_view('meta.template_tools');
		}

		public function template_assistance( $post ) {
			include parent::instance()->get_view('meta.template_assistance');
		}
		public function template_attributes( $post ) {
			include parent::instance()->get_view('meta.template_attributes');
		}

		public function ajax_lookup(){
			$term = trim($_REQUEST['term']);
			$presenters = array();
			foreach(self::search_presenters($term) as $presenter) {
				$presenters[]= array($presenter->data->ID, $presenter->data->display_name);
			}

			echo json_encode($presenters);
			exit;
		}

		public function save_post( $post_id ){

			// Verify if this is an auto save routine. 
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
			  return;

			// Ensure the nonce is set
			if ( ! isset($_POST[ parent::DOMAIN ]) )
				return;

			// Verify this came from the edit screen and with proper authorization
			if ( !wp_verify_nonce( $_POST[ parent::DOMAIN ], parent::instance()->base_name ) )
			  return;

			// Check permissions
			if ( in_array( $_POST['post_type'], array(parent::FACTORY_TYPE, parent::FACTORY_TEMPLATE)) ) {
				if ( !current_user_can( 'edit_page', $post_id ) )
				    return;
			} else {
				if ( !current_user_can( 'edit_post', $post_id ) )
				    return;
			}

			//verify post is not a revision
			if ( !wp_is_post_revision( $post_id ) ) {
				$wp_query_factory = parent::instance();
				switch( $_POST['post_type'] ) {
					case parent::FACTORY_TYPE:
						if( isset($_POST['query_builder'])) {

							// print_r($_POST['query_builder']);
							// die;

							$post_name = sanitize_title($_POST['query_builder']['post_name']);
							$default_template = isset($_POST['template_tools']['default_template']) && !is_null($_POST['template_tools']['default_template']) ? $_POST['template_tools']['default_template'] : '';
							$query_type = in_array( $_POST['query_builder']['query_type'], $wp_query_factory->wp_query_param['query_type']) ? $_POST['query_builder']['query_type'] : null;

							switch( $query_type ) {
								case 'WP_User_Query':
									break;
								default:
									
									// protect blank query from lack of field value with default
									foreach($this->query_builder_default as $default ) {
										$_POST['query_builder'][$default] = isset($_POST['query_builder'][$default]) ? $_POST['query_builder'][$default] : (array) array_shift(array_values($wp_query_factory->wp_query_param[$default]));
									}

									// prevent a blank author filter from existing
									if( !empty($_POST['query_builder']['include_author']) && !empty($_POST['query_builder']['exclude_author'])) {
										$_POST['query_builder']['author'] = array_merge( (array)$_POST['query_builder']['include_author'], (array)$_POST['query_builder']['exclude_author']);
									} elseif( isset($_POST['query_builder']['include_author']) ) {
										$_POST['query_builder']['author'] = (array)$_POST['query_builder']['include_author'];
									} elseif( isset($_POST['query_builder']['exclude_author']) ) {
										$_POST['query_builder']['author'] = (array)$_POST['query_builder']['exclude_author'];
									}

									// print_r($_POST['query_builder']);
									// die;

									foreach($this->query_builder_unset  as $unset) {
										unset($_POST['query_builder'][$unset]);
									}

									// print_r($_POST['query_builder']);
									// die;
									
									$post_content = base64_encode(serialize($_POST['query_builder']));
								break;
							}

							// Force post_content to be set outside of save_post loop
							$this->force_post_update( $post_id , array(
								'post_name' => $post_name,
								'post_content' => $post_content,
								'post_mime_type' => $query_type,
								'to_ping' => $default_template
								));

							// flush the transient it will be rebuilt on first call from the front
							delete_transient( self::TRANSIENT . '_' . $post_name );
						}
						break;
					case parent::FACTORY_TEMPLATE:

						// print_r($_POST);
						// die;
						
						// set the post_name from the widget template_tools
						$template_name = isset($_POST['template_tools']['post_name']) ? sanitize_title($_POST['template_tools']['post_name']) : sanitize_title($_POST['post_name']);

						$t = self::generate_template( $_POST['post_ID'], $_POST['post_content'] );
						$file = $wp_query_factory->get_template( $template_name, false );

						// save template to file system
						file_put_contents( $file, $t );

						// Force post_content to be set outside of save_post loop
						$this->force_post_update( $post_id , array(
							'post_name' => $template_name
							));

						break;
					default:
						// ignore for all other post types
						break;
				}
			}
		}

		public function generate_template( $post_id, $template ) {
			$t = '<?php if ( !defined("ABSPATH") ) die("-1");' . "\n" .
				'/* GENERATED BY "' . parent::FACTORY_TYPE . '" on: ' . date(DATE_RFC822) . ' */ ?>' . "\n" .
				'<div class="' . implode(" ", get_post_class('',$post_id)) . '">' . "\n" .
				self::clean_template( $template ) .
				'</div>';
			return $t;
		}

		public function clean_template( $template ) {
			// we break WordPress if 'the_content(%)' is in the template because it's being called inside the_content()
			return str_replace('the_content(', 'WP_Query_Factory::the_content(', stripslashes($template));
		}

	    // prevent wysiwyg rich editor from showing for templates
	    public function disable_richedit($c) {
	        global $post_type;
	        // if ( in_array( $post_type, array( self::FACTORY_TYPE, self::FACTORY_TEMPLATE) ) ) {
	        if( parent::check_factory_types( $post_type ) ) {
	          return false;
	        }
	        return $c;
	    }

	    public function admin_enqueue_scripts() {
	        // if ( in_array( get_post_type(), array( self::FACTORY_TYPE, self::FACTORY_TEMPLATE)) )
	        if( parent::check_factory_types( get_post_type() ) ) {        				
				wp_enqueue_style( parent::DOMAIN . '-chosen', parent::instance()->base_url . 'assets/chosen/chosen.css' );
				wp_enqueue_script( parent::DOMAIN . '-chosen', parent::instance()->base_url . 'assets/chosen/chosen.jquery.min.js', array('jquery'));
				wp_enqueue_style( parent::DOMAIN . '-editor', parent::instance()->base_url . 'assets/editor.css');
				wp_enqueue_script( parent::DOMAIN . '-editor', parent::instance()->base_url . 'assets/editor.js', array('jquery'));
				// prevent autosaves on plugin post types (prevents live results from changing during edit)
				wp_dequeue_script( 'autosave' );
	        }
	    }

		public function ordinal($n) {
			return $n . date('S',mktime(1,1,1,1,( (($n>=10)+($n>=20)+($n==0))*10 + $n%10) ));
		}

	    public function force_post_update( $post_id, $data = null ){
	    	if( !empty( $data )) {
		    	global $wpdb;
		    	$wpdb->update($wpdb->posts, $data, array(  'ID' => $post_id ));
		    }
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