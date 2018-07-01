<?php
/**
 * @package RedBalloonOffers
 */
/*
Plugin Name: Red Balloon Offers
Plugin URI: https://github.com/LostCarnival/Red-Balloon-Offers
Description: Hotel offers plugin.
Version: 1.0.0
Author: Victor Dembitsky
Author URI: https://www.linkedin.com/in/victor-dembitsky-668527116/
License: GPLv2 or later
Text Domain: red-balloon-offers
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Copyright 2005-2015 Automattic, Inc.
*/

if ( !defined( 'ABSPATH' ) ) {
	die;
}

class RedBalloonOffers
{
	function __construct() {
		add_action( 'init', array( $this, 'red_balloon_offers_post_type' ) );
	}

	function activate() {
		$this->red_balloon_offers_post_type();
		flush_rewrite_rules();
	}

	function deactivate() {
		flush_rewrite_rules();
	}

	function red_balloon_offers_post_type() {
		register_post_type( 'offer', ['public' => true, 'label' => 'Offers'] );
	}
}

if ( class_exists( 'RedBalloonOffers' ) ) {
	$redBalloonOffers = new RedBalloonOffers();
}

register_activation_hook( __FILE__, array( $redBalloonOffers, 'activate' ) );

register_deactivation_hook( __FILE__, array( $redBalloonOffers, 'deactivate' ) );