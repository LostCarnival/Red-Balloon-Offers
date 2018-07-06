<?php
/**
 * RedBalloonOffers custom fields
 *
 * @package RedBalloonOffers
 */

if ( !class_exists( 'OffersCustomFields' ) ) {

	class OffersCustomFields
	{
		var $prefix = '_ocf_';
		var $customFileds = array(
			array(
				'name'        => 'cost',
				'title'       => 'Cost',
				'description' => '',
				'type'        => 'text',
				'scope'       => array( 'post' ),
				'capability'  => 'edit_posts'
			)
		);

		function __construct() {
			add_action( 'admin_menu', array( $this, 'create_custom_fields' ) );
			add_action( 'save_post', array( $this, 'save_custom_fields' ) );
		}

		function create_custom_fields() {
			if ( function_exists( 'add_meta_box' ) ) {
				add_meta_box( 'offer-custom-fields', 'Offer Fields', array( $this, 'display_custom_fields' ), 'offer', 'normal' );
			}
		}

		function display_custom_fields() {
			global $post;
			echo '<div class="form-wrap">';
			echo '</div>';
		}
	}

}