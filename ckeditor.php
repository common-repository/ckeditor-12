<?php
/*
Plugin Name: CKEditor 1.3
Plugin URI: http://www.cmsspace.com/demo.html
Description: Replaces the default Wordpress editor with <a href="http://www.ckeditor.com/"> CKEditor</a>
Version: 1.3
Author: Daniar
Author URI: http://www.cmsspace.com
*/
require_once('ckeditor_class.php');
add_action('admin_menu', array(&$ckeditor, 'add_option_page'));
add_action('admin_head', array(&$ckeditor, 'add_admin_head'));
add_action('personal_options_update', array(&$ckeditor, 'user_personalopts_update'));
add_action('edit_form_advanced', array(&$ckeditor, 'load_ckeditor'));
add_action('edit_page_form', array(&$ckeditor, 'load_ckeditor'));
add_action('simple_edit_form', array(&$ckeditor, 'load_ckeditor'));
add_action('admin_print_scripts', array(&$ckeditor, 'add_admin_js'));
register_activation_hook(basename(dirname(__FILE__)).'/' . basename(__FILE__), array(&$ckeditor, 'activate'));
register_deactivation_hook(basename(dirname(__FILE__)).'/' . basename(__FILE__), array(&$ckeditor, 'deactivate'));
?>