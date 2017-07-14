<?php
/*
Plugin Name: GNA Crawling Errors
Version: 0.9.3
Plugin URI: http://wordpress.org/plugins/gna-crawling-errors/
Author: Chris Dev
Author URI: http://webgna.com/
Description: Easy to check crawling errors of website and export the information to CSV type file
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: gna-crawling-errors
*/

if(!defined('ABSPATH'))exit; //Exit if accessed directly

include_once('gna-crawling-errors-core.php');

register_activation_hook(__FILE__, array('GNA_CrawlingErrors', 'activate_handler'));		//activation hook
register_deactivation_hook(__FILE__, array('GNA_CrawlingErrors', 'deactivate_handler'));	//deactivation hook
