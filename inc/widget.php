<?php

/**
 * This class from WDS Widget Boilerplate https://github.com/WebDevStudios/WDS-Widget-Boilerplate
 */


// Exit if accessed directly
if ( ! defined ( 'ABSPATH' ) ) {
	exit;
}

class WDS_Widget_Comment_Autoupdate extends WP_Widget {


	/**
	 * Unique identifier for this widget.
	 *
	 * Will also serve as the widget class.
	 *
	 * @var string
	 */
	protected $widget_slug = 'wds-widget-comment-auto-update';


	/**
	 * Widget name displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 */
	protected $widget_name = '';


	/**
	 * Default widget title displayed in Widgets dashboard.
	 * Set in __construct since __() shouldn't take a variable.
	 *
	 * @var string
	 */
	protected $default_widget_title = '';


	/**
	 * Shortcode name for this widget
	 *
	 * @var string
	 */
	protected $shortcode = 'wds_widget_comment_auto_update';


	/**
	 * Contruct widget.
	 */
	public function __construct() {

		$this->widget_name          = esc_html__( 'Auto Updating Comments', 'wds-comment-autoupdate' );
		$this->default_widget_title = esc_html__( 'Live Comments', 'wds-comment-autoupdate' );

		parent::__construct(
			$this->widget_slug,
			$this->widget_name,
			array(
				'classname'   => $this->widget_slug,
				'description' => esc_html__( 'A list of recent comments auto updated.', 'wds-comment-autoupdate' ),
			)
		);

		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );
		add_action( 'wp_head',  	array( $this, 'apiurl' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );
		add_shortcode( $this->shortcode, array( __CLASS__, 'get_widget' ) );
	}


	/**
	 * Delete this widget's cache.
	 *
	 * Note: Could also delete any transients
	 * delete_transient( 'some-transient-generated-by-this-widget' );
	 */
	public function flush_widget_cache() {
		wp_cache_delete( $this->widget_slug, 'widget' );
	}


	/**
	 * Front-end display of widget.
	 *
	 * @param  array  $args      The widget arguments set up when a sidebar is registered.
	 * @param  array  $instance  The widget settings as set by user.
	 */
	public function widget( $args, $instance ) {

		echo self::get_widget( array(
			'before_widget' => $args['before_widget'],
			'after_widget'  => $args['after_widget'],
			'before_title'  => $args['before_title'],
			'after_title'   => $args['after_title'],
			'title'         => $instance['title'],
		) );

	}


	/**
	 * Return the widget/shortcode output
	 *
	 * @param  array  $atts Array of widget/shortcode attributes/args
	 * @return string       Widget output
	 */
	public static function get_widget( $atts ) {
	
		$class = new WDS_Widget_Comment_Autoupdate();
		
		$widget = '';

		// Set up default values for attributes
		$atts = shortcode_atts(
			array(
				// Ensure variables
				'before_widget' => '',
				'after_widget'  => '',
				'before_title'  => '',
				'after_title'   => '',
				'title'         => '',
			),
			(array) $atts,
			'wds_widget_comment_auto_update'
		);

		// Before widget hook
		$widget .= $atts['before_widget'];

		// Title
		$widget .= ( $atts['title'] ) ? $atts['before_title'] . esc_html( $atts['title'] ) . $atts['after_title'] : '';
		
		// wrapper for comments list
		$widget .= '<div id="wds-comment-widget"></div>';
		

		// After widget hook
		$widget .= $atts['after_widget'];

		return $widget;
	}


	/**
	 * Update form values as they are saved.
	 *
	 * @param  array  $new_instance  New settings for this instance as input by the user.
	 * @param  array  $old_instance  Old settings for this instance.
	 * @return array  Settings to save or bool false to cancel saving.
	 */
	public function update( $new_instance, $old_instance ) {

		// Previously saved values
		$instance = $old_instance;

		// Sanitize title before saving to database
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		// Flush cache
		$this->flush_widget_cache();

		return $instance;
	}


	/**
	 * Back-end widget form with defaults.
	 *
	 * @param  array  $instance  Current settings.
	 */
	public function form( $instance ) {

		// If there are no settings, set up defaults
		$instance = wp_parse_args( (array) $instance,
			array(
				'title' => $this->default_widget_title,
				'text'  => '',
			)
		);

		?>

		<p><label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'wds-comment-autoupdate' ); ?></label>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_html( $instance['title'] ); ?>" placeholder="optional" /></p>

		<?php
	}
	

	/**
	 * scripts function.
	 *
	 * only load our js file if our widget is added to sidebar
	 * 
	 * @access public
	 * @return void
	 */
	public function scripts() {
	
		$jsfile = WDS_COMMENT_WIDGET_PLUGIN_URL . 'inc/comment-widget.js';
		
		if ( is_active_widget( false, false, $this->widget_slug, true ) && ! is_admin()  ) {
				wp_enqueue_script( 'script-name', $jsfile, array(), WDS_COMMENT_WIDGET_VERSION, true );
		}
		
	}
	
	

	/**
	 * apiurl function.
	 *
	 * adds api url to head so we can us it in ajax 
	 * 
	 * @access public
	 * @return void
	 */
	public function apiurl() {
	?>
	<script type="text/javascript">
	var apiurl = '<?php echo trailingslashit( site_url('wp-json') ) ?>';
	</script>
	<?php
	
	}
	
	
	
}


/**
 * Register this widget with WordPress.
 */
function register_wds_widget_comment_update() {
	register_widget( 'WDS_Widget_Comment_Autoupdate' );
}
add_action( 'widgets_init', 'register_wds_widget_comment_update' );
