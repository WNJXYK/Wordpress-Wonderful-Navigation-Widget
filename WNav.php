<?php
/*
 * Plugin Name: Wonderful Nav Widget
 * Plugin URI: https://wnjxyk.tech/black-tech/wordpress/wonderful-nav-widget/
 * Description:	增加一个可层级展开的导航菜单小工具
 * Version: 1.0
 * Author: 由 <a href="https://wnjxyk.tech">WNJXYK</a> 开发 
 * Author URI: 
 * License: GPLv2
 * Copyright 2020 WNJXYK (email : wnjxyk@gmail.com)
 */

define('WNAV_VERSION_NUM', '1.0');
define('WNAV_MINIMUM_WP_VERSION', '4.0');
define('WNAV_URL', plugins_url('', __FILE__));
define('WNAV_PATH', dirname(__FILE__));
require WNAV_PATH . '/WNav_Widget.php';
add_action('wp_enqueue_scripts', 'wnav_scripts');
function wnav_scripts() {
    wp_enqueue_style('wnav-style', WNAV_URL . '/css/wnav.css', array(), WNAV_VERSION_NUM, 'all');
    wp_enqueue_script('wnav-script', WNAV_URL . '/js/wnav.js',  array('jquery') , WNAV_VERSION_NUM ,true);
}
?>
