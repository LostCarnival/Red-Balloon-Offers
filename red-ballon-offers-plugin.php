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

if ( !class_exists( 'RedBalloonOffers' ) ) {

	class RedBalloonOffers
	{
		function register() {
			add_action( 'init', array( $this, 'create_hotels_post_type' ) );
			add_action( 'init', array( $this, 'create_offers_post_type' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
		}

		function activate() {
			$this->create_hotels_post_type();
			$this->create_offers_post_type();
			flush_rewrite_rules();
		}

		function deactivate() {
			flush_rewrite_rules();
		}

		function create_hotels_post_type() {
			$labels = array(
				'add_new'      => 'Add New Hotel',
				'add_new_item' => 'New Hotel Name',
				'edit_item'    => 'Edit Hotel Name'
			);
			$args = array(
				'label'    => 'Hotels',
				'labels'   => $labels,
				'public'   => true,
				'supports' => array(
					'title',
					'editor',
					'thumbnail'
				)
			);
			register_post_type( 'hotel', $args );
		}

		function create_offers_post_type() {
			$labels = array(
				'add_new'      => 'Add New Offer',
				'add_new_item' => 'New Room Name',
				'edit_item'    => 'Edit Room Name'
			);
			$args = array(
				'label'    => 'Offers',
				'labels'   => $labels,
				'public'   => true,
				'supports' => array(
					'title',
					'editor'
				)
			);
			register_post_type( 'offer', $args );
		}

		function enqueue() {
			wp_enqueue_style( 'red-balloon-offers-style', plugins_url( '/assets/style.css', __FILE__ ) );
			wp_enqueue_script( 'red-balloon-offers-script', plugins_url( '/assets/script.css', __FILE__ ) );
		}
	}

}

require_once plugin_dir_path( __FILE__ ) . 'offers-custom-fields.php';

if ( class_exists( 'RedBalloonOffers' ) ) {
	$redBalloonOffers = new RedBalloonOffers();
	$redBalloonOffers->register();
}

register_activation_hook( __FILE__, array( $redBalloonOffers, 'activate' ) );
register_deactivation_hook( __FILE__, array( $redBalloonOffers, 'deactivate' ) );