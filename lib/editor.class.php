<?php

if ( !defined('ABSPATH') )
	die('-1');

if( ! class_exists('WP_Query_Factory_Editor') ) {
	class WP_Query_Factory_Editor extends WP_Query_Factory {
		function __construct() {
			add_action( 'admin_menu', array( $this, 'add_meta_box' ));
			add_action( 'save_post', array( $this, 'save_meta_box' ));
		}

		public function add_meta_box(){

			wp_enqueue_style( parent::DOMAIN . '-chosen', parent::instance()->base_url . 'assets/chosen/chosen.css' );
			wp_enqueue_script( parent::DOMAIN . '-chosen', parent::instance()->base_url . 'assets/chosen/chosen.jquery.min.js', array('jquery'));
			wp_enqueue_style( parent::DOMAIN . '-editor', parent::instance()->base_url . 'assets/editor.css');
			wp_enqueue_script( parent::DOMAIN . '-editor', parent::instance()->base_url . 'assets/editor.js', array('jquery'));

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
		}

		public function query_builder( $post ){

			// load saved data
			$saved_arguments = unserialize(base64_decode($post->post_content));
			// print_r($saved_arguments);
			// if( isset($saved_arguments['author']) ) {
			// 	foreach( $saved_arguments['author'] as $author_id ) {
			// 		if ($number < 0) {

			// 		} else {

			// 		}
			// 	}
			// }
			// $saved_include_authors = ;
			// $saved_exclude_authors = ;

			$post_types = apply_filters( parent::DOMAIN . '_editor_post_types', array_filter(get_post_types(), array( $this, 'exclude_post_types' ) ) );
			$users = apply_filters( parent::DOMAIN . '_editor_users', get_users() );
			
			include parent::instance()->get_view('meta.query_builder', 'views-admin');
		}

		public function template_tools( $post ){
			echo 'template_tools';
		}

		function save_meta_box( $post_id ) {
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

			if( isset($_POST['query_builder'])) {

				// print_r($_POST['query_builder']);
				// die;

				$post_name = sanitize_title($_POST['query_builder']['post_name']);
				$query_type = in_array( $_POST['query_builder']['query_type'], parent::$query_types) ? $_POST['query_builder']['query_type'] : null;

				switch( $query_type ) {
					case 'WP_User_Query':
						break;
					default:
						// protect blank query from lack of post type with default
						$_POST['query_builder']['post_type'] = isset($_POST['query_builder']['post_type']) ? $_POST['query_builder']['post_type'] : (array)'post';


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

						foreach(array('post_name','query_type','include_author','exclude_author') as $unset) {
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
					'post_mime_type' => $query_type
					));
			}
		}

	    public function force_post_update( $post_id, $data = null ){
	    	if( !empty( $data )) {
		    	global $wpdb;
		    	$wpdb->update($wpdb->posts, $data, array(  'ID' => $post_id ));
		    }
	    }

		public function exclude_post_types( $post_type ){
			return ! in_array($post_type, array(parent::FACTORY_TYPE, parent::FACTORY_TEMPLATE));
		}
	}
}