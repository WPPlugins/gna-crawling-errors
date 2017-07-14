<?php
if (!class_exists('GNA_CrawlingErrors_Admin_Init')) {
	class GNA_CrawlingErrors_Admin_Init {
		var $main_menu_page;
		var $settings_menu;

		public function __construct() {
			$this->admin_includes();
			add_action('admin_menu', array(&$this, 'create_admin_menus'));

			if ( isset($_GET['page']) && (strpos($_GET['page'], GNA_CRAWLING_ERRORS_MENU_SLUG_PREFIX ) !== false) ) {
				add_action('admin_print_scripts', array(&$this, 'admin_menu_page_scripts'));
				add_action('admin_print_styles', array(&$this, 'admin_menu_page_styles'));
			}
		}

		public function admin_menu_page_scripts() {
			wp_enqueue_script('jquery');
			//wp_enqueue_script('postbox');
			//wp_enqueue_script('dashboard');
			//wp_enqueue_script('thickbox');
			wp_enqueue_script('gna-ce-script', GNA_CRAWLING_ERRORS_URL. '/assets/js/gna-crawling-errors.js', array(), GNA_CRAWLING_ERRORS_VERSION);
		}

		function admin_menu_page_styles() {
			//wp_enqueue_style('dashboard');
			//wp_enqueue_style('thickbox');
			//wp_enqueue_style('global');
			//wp_enqueue_style('wp-admin');
			wp_enqueue_style('gna-crawling-errors-admin-css', GNA_CRAWLING_ERRORS_URL. '/assets/css/gna-crawling-errors.css');
		}

		public function admin_includes() {
			include_once('gna-crawling-errors-admin-menu.php');
		}

		public function create_admin_menus() {
			$this->main_menu_page = add_menu_page( __('Crawling Errors', 'gna-crawling-errors'), __('Crawling Errors', 'gna-crawling-errors'), 'manage_options', 'gna-ce-settings-menu', array(&$this, 'handle_settings_menu_rendering'), GNA_CRAWLING_ERRORS_URL . '/assets/images/gna_20x20.png' );

			add_submenu_page('gna-ce-settings-menu', __('Settings', 'gna-crawling-errors'),  __('Settings', 'gna-crawling-errors'), 'manage_options', 'gna-ce-settings-menu', array(&$this, 'handle_settings_menu_rendering'));
			
			add_submenu_page('gna-ce-settings-menu', __('Checking', 'gna-crawling-errors'),  __('Checking', 'gna-crawling-errors'), 'manage_options', 'gna-ce-checking-menu', array(&$this, 'handle_checking_menu_rendering'));

			add_action( 'admin_init', array(&$this, 'register_gna_crawling_errors_settings') );
		}

		public function register_gna_crawling_errors_settings() {
			register_setting( 'gna-crawling-errors-setting-group', 'g_crawling_errors_configs' );
		}

		public function handle_settings_menu_rendering() {
			include_once('gna-crawling-errors-admin-settings-menu.php');
			$this->settings_menu = new GNA_CrawlingErrors_Settings_Menu();
		}

		public function handle_checking_menu_rendering() {
			include_once('gna-crawling-errors-admin-checking-menu.php');
			$this->checking_menu = new GNA_CrawlingErrors_Checking_Menu();
		}
	}
}
