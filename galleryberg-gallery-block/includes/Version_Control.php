<?php
/**
 * Version control manager
 *
 * @package Galleryberg
 */

namespace Galleryberg;

use Exception;
use Plugin_Upgrader;
use WP_Error;

/**
 * Manager responsible for version related operations.
 */
class Version_Control {
	/**
	 * AJAX action name for version rollback.
	 */
	const VERSION_ROLLBACK_AJAX_ACTION = 'galleryberg_version_control';

	/**
	 * Plugin slug on WordPress.org
	 */
	const PLUGIN_SLUG = 'galleryberg-gallery-block';

	/**
	 * Plugin file path
	 */
	const PLUGIN_FILE = 'galleryberg-gallery-block/galleryberg-gallery-block.php';

	/**
	 * Initialize the version control manager.
	 */
	public static function init() {
		$instance = new self();
		$instance->init_process();
	}

	/**
	 * Main process that will be called during initialization of manager.
	 *
	 * @return void
	 */
	protected function init_process() {
		add_filter( 'galleryberg/filter/admin_settings_menu_data', array( $this, 'add_settings_menu_data' ) );
		add_action( 'wp_ajax_' . self::VERSION_ROLLBACK_AJAX_ACTION, array( $this, 'ajax_version_rollback' ) );
	}

	/**
	 * Handle ajax version rollback requests.
	 *
	 * @return void
	 */
	public function ajax_version_rollback() {
		if ( current_user_can( 'manage_options' ) && check_ajax_referer(
			self::VERSION_ROLLBACK_AJAX_ACTION,
			'nonce',
			false
		) ) {
			try {
				if ( ! isset( $_POST['version'] ) ) {
					throw new Exception( esc_html__( 'Invalid request body.', 'galleryberg-gallery-block' ) );
				}

				$target_version         = sanitize_text_field( wp_unslash( $_POST['version'] ) );
				$current_plugin_version = GALLERYBERG_BLOCKS_VERSION;

				if ( $target_version === $current_plugin_version ) {
					throw new Exception( esc_html__( 'You are on the same plugin version.', 'galleryberg-gallery-block' ) );
				}

				$available_versions = $this->get_plugin_versions_info();

				if ( ! in_array( $target_version, array_keys( $available_versions ), true ) ) {
					throw new Exception( esc_html__( 'Target version is out of bounds.', 'galleryberg-gallery-block' ) );
				}

				require_once ABSPATH . 'wp-admin/includes/file.php';
				require_once ABSPATH . 'wp-admin/includes/misc.php';
				require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
				require_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
				require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

				$download_url = $available_versions[ $target_version ];

				// Use output buffering to prevent any HTML output.
				ob_start();

				// Silent upgrader skin that suppresses all output.
				$skin     = new \WP_Ajax_Upgrader_Skin();
				$upgrader = new Plugin_Upgrader( $skin );

				add_filter(
					'upgrader_package_options',
					function ( $options ) use ( $target_version ) {
						$options['abort_if_destination_exists'] = false;
						$options['hook_extra']                   = array_merge(
							$options['hook_extra'],
							array(
								'plugin'  => self::PLUGIN_FILE,
								'version' => $target_version,
							)
						);
						return $options;
					}
				);

				$install_result = $upgrader->install( $download_url, array( 'overwrite_package' => true ) );

				// Clean and discard any output.
				ob_end_clean();

				if ( is_wp_error( $install_result ) || false === $install_result ) {
					throw new Exception( esc_html__( 'An error occurred during rollback operation.', 'galleryberg-gallery-block' ) );
				}

				activate_plugin( self::PLUGIN_FILE );				wp_send_json_success(
					array(
						'message' => sprintf(
							/* translators: %s: version number */
							esc_html__( 'Plugin version %s installed successfully.', 'galleryberg-gallery-block' ),
							esc_html( $target_version )
						),
					)
				);
			} catch ( Exception $e ) {
				wp_send_json_error(
					array(
						'error' => $e->getMessage(),
					)
				);
			}
		} else {
			wp_send_json_error(
				array(
					'error' => esc_html__( 'You are not authorized to use this ajax endpoint.', 'galleryberg-gallery-block' ),
				)
			);
		}
	}

	/**
	 * Get plugin versions info.
	 *
	 * @return array versions info
	 */
	private function get_plugin_versions_info() {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$plugin_remote_info = plugins_api(
			'plugin_information',
			array(
				'slug' => self::PLUGIN_SLUG,
			)
		);

		if ( is_wp_error( $plugin_remote_info ) ) {
			return array();
		}

		if ( ! isset( $plugin_remote_info->versions ) ) {
			return array();
		}

		if ( null === $plugin_remote_info->versions ) {
			return array();
		}

		$all_versions = $plugin_remote_info->versions;
		unset( $all_versions['trunk'] );

		// Return last 5 versions.
		return array_slice( array_reverse( $all_versions, true ), 0, 5, true );
	}

	/**
	 * Add version control related data to settings menu frontend.
	 *
	 * @param array $data data.
	 *
	 * @return array filtered data
	 */
	public function add_settings_menu_data( $data ) {
		$current_version = GALLERYBERG_BLOCKS_VERSION;
		$versions        = array_keys( $this->get_plugin_versions_info() );

		$data['versionControl'] = array(
			'currentVersion' => $current_version,
			'versions'       => $versions,
			'ajax'           => array(
				'versionRollback' => array(
					'url'    => admin_url( 'admin-ajax.php' ),
					'action' => self::VERSION_ROLLBACK_AJAX_ACTION,
					'nonce'  => wp_create_nonce( self::VERSION_ROLLBACK_AJAX_ACTION ),
				),
			),
		);

		return $data;
	}
}
