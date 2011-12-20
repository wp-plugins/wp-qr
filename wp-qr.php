<?php
/*
Plugin Name: QR wordPress plugin
Plugin URI: http://ilovejava.net/qr-wordpress-plugin/
Description: 获取日志的 QR 二维码
Version: 1.0
Author: 小辛
Author URI: http://ilovejava.net/
License: GNU General Public License 2.0
*/

/*  Copyright 2011  小辛  (email : myandroid@qq.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function get_qr(){

	include "phpqrcode/qrlib.php";
	$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
	$PNG_WEB_DIR = 'temp/';

	if (!file_exists($PNG_TEMP_DIR))
        mkdir($PNG_TEMP_DIR);
	$value = get_permalink($post_ID);
	$errorCorrectionLevel = 'L';
	$matrixPointSize = 4;   
	$filename = $PNG_TEMP_DIR.'test.png';
	$filename = $PNG_TEMP_DIR.'test'.md5($value.'|'.$errorCorrectionLevel.'|'.$matrixPointSize).'.png';

	QRcode::png($value, $filename, $errorCorrectionLevel, $matrixPointSize, 2); 
	$addr = $PNG_WEB_DIR.basename($filename);
	return $addr;
}

add_action('publish_post', 'publish_post_2_microblog', 0);
function publish_post_2_microblog($post_ID){
	get_qr($post_ID);
}

add_filter('the_content','qr_content');
function qr_content($text){
	if(is_single()||is_page()||is_feed()) {
		global $post;
		$addr = get_qr($post->ID);
		$wp_host_url = get_site_url();
		$text = $text . '<hr/><div class="qr">这篇日志的 QR 二维码为：<img src="'.$wp_host_url.'/wp-content/plugins/wp-qr/'.$addr.'" /></div>';
	}
	return $text;	
}

?>