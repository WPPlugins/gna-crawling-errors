<?php
if (!class_exists('GNA_Crawler')) {
	set_time_limit(10000);
	include_once(plugin_dir_path(__FILE__).'../libs/PHPCrawler.class.php');
	
	class GNA_Crawler extends PHPCrawler {
		public function handleDocumentInfo(PHPCrawlerDocumentInfo $DocInfo) {
			/*
			// Just detect linebreak for output ("\n" in CLI-mode, otherwise "<br>").
			if (PHP_SAPI == "cli") $lb = "\n";
			else $lb = "<br />";

			// Print the URL and the HTTP-status-Code
			echo "Page requested: ".$DocInfo->url." (".$DocInfo->http_status_code.")".$lb;

			// Print the refering URL
			echo "Referer-page: ".$DocInfo->referer_url.$lb;

			// Print if the content of the document was be recieved or not
			if ($DocInfo->received == true)
			echo "Content received: ".$DocInfo->bytes_received." bytes".$lb;
			else
			echo "Content not received".$lb;

			// Now you should do something with the content of the actual
			// received page or file ($DocInfo->source), we skip it in this example

			echo $lb;
			*/
			
			echo '<tr>';
			echo '<td>'.$DocInfo->http_status_code.'</td>';
			echo '<td>'.$DocInfo->url.'</td>';
			echo '<td>'.$DocInfo->referer_url.'</td>';
			if ($DocInfo->received == true) {
				echo '<td>'.$DocInfo->bytes_received.'</td>';
			} else {
				echo '<td>0</td>';
			}
			echo '</tr>';

			flush(); 
		}
	}
}
