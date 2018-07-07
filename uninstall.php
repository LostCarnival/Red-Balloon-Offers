<?php
/**
 * RedBalloonOffers uninstall actions
 *
 * @package RedBalloonOffers
 */

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}

global $wpdb;
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'hotel'" );
$wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'offer'" );
$wpdb->query( "DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts)" );