<?php
/**
 * Main plugin class file.
 *
 * @package Vote-Me/Includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main plugin class.
 */
class Article_Vote_System {

	/**
	 * The single instance of Article_Vote_System.
	 *
	 * @var     object
	 * @access  private
	 * @since   1.0.0
	 */
	private static $_instance = null; //phpcs:ignore

	/**
	 * Settings class object
	 *
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $settings = null;


	/**
	 * The main plugin file.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $file;

	/**
	 * The main plugin directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $dir;

	/**
	 * The plugin assets directory.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_dir;

	/**
	 * The plugin assets URL.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $assets_url;

	/**
	 * Suffix for JavaScripts.
	 *
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $script_suffix;

	/**
	 * Constructor funtion.
	 *
	 * @param string $file File constructor.
	 * @param string $version Plugin version.
	 */
	public function __construct( $file = '', $version = '1.0.0' ) {
		$this->_version = $version;
		$this->_token   = 'article-vote-system';

		// Load plugin environment variables.
		$this->file       = $file;
		$this->dir        = dirname( $this->file );
		$this->assets_dir = trailingslashit( $this->dir ) . 'assets';
		$this->assets_url = esc_url( trailingslashit( plugins_url( '/assets/', $this->file ) ) );

		$this->script_suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		register_activation_hook( $this->file, array( $this, 'install' ) );

		// Load frontend JS & CSS.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ), 10 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 10 );

		//create next to side bar 
		add_action('admin_menu', array($this, 'add_plugin_menu'));
        // flush_rewrite_rules();

	} // End __construct ()

	/**
	 * Register post type function.
	 *
	 * @param string $post_type Post Type.
	 * @param string $plural Plural Label.
	 * @param string $single Single Label.
	 * @param string $description Description.
	 * @param array  $options Options array.
	 *
	 * @return bool|string|WordPress_Plugin_Template_Post_Type
	 */
	public function register_post_type( $post_type = '', $plural = '', $single = '', $description = '', $options = array() ) {

		if ( ! $post_type || ! $plural || ! $single ) {
			return false;
		}

		$post_type = new WordPress_Plugin_Template_Post_Type( $post_type, $plural, $single, $description, $options );

		return $post_type;
	}

	/**
	 * Load frontend CSS.
	 *
	 * @access  public
	 * @return void
	 * @since   1.0.0
	 */
	public function enqueue_styles() {
		wp_register_style( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'css/frontend.css', array(), $this->_version );
		wp_enqueue_style( $this->_token . '-frontend' );
	} // End enqueue_styles ()

	/**
	 * Load frontend Javascript.
	 *
	 * @access  public
	 * @return  void
	 * @since   1.0.0
	 */
	public function enqueue_scripts() {
		wp_register_script( $this->_token . '-frontend', esc_url( $this->assets_url ) . 'js/frontend' . $this->script_suffix . '.js', array( 'jquery' ), $this->_version, true );
		wp_enqueue_script( $this->_token . '-frontend' );
	} // End enqueue_scripts ()

	/**
	 * Main Article_Vote_System Instance
	 *
	 * Ensures only one Article_Vote_System is loaded or can be loaded.
	 *
	 * @param string $file File instance.
	 * @param string $version Version parameter.
	 *
	 * @return Object Article_Vote_System instance
	 * @see Article_Vote_System()
	 * @since 1.0.0
	 * @static
	 */
	public static function instance( $file = '', $version = '1.0.0' ) {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self( $file, $version );
		}
		return self::$_instance;
	}

	/**
	* Add a top-level menu item.
	*/ 
	public function add_plugin_menu() {
		// Add main pages under the top-level menu item
		add_menu_page(
			'Vote System',            // Page title
			'Vote System',          // Menu title
			'manage_options',       // Capability required to access the page
			'All-votes-settings',   // Menu slug
			array($this, 'vote_plugin_settings_page'), // Callback function to render the page
			'dashicons-smiley', // Icon URL or Dashicons class
			5 ,
		);
		// Add 1st subpages under the top-level menu item
		add_submenu_page(
			'All-votes-settings',   // Parent menu slug
			'All Votes',            // Page title
			'All Votes',            // Menu title
			'manage_options',       // Capability required to access the page
			'All-votes-settings',  // Menu slug
			array($this, 'vote_plugin_settings_page') // Callback function to render the page
		);
		// Add 2nd subpages under the top-level menu item
		add_submenu_page(
			'All-votes-settings',   // Parent menu slug
			'Add New',            // Page title
			'Add New',            // Menu title
			'manage_options',       // Capability required to access the page
			'vote-Add-new',  // Menu slug
			array($this, 'vote_plugin_subpage_1_content') // Callback function to render the page
		);
	}

	/**
	* plugin main page content goes here.
	*/ 
	public function vote_plugin_settings_page() {
		echo '<div style="display:flex; Flex-direction: row; align-items: center; justify-content:left;">';
		echo '<h1 style="margin-right: 10px;">All Votes</h1>'.'&nbsp;&nbsp;&nbsp';
		echo '<button type="button" class="button button-primary">Create new poll</button>';
		echo '</div> </br>';
		// column_default( , );
	}

	/**
	* plugin submain page content goes here.
	*/ 
	public function vote_plugin_subpage_1_content() {
		echo '<h1>Create Poll</h1>';
	}

}
