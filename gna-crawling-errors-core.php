<?php
if (!class_exists('GNA_CrawlingErrors')) {
	class GNA_CrawlingErrors {
		var $plugin_url;
		var $admin_init;
		var $configs;

		public function init() {
			$class = __CLASS__;
			new $class;
		}

		public function __construct() {
			$this->load_configs();
			$this->define_constants();
			$this->define_variables();
			$this->includes();
			$this->loads();

			add_action('init', array(&$this, 'plugin_init'), 0);
			add_filter('plugin_row_meta', array(&$this, 'filter_plugin_meta'), 10, 2);
		}

		public function load_configs() {
			include_once('inc/gna-crawling-errors-config.php');
			$this->configs = GNA_CrawlingErrors_Config::get_instance();
		}

		public function define_constants() {
			define('GNA_CRAWLING_ERRORS_VERSION', '0.9.3');

			define('GNA_CRAWLING_ERRORS_BASENAME', plugin_basename(__FILE__));
			define('GNA_CRAWLING_ERRORS_URL', $this->plugin_url());

			define('GNA_CRAWLING_ERRORS_MENU_SLUG_PREFIX', 'gna-ce-settings-menu');
		}

		public function define_variables() {
		}

		public function includes() {
			if(is_admin()) {
				include_once('admin/gna-crawling-errors-admin-init.php');
			}
		}

		public function loads() {
			if(is_admin()){
				$this->admin_init = new GNA_CrawlingErrors_Admin_Init();
			}
		}

		public function plugin_init() {
			load_plugin_textdomain('gna-crawling-errors', false, dirname(plugin_basename(__FILE__ )) . '/languages/');
		}

		public function plugin_url() {
			if ($this->plugin_url) return $this->plugin_url;
			return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
		}

		public function filter_plugin_meta($links, $file) {
			if( strpos( GNA_CRAWLING_ERRORS_BASENAME, str_replace('.php', '', $file) ) !== false ) { /* After other links */
				$links[] = '<a target="_blank" href="https://profiles.wordpress.org/chris_dev/" rel="external">' . __('Developer\'s Profile', 'gna-crawling-errors') . '</a>';
			}

			return $links;
		}

		public function install() {
		}

		public function uninstall() {
		}

		public function activate_handler() {
		}

		public function deactivate_handler() {
		}
	}
}
$GLOBALS['g_crawlingerrors'] = new GNA_CrawlingErrors();
