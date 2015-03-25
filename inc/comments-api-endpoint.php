<?php
/**
 * Comment API.
 *
 * comments endpoint.
 *
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;


/**
 * wds_api_comments_init function.
 * 
 * @access public
 * @return void
 */
function wds_api_comments_init() {

    $wds_api_comments = new WDS_API_COMMENTS();
    add_filter( 'json_endpoints', array( $wds_api_comments, 'register_routes' ) );
}
add_action( 'wp_json_server_before_serve', 'wds_api_comments_init' );


/**
 * WDS_API_COMMENTS class.
 */
class WDS_API_COMMENTS{


    /**
     * register_routes function.
     * 
     * @access public
     * @param mixed $routes
     * @return void
     */
    public function register_routes( $routes ) {
    
        $routes['/comments'] = array(
            array( array( $this, 'get_items'), WP_JSON_Server::READABLE ),
        );

        return $routes;
    }
    
    
    /**
     * get_items function.
     * 
     * @access public
     * @return json
     */
    public function get_items() {

		$comments = get_comments( apply_filters( 'widget_comments_args', array(
			'number'      => 5,
			'status'      => 'approve',
			'post_status' => 'publish'
		) ) );
    	
	    return wp_send_json( $comments );
    }
      
    
}