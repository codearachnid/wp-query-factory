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

			// custom WP LIST TABLE
			add_filter( 'post_row_actions', array($this,'post_row_actions'), 10, 2 );
			add_filter( 'manage_'.parent::FACTORY_TYPE.'_posts_columns' , array($this,'set_query_columns'));
			add_action( 'manage_'.parent::FACTORY_TYPE.'_posts_custom_column' , array($this,'set_query_row'), 10, 2 );
			add_filter( 'manage_'.parent::FACTORY_TEMPLATE.'_posts_columns' , array($this,'set_template_columns'));
			add_action( 'manage_'.parent::FACTORY_TEMPLATE.'_posts_custom_column' , array($this,'set_template_row'), 10, 2 );

			add_action( 'wp_ajax_' . parent::DOMAIN . '_lookup', array( $this,'ajax_lookup' ) );
			$this->query_builder_unset = apply_filters( parent::DOMAIN . '-query_builder_unset', array('post_name','include_author','exclude_author','offset','order','orderby','year','monthnum','day','hour','minute','second','w','s'));
			$this->query_builder_default =  apply_filters( parent::DOMAIN . '-query_builder_default', array('post_type','post_status'));
		}

		public function add_meta_box(){
			wp_enqueue_style( parent::DOMAIN . '-chosen', parent::instance()->base_url . 'assets/js/chosen/chosen.css' );
			wp_enqueue_script( parent::DOMAIN . '-chosen', parent::instance()->base_url . 'assets/js/chosen/chosen.jquery.min.js', array('jquery'));			
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
			$load_args = unserialize(base64_decode($post->post_content));
			$saved_arguments = $load_args;
			// echo '<pre>'; print_r($saved_arguments); echo '</pre>';
			
			foreach($wp_query_factory->field_list as $field => $setup){
				switch($field) {
					case 'default_template':
						$wp_query_factory->field_list[$field]['value'] = $post->to_ping;
						break;
					case 'post_name':
						$wp_query_factory->field_list[$field]['value'] = $post->post_name;
						break;
					case 'query_type':
						$wp_query_factory->field_list[$field]['value'] = $post->post_mime_type;
						break;
					case 'author_exclude':
						$wp_query_factory->field_list[$field]['value'] = isset($load_args['author']) ? $load_args['author'] : null;
						break;
					case 'orderby':
						$wp_query_factory->field_list[$field]['value'] = isset($load_args[$field]) ? explode(" ", $load_args[$field]) : null;
						break;
					case 'category_type': // setup advanced category selection and types
						foreach($wp_query_factory->field_list['category_type']['options'] as $option => $title) {
							if(isset($load_args[$option])) {
								$wp_query_factory->field_list[$field]['value'] = $option;
								$wp_query_factory->field_list['cat']['value'] = $load_args[$option];
							}
						}
						break;
					default:
						$wp_query_factory->field_list[$field]['value'] = isset($load_args[$field]) ? $load_args[$field] : null;
						break;
				}
			}

			// setup values
			// $wp_list_users = array();
			// $wp_list_users_exclude = $wp_list_users;
			// foreach(get_users() as $user ) {
			// 	$wp_list_users[$user->ID] = $user->display_name;
			// 	$wp_list_users_exclude['-'.$user->ID] = $user->display_name;
			// }
			// $users = apply_filters( parent::DOMAIN . '_editor_users', $wp_list_users );
			// $exclude_users = apply_filters( parent::DOMAIN . '_editor_users_exclude', $wp_list_users_exclude );

			include $wp_query_factory->get_view('meta.query_builder');
		}

		public function template_tools( $post ){
			$wpqf = parent::instance();
			$wpqf->field_list['default_template']['value'] = $post->to_ping;
			include $wpqf->get_view('meta.template_tools');
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
							// set the "query ID" from the post_name or post_title if blank
							$post_name = !empty($_POST['query_builder']['post_name']) ? sanitize_title($_POST['query_builder']['post_name']) : sanitize_title($_POST['post_title']);
							$default_template = isset($_POST['template_tools']['default_template']) && !is_null($_POST['template_tools']['default_template']) ? $_POST['template_tools']['default_template'] : '';
							$query_type = in_array( $_POST['query_builder']['query_type'], $wp_query_factory->field_list['query_type']['options']) ? $_POST['query_builder']['query_type'] : null;

							switch( $query_type ) {
								case 'WP_User_Query':
									break;
								default:

									foreach($wp_query_factory->field_list as $field => $setup){

										$unset = false;

										foreach( array('required','not_arg') as $bool ) {
											${$bool} = isset($setup[$bool]) ? $setup[$bool] : false ;	
										}
										
										switch($field) {
											// no need for these fields in the query_builder args
											case 'query_type':
											case 'post_name':
											case 'default_template':
												$unset = true;
												break;
											case 'author_exclude':
												$author = $_POST['query_builder']['author'];
												$author_exclude = $_POST['query_builder'][$field];
												$merged_authors = array_merge( (array)$author, (array)$author_exclude);
												$_POST['query_builder']['author'] = $merged_authors;
												if(empty($_POST['query_builder']['author']))
													unset($_POST['query_builder']['author']);
												$unset = true;
											case 'orderby':
												$_POST['query_builder'][$field] = (is_array($_POST['query_builder'][$field]) && count($_POST['query_builder'][$field]) == 1) ? $_POST['query_builder'][$field][0] : implode(" ", $_POST['query_builder'][$field]);
												if(empty($_POST['query_builder'][$field]))
													$unset = true;
												break;
											case 'category_type': // setup advanced category selection and types
												if(!empty($_POST['query_builder'][$field])) {
													$_POST['query_builder'][ $_POST['query_builder'][$field] ] = (array) $_POST['query_builder']['cat'];
													unset($_POST['query_builder']['cat']);
												}
												$unset = true;
												break;
											default:
												if(!empty($_POST['query_builder'][$field])) {
													$_POST['query_builder'][$field] = (is_array($_POST['query_builder'][$field]) && count($_POST['query_builder'][$field]) == 1) ? $_POST['query_builder'][$field][0] : $_POST['query_builder'][$field];
												} else{
													$unset = true;
												}

												// fail check for required values
												if( $required ) {
													$_POST['query_builder'][$field] = empty($_POST['query_builder'][$field]) ? $setup['default'] : $_POST['query_builder'][$field];
													$unset = false;
												}
												break;
										}
										if($unset)
											unset($_POST['query_builder'][$field]);
									}
									
									$post_content = base64_encode(serialize($_POST['query_builder']));
								break;
							}

							// Force post_content to be set outside of save_post loop
							// $this->force_post_update( $post_id , array(
							// 	'ID' => $post_id,
							// 	'post_name' => $post_name,
							// 	'post_content' => $post_content,
							// 	'post_mime_type' => $query_type,
							// 	'to_ping' => $default_template
							// 	));

							// unhook this function so it doesn't loop infinitely
							remove_action( 'save_post', array( $this, 'save_post' ) );
							// update the post, which calls save_post again
							wp_update_post(array(
								'ID' => $post_id,
								'post_name' => $post_name,
								'post_content' => $post_content,
								'post_mime_type' => $query_type,
								'to_ping' => $default_template
								));
							// re-hook this function
							add_action( 'save_post', array( $this, 'save_post' ) );

							// flush the transient it will be rebuilt on first call from the front
							delete_transient( self::TRANSIENT . '_' . $post_name );
						}
						break;
					case parent::FACTORY_TEMPLATE:

						// print_r($_POST);
						// die;
						
						// set the template_name from the template_tools or post_title if blank
						$template_name = !empty($_POST['template_tools']['post_name']) ? sanitize_title($_POST['template_tools']['post_name']) : sanitize_title($_POST['post_title']);

						$t = self::generate_template( $_POST['post_content'] );
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

		public function generate_template( $template ) {
			$t = '<?php if ( !defined("ABSPATH") ) die("-1");' . "\n" .
				'/* GENERATED BY "' . parent::FACTORY_TYPE . '" on: ' . date(DATE_RFC822) . ' */ ?>' . "\n" .
				'<div class="' . implode(" ", get_post_class()) . '">' . "\n" .
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

	    public function force_post_update( $post_id, $data = null ){
	    	if( !empty( $data )) {
		    	global $wpdb;
		    	$wpdb->update($wpdb->posts, $data, array(  'ID' => $post_id ));
		    }
	    }

		public function post_row_actions( $actions, $post ) {
			global $current_screen;
			if( parent::exclude_factory_types($current_screen->post_type) ) 
				return $actions;
			// unset( $actions['edit'] );
			unset( $actions['view'] );
			// unset( $actions['trash'] );
			unset( $actions['inline hide-if-no-js'] );

			return $actions;
		}

		public function set_query_columns($columns) {
		    return array(
		        'cb' => '<input type="checkbox" />',
		        'title' => __('Query','wp-query-factory'),
		        'query_id' => __('Shortcode','wp-query-factory'),
		        'query_type' => __('Type','wp-query-factory'),
		        'template' =>__( 'Template','wp-query-factory')
		    );
		}

		public function set_query_row( $column, $post_id ) {
			$query_lookup = new WP_Query(array('p'=>$post_id,'post_type'=>parent::FACTORY_TYPE));
			$query_lookup = $query_lookup->posts[0];
		    switch ( $column ) {
		    	case 'query_id':
		    		echo '[query_factory id="' . $query_lookup->post_name . '"]';
		    		break;
				case 'query_type':
					echo ucwords(str_replace('-', ' ', str_replace('_', ' ', $query_lookup->post_mime_type)));
					break;
		    	case 'template':
		    		if( empty($query_lookup->to_ping)) {
		    			_e('No Template Set','wp-query-factory');
		    		} else {
			    		$template_lookup = new WP_Query(array('name'=>$query_lookup->to_ping,'post_type'=>parent::FACTORY_TEMPLATE));
			    		if(!empty($template_lookup->posts)) {
				    		$template_lookup = $template_lookup->posts[0];
				    		echo $template_lookup->post_title . apply_filters(parent::DOMAIN . '_query_row_edit', ' <a href="' . get_admin_url() . 'post.php?post=' . $template_lookup->ID . '&action=edit">(' . __('Edit','wp-query-factory') . ')</a>');
				    	} else {
				    		_e('No Template Found','wp-query-factory');
				    	}
				    }
		    		break;
				default :
		    }
		}

		public function set_template_columns($columns) {
		    return array(
		        'cb' => '<input type="checkbox" />',
		        'title' => __('Template','wp-query-factory'),
		        'template_id' => __('ID','wp-query-factory'),
		        'query_lookup' => __('Associated Queries','wp-query-factory')
		    );
		}

		public function set_template_row( $column, $post_id ) {
			$template_lookup = new WP_Query(array('p'=>$post_id,'post_type'=>parent::FACTORY_TEMPLATE));
			$template_lookup = $template_lookup->posts[0];
		    switch ( $column ) {
		    	case 'template_id':
		    		echo $template_lookup->post_name;
		    		break;
		    	case 'query_lookup':
		    		global $wpdb;
		    		// $query_lookup = new WP_Query(array('name'=>$query_lookup->to_ping,'post_type'=>parent::FACTORY_TEMPLATE));
		    		$query_lookup = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE to_ping = '{$template_lookup->post_name}';" ) );
		    		if(!empty($query_lookup)) {
			    		echo '<ul>';
			    		foreach ( $query_lookup as $query )
				    		echo '<li>'.$query->post_title . apply_filters(parent::DOMAIN . '_template_row_edit', ' <a href="' . get_admin_url() . 'post.php?post=' . $query->ID . '&action=edit">(' . __('Edit','wp-query-factory') . ')</a>') . '</li>';
			    		echo '</ul>';
			    	} else {
			    		_e('No associated queries found','wp-query-factory');
			    	}
		    		break;
				default :
		    }
		}

	    public function admin_enqueue_scripts() {
	        // if ( in_array( get_post_type(), array( self::FACTORY_TYPE, self::FACTORY_TEMPLATE)) )
	        if( parent::check_factory_types( get_post_type() ) ) {        				
				wp_enqueue_style( parent::DOMAIN . '-editor', parent::instance()->base_url . 'assets/css/editor.css');
				wp_enqueue_script( parent::DOMAIN . '-editor', parent::instance()->base_url . 'assets/js/editor.js', array('jquery'));
				// prevent autosaves on plugin post types (prevents live results from changing during edit)
				wp_dequeue_script( 'autosave' );
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