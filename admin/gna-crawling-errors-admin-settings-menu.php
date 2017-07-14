<?php
if (!class_exists('GNA_CrawlingErrors_Settings_Menu')) {
	class GNA_CrawlingErrors_Settings_Menu extends GNA_CrawlingErrors_Admin_Menu {
		var $menu_page_slug = 'gna-ce-settings-menu';

		/* Specify all the tabs of this menu in the following array */
		var $menu_tabs;

		var $menu_tabs_handler = array(
			'tab1' => 'render_tab1', 
			);

		public function __construct() {
			$this->render_menu_page();
		}

		public function set_menu_tabs() {
			$this->menu_tabs = array(
				'tab1' => __('General Settings', 'gna-crawling-errors'),
			);
		}

		public function get_current_tab() {
			$tab_keys = array_keys($this->menu_tabs);
			$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $tab_keys[0];
			return $tab;
		}

		/*
		 * Renders our tabs of this menu as nav items
		 */
		public function render_menu_tabs() {
			$current_tab = $this->get_current_tab();

			echo '<h2 class="nav-tab-wrapper">';
			foreach ( $this->menu_tabs as $tab_key => $tab_caption ) 
			{
				$active = $current_tab == $tab_key ? 'nav-tab-active' : '';
				echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->menu_page_slug . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';
			}
			echo '</h2>';
		}

		/*
		 * The menu rendering goes here
		 */
		public function render_menu_page() {
			echo '<div class="wrap">';
			echo '<h2>'.__('Settings','gna-crawling-errors').'</h2>';//Interface title
			$this->set_menu_tabs();
			$tab = $this->get_current_tab();
			$this->render_menu_tabs();
			?>
			<div id="poststuff"><div id="post-body">
			<?php 
				//$tab_keys = array_keys($this->menu_tabs);
				call_user_func(array(&$this, $this->menu_tabs_handler[$tab]));
			?>
			</div></div>
			</div><!-- end of wrap -->
			<?php
		}

		public function render_tab1() {
			global $g_crawlingerrors;
			if ( isset($_POST['gna_ce_save_settings']) ) {
				$nonce = $_REQUEST['_wpnonce'];
				if ( !wp_verify_nonce($nonce, 'n_gna-ce-save-settings') ) {
					die("Nonce check failed on save settings!");
				}

				//$g_crawlingerrors->configs->set_value('g_analytics_ua_id', isset($_POST["g_analytics_ua_id"]) ? $_POST["g_analytics_ua_id"] : '');
				$g_crawlingerrors->configs->save_config();
				$this->show_msg_settings_updated();
			}

			?>
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('GNA Crawling Errors', 'gna-crawling-errors'); ?></label></h3>
				<div class="inside">
					<p><?php _e('Thank you for using our GNA Crawling Errors plugin.', 'gna-crawling-errors'); ?></p>
				</div>
			</div> <!-- end postbox-->
			<!--
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('Web Property ID', 'gna-crawling-errors'); ?></label></h3>
				<div class="inside">
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<?php wp_nonce_field('n_gna-ce-save-settings'); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('UA ID', 'gna-crawling-errors')?>:</th>
								<td>
									<div class="input_fields_wrap">
									</div>
								</td>
							</tr>
						</table>
						<input type="submit" name="gna_ce_save_settings" value="<?php _e('Save Settings', 'gna-crawling-errors')?>" class="button" />
					</form>
				</div>
			</div>-->
			<?php
		}
	} //end class
}
