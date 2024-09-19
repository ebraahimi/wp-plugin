<?php
/*
 * Plugin Name:       Article Vote System
 * Plugin URI:        ---This is Demo
 * Description:       This is very simple vote poll 
 * Version:           1.0.0
 * Author:            Sara Ebrahimi
 * Author URI:        https://ebraahimi.net/en/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        ---
 * Text Domain:       ---
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load plugin class files.
require_once 'includes/article-vote-system.php';
require_once 'includes/class-article-vote-system-settings.php';

// Load plugin libraries.
// require_once 'includes/lib/class-article-vote-system-admin-api.php';
require_once 'includes/lib/article-vote-system-post-type.php';
// require_once 'includes/lib/class-article-vote-system-taxonomy.php';

/**
 * Returns the main instance of article-vote-system to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object Article_Vote_System
 */
function Article_Vote_System() {
	$instance = Article_Vote_System::instance( __FILE__, '1.0.0' );

	// if ( is_null( $instance->settings ) ) {
	// 	$instance->settings = Article_Vote_System_Settings::instance( $instance );
	// }

	return $instance;
}

Article_Vote_System();