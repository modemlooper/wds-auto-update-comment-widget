<?php
/**
 * Plugin Name: WebDevStudios Auto Update Comment Widget
 * Plugin URI: http://webdevstudios.com
 * Description: Adds a widget that uses WP-API to auto update with latest comments.
 * Author: WebDevStudios
 * Author URI: http://webdevstudios.com
 * Version: 1.0.0
 * License: GPLv2
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;


if ( ! class_exists( 'WDS_AutoUpdate_Comment_Widget' ) ) {

	class WDS_AutoUpdate_Comment_Widget {
	
		/**
		 * instance function.
		 * 
		 * @access public
		 * @static
		 * @return void
		 */
		public static function instance() {
	
			// Store the instance locally to avoid private static replication
			static $instance = null;
	
			// Only run these methods if they haven't been run previously
			if ( null === $instance ) {
				$instance = new WDS_AutoUpdate_Comment_Widget;
				$instance->constants();
				$instance->includes();
			}
	
			// Always return the instance
			return $instance;
		}
		
		
		
		/**
		 * __construct function.
		 * 
		 * @access private
		 * @return void
		 */
		private function __construct() { /* Do nothing here */ }
		
		
		
		/**
		 * constants function.
		 * 
		 * @access private
		 * @return void
		 */
		private function constants() {
		
			// Path and URL
			if ( ! defined( 'WDS_COMMENT_WIDGET_PLUGIN_DIR' ) ) {
				define( 'WDS_COMMENT_WIDGET_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}
	
			if ( ! defined( 'WDS_COMMENT_WIDGET_PLUGIN_URL' ) ) {
				define( 'WDS_COMMENT_WIDGET_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}
			
			if ( ! defined( 'WDS_COMMENT_WIDGET_VERSION' ) ) {
				define( 'WDS_COMMENT_WIDGET_VERSION', '1.0.0' );
			}
		
		}
		
		
		
		/**
		 * setup_globals function.
		 * 
		 * @access private
		 * @return void
		 */
		private function setup_globals() {
		
			$this->plugin_dir = trailingslashit( constant( 'WDS_COMMENT_WIDGET_PLUGIN_DIR' ) );
			$this->plugin_url = trailingslashit( constant( 'WDS_COMMENT_WIDGET_PLUGIN_URL' ) );
		
		}
		
		
		
		/**
		 * includes function.
		 * 
		 * @access private
		 * @return void
		 */
		private function includes() {
		
			require( $this->plugin_dir . 'inc/widget.php' );
			require( $this->plugin_dir . 'inc/comments-api-endpoint.php' );
		
		}
		
	
	}
	
}

function wds_comment_widget() {
	return WDS_AutoUpdate_Comment_Widget::instance();
}

$GLOBALS['wds_comment_widget'] = wds_comment_widget();