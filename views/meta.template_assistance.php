<?php

if ( !defined('ABSPATH') )
	die('-1');

wp_nonce_field( parent::instance()->base_name, parent::DOMAIN );

?>
Wrapper Class