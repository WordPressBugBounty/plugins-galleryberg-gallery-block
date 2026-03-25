<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @package Galleryberg
 */

namespace Galleryberg\Admin;

/**
 * Manage Galleryberg Admin
 */
class Galleryberg_Admin {
	/**
	 * The ID of this plugin.
	 *
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * The PATH of this plugin.
	 *
	 * @access   private
	 * @var      string $plugin_path The PATH of this plugin.
	 */
	private $plugin_path;

	/**
	 * The URL of this plugin.
	 *
	 * @access   private
	 * @var      string $plugin_url The URL of this plugin.
	 */
	private $plugin_url;

	/**
	 * Initialize the class and set its properties.
	 */
	public function __construct() {
		$this->plugin_name = 'galleryberg-gallery-block';
		$this->version     = GALLERYBERG_BLOCKS_VERSION;
		$this->plugin_path = GALLERYBERG_BLOCKS_DIR_PATH;
		$this->plugin_url  = GALLERYBERG_BLOCKS_PLUGIN_URL;

		// Initialize version control manager.
		\Galleryberg\Version_Control::init();

		add_action( 'admin_menu', array( $this, 'register_admin_menus' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_script' ) );
	}

	/**
	 * Add data for admin settings menu frontend.
	 *
	 * @param array $data frontend data.
	 *
	 * @return array filtered frontend data
	 */
	public function add_settings_menu_data( $data ) {
		$data['assets'] = array(
			'logo' => trailingslashit( $this->plugin_url ) . 'includes/Admin/images/logos/menu-icon-colored.svg',
		);

		global $gp_fs;
		if ( isset( $gp_fs ) && function_exists( 'gp_fs' ) ) {
			$data['misc'] = array(
				'pro_status' => gp_fs()->is_premium() && gp_fs()->has_active_valid_license(),
			);
		} else {
			$data['misc'] = array(
				'pro_status' => false,
			);
		}

		$data['welcome'] = array(
			'title'   => __( 'Welcome to Galleryberg!', 'galleryberg-gallery-block' ),
			'content' => __( 'Build stunning, fast, and flexible galleries using the Gutenberg block editor - no shortcodes or clunky builder.', 'galleryberg-gallery-block' ),
		);

		$data['video'] = array(
			'title'     => __( 'Getting Started with Galleryberg', 'galleryberg-gallery-block' ),
			'video_id'  => 'zBrPTRmQBaU',
			'thumbnail' => 'https://img.youtube.com/vi/zBrPTRmQBaU/maxresdefault.jpg',
		);

		$data['documentation'] = array(
			'title'   => __( 'Documentation', 'galleryberg-gallery-block' ),
			'content' => __( 'Guides and tutorials to help you create beautiful galleries with ease.', 'galleryberg-gallery-block' ),
			'url'     => 'https://galleryberg.com/docs/',
			'button'  => __( 'View Docs', 'galleryberg-gallery-block' ),
		);

		$data['support'] = array(
			'title'   => __( 'Support', 'galleryberg-gallery-block' ),
			'content' => __( 'Have a question? We\'re here to help.', 'galleryberg-gallery-block' ),
			'url'     => 'https://galleryberg.com/contact/',
			'button'  => __( 'Contact Us', 'galleryberg-gallery-block' ),
		);

		$data['upgrade'] = array(
			'title'   => __( 'Upgrade to Galleryberg PRO!', 'galleryberg-gallery-block' ),
			'content' => __( 'Unlock premium layouts, advanced customization, and enhanced gallery controls.', 'galleryberg-gallery-block' ),
			'url'     => 'https://galleryberg.com/pricing/',
			'button'  => __( 'Get Galleryberg Pro', 'galleryberg-gallery-block' ),
		);

		$data['features'] = array(
			'title'       => __( 'Galleryberg Features', 'galleryberg-gallery-block' ),
			'description' => __( 'Explore the powerful features available in Galleryberg Gallery Block.', 'galleryberg-gallery-block' ),
			'list'        => array(
				array(
					'title'       => __( 'Gallery Layouts', 'galleryberg-gallery-block' ),
					'description' => __( 'Choose from Tiles, Masonry, Justified, and Square layouts for flexible, modern gallery designs.', 'galleryberg-gallery-block' ),
					'is_pro'      => false,
				),
				array(
					'title'       => __( 'Hover Effects', 'galleryberg-gallery-block' ),
					'description' => __( 'Add smooth Zoom In/Out hover effects for a more interactive browsing experience.', 'galleryberg-gallery-block' ),
					'is_pro'      => false,
				),
				array(
					'title'       => __( 'Lightbox Viewer', 'galleryberg-gallery-block' ),
					'description' => __( 'Show images in a fullscreen lightbox with animations, slide transitions, zoom, drag, and keyboard navigation.', 'galleryberg-gallery-block' ),
					'is_pro'      => false,
				),
				array(
					'title'       => __( 'Advanced Captions', 'galleryberg-gallery-block' ),
					'description' => __( 'Use caption styles like below image, overlay, or bar overlay, with full control over visibility and alignment.', 'galleryberg-gallery-block' ),
					'is_pro'      => false,
				),
				array(
					'title'       => __( 'Gallery Styles', 'galleryberg-gallery-block' ),
					'description' => __( 'Customize spacing, colors, borders, and radius to match your site\'s style.', 'galleryberg-gallery-block' ),
					'is_pro'      => false,
				),
				array(
					'title'       => __( 'Mosaic Layout (PRO)', 'galleryberg-gallery-block' ),
					'description' => __( 'Build mosaic galleries with varied image sizes for a premium visual look.', 'galleryberg-gallery-block' ),
					'is_pro'      => true,
				),
				array(
					'title'       => __( 'Lightbox Thumbnails (PRO)', 'galleryberg-gallery-block' ),
					'description' => __( 'Display clickable thumbnails inside the lightbox for quicker navigation.', 'galleryberg-gallery-block' ),
					'is_pro'      => true,
				),
				array(
					'title'       => __( 'More Coming Soon (PRO)', 'galleryberg-gallery-block' ),
					'description' => __( 'Get early access to upcoming premium layouts and advanced controls.', 'galleryberg-gallery-block' ),
					'is_pro'      => true,
				),
			),
			'cta'         => array(
				'title'   => __( 'Ready to unlock all features?', 'galleryberg-gallery-block' ),
				'content' => __( 'Upgrade to Galleryberg PRO and get access to mosaic layouts, manual span controls, and future premium features.', 'galleryberg-gallery-block' ),
				'url'     => 'https://galleryberg.com/pricing/',
				'button'  => __( 'Upgrade to PRO', 'galleryberg-gallery-block' ),
			),
		);

		return $data;
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function enqueue_admin_script() {
		// Only enqueue on our settings page
		$screen = get_current_screen();
		if ( ! $screen || strpos( $screen->id, 'galleryberg-settings' ) === false ) {
			return;
		}

		wp_enqueue_script(
			'galleryberg-admin-script',
			$this->plugin_url . 'includes/Admin/assets/galleryberg-admin.build.js',
			array(
				'lodash',
				'react',
				'wp-block-editor',
				'wp-blocks',
				'wp-components',
				'wp-data',
				'wp-element',
				'wp-i18n',
				'wp-primitives',
			),
			$this->version,
			true
		);

		$frontend_script_data = apply_filters( 'galleryberg/filter/admin_settings_menu_data', array() );
		$frontend_script_data = $this->add_settings_menu_data( $frontend_script_data );

		wp_localize_script( 'galleryberg-admin-script', 'gallerybergAdminMenuData', $frontend_script_data );

		wp_enqueue_style(
			'galleryberg-admin-style',
			$this->plugin_url . 'includes/Admin/assets/galleryberg-admin-style.css',
			array(),
			$this->version,
			'all'
		);
	}

	/**
	 * Set template for main setting page
	 *
	 * @return void
	 */
	public function main_menu_template_cb() {
		?>
		<div id="galleryberg-admin-menu"></div>
		<?php
	}

	/**
	 * Register Setting Pages for the admin area.
	 */
	public function register_admin_menus() {
		add_menu_page(
			__( 'Galleryberg Settings', 'galleryberg-gallery-block' ),
			__( 'Galleryberg', 'galleryberg-gallery-block' ),
			'manage_options',
			'galleryberg-settings',
			array( $this, 'main_menu_template_cb' ),
			plugin_dir_url( __FILE__ ) . 'images/logos/menu-icon.svg'
		);
	}
}
