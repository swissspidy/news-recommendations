<?php
/**
 * Plugin Name: News Recommendations
 * Plugin URI:  https://github.com/swissspidy/news-recommendations/
 * Description: Maintain a list of recommended links.
 * Version:     1.0.0
 * Author:      Pascal Birchler
 * Author URI:  https://pascalbirchler.com
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: news-recommendations
 * Domain Path: /languages
 *
 * @package NewsRecommendations
 */

namespace NewsRecommendations;

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

add_action( 'plugins_loaded', __NAMESPACE__ . '\bootstrap' );
