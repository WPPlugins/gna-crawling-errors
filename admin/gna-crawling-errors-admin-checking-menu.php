<?php
if (!class_exists('GNA_CrawlingErrors_Checking_Menu')) {
	class GNA_CrawlingErrors_Checking_Menu extends GNA_CrawlingErrors_Admin_Menu {
		var $menu_page_slug = 'gna-ce-checking-menu';

		/* Specify all the tabs of this menu in the following array */
		var $menu_tabs;

		var $menu_tabs_handler = array(
			'tab1' => 'render_tab1', 
			);

		public function __construct() {
			include_once(plugin_dir_path(__FILE__).'../inc/gna-crawler-class.php');

			$this->render_menu_page();
		}

		public function set_menu_tabs() {
			$this->menu_tabs = array(
				'tab1' => __('Check WebSite', 'gna-crawling-errors'),
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
			echo '<h2>'.__('Checking Website','gna-crawling-errors').'</h2>';//Interface title
			$this->set_menu_tabs();
			$tab = $this->get_current_tab();
			$this->render_menu_tabs();
			?>
			<div id="poststuff"><div id="post-body">
			<?php
				call_user_func(array(&$this, $this->menu_tabs_handler[$tab]));
			?>
			</div></div>
			</div><!-- end of wrap -->
			<?php
		}

		public function render_tab1() {
			global $g_crawlingerrors;
			?>
			<div class="postbox">
				<h3 class="hndle"><label for="title"><?php _e('Analize Website', 'gna-crawling-errors'); ?></label></h3>
				<div class="inside">
					<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
						<?php wp_nonce_field('n_gna-ce-analyze-site'); ?>
						<table class="form-table">
							<tr valign="top">
								<th scope="row"><?php _e('Website URL', 'gna-crawling-errors')?>:</th>
								<td>
									<div class="input_fields_wrap">
										<input type="text" id="gna_dest_url" name="gna_dest_url" class="regular-text" value="" />
									</div>
								</td>
							</tr>
						</table>
						<input type="submit" name="gna_ce_analyze_site" value="<?php _e('Start', 'gna-crawling-errors')?>" class="button button-primary" />
					</form>
				</div>
			</div>
			<?php
				if ( isset($_POST['gna_ce_analyze_site']) && isset($_POST['gna_dest_url']) ) {
			?>
				<div class="postbox">
					<h3 class="hndle"><label for="title"><?php _e('Analize Website', 'gna-crawling-errors'); ?></label></h3>
					<div class="inside">
					<?php
						$nonce = $_REQUEST['_wpnonce'];
						if ( !wp_verify_nonce($nonce, 'n_gna-ce-analyze-site') ) {
							die("Nonce check failed on save settings!");
						}

						$crawler = new GNA_Crawler();

						// URL to crawl
						$crawler->setURL($_POST['gna_dest_url']);

						// Only receive content of files with content-type "text/html"
						$crawler->addContentTypeReceiveRule("#text/html#");

						// Ignore links to pictures, dont even request pictures
						$crawler->addURLFilterRule("#\.(jpg|jpeg|gif|png)$# i");

						// Store and send cookie-data like a browser does
						$crawler->enableCookieHandling(true);

						// Set the traffic-limit to 1 MB (in bytes, 
						// for testing we dont want to "suck" the whole site)
						//$crawler->setTrafficLimit(1000 * 1024);

						echo '<table>';
						echo '<thead>';
						echo '<tr>';
						echo '<th>Status Code</th>';
						echo '<th>Page requested</th>';
						echo '<th>Referer-page</th>';
						echo '<th>Content received</th>';
						echo '</tr>';
						echo '</thead>';
						echo '<tbody>';
						
						// Thats enough, now here we go
						$crawler->go();

						// At the end, after the process is finished, we print a short 
						// report (see method getProcessReport() for more information)
						$report = $crawler->getProcessReport();

						if (PHP_SAPI == "cli") $lb = "\n";
						else $lb = "<br />";

						echo '</tbody>';
						echo '</table>';
						
						echo "Summary:".$lb;
						echo "Links followed: ".$report->links_followed.$lb;
						echo "Documents received: ".$report->files_received.$lb;
						echo "Bytes received: ".$report->bytes_received." bytes".$lb;
						echo "Process runtime: ".$report->process_runtime." sec".$lb;

						$this->show_msg_updated('Analyzing is done.');
					?>
					</div>
				</div>
			<?php
				}
		}
	} //end class
}
