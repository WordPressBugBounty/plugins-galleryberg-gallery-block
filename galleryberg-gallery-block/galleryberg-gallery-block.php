<?php
/**
 * Plugin Name:       Galleryberg Gallery Block
 * Description:       A customizable gallery block for displaying images in columns with optional cropping and spacing.
 * Version:           1.1.3
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Imtiaz Rayhan
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       galleryberg-gallery-block
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! defined( 'GALLERYBERG_BLOCKS_VERSION' ) ) {
	define( 'GALLERYBERG_BLOCKS_VERSION', '1.1.3' );
}

if ( ! defined( 'GALLERYBERG_BLOCKS_DIR_PATH' ) ) {
	define( 'GALLERYBERG_BLOCKS_DIR_PATH', \plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'GALLERYBERG_BLOCKS_PLUGIN_URL' ) ) {
	define( 'GALLERYBERG_BLOCKS_PLUGIN_URL', \plugins_url( '/', __FILE__ ) );
}

// Load Composer autoloader
if ( file_exists( GALLERYBERG_BLOCKS_DIR_PATH . 'vendor/autoload.php' ) ) {
	require_once GALLERYBERG_BLOCKS_DIR_PATH . 'vendor/autoload.php';
}

if ( ! function_exists( 'gal_fs' ) ) {
    // Create a helper function for easy SDK access.
    function gal_fs() {
        global $gal_fs;

        if ( ! isset( $gal_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';

            $gal_fs = fs_dynamic_init( array(
                'id'                  => '21743',
                'slug'                => 'galleryberg-gallery-block',
                'premium_slug'        => 'galleryberg-gallery-block-pro',
                'type'                => 'plugin',
                'public_key'          => 'pk_76372b4a6fdc50cc1d28d1ee6efc8',
                'is_premium'          => false,
                'has_addons'          => true,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'first-path'     => 'admin.php?page=galleryberg-settings&route=welcome',
                ),
            ) );
        }

        return $gal_fs;
    }

    // Init Freemius.
    gal_fs();
    // Signal that SDK was initiated.
    do_action( 'gal_fs_loaded' );
}

// Initialize admin settings page
if ( is_admin() ) {
	require_once GALLERYBERG_BLOCKS_DIR_PATH . 'includes/Version_Control.php';
	require_once GALLERYBERG_BLOCKS_DIR_PATH . 'includes/Admin/Galleryberg_Admin.php';
	new \Galleryberg\Admin\Galleryberg_Admin();
}

function galleryberg_gallery_block_init() {
	/**
	 * Registers the block type(s) in the `blocks-manifest.php` file.
	 *
	 * @see https://developer.wordpress.org/reference/functions/register_block_type/
	 */
	$manifest_data = require __DIR__ . '/build/blocks-manifest.php';
	foreach ( array_keys( $manifest_data ) as $block_type ) {
		register_block_type( __DIR__ . "/build/{$block_type}" );
	}
}
add_action( 'init', 'galleryberg_gallery_block_init' );
add_action( 'wp_enqueue_scripts', function() {
	wp_register_style(
		'galleryberg-lightbox',
		GALLERYBERG_BLOCKS_PLUGIN_URL . 'assets/lightbox.min.css',
		array(),
		defined('GALLERYBERG_BLOCKS_VERSION') ? GALLERYBERG_BLOCKS_VERSION : uniqid()
	);
	wp_register_script(
		'galleryberg-lightbox',
		GALLERYBERG_BLOCKS_PLUGIN_URL . 'assets/lightbox.min.js',
		array(),
		defined('GALLERYBERG_BLOCKS_VERSION') ? GALLERYBERG_BLOCKS_VERSION : uniqid(),
		true
	);
});
