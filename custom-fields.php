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
		var $customFields = array(
			array(
				'name'        => 'cost',
				'title'       => 'Cost',
				'description' => '',
				'type'        => 'text',
				'capability'  => 'edit_posts'
			)
		);

		function __construct() {
			add_action( 'admin_menu', array( $this, 'create_custom_fields' ) );
			add_action( 'save_post', array( $this, 'save_custom_fields' ), 1, 2 );
		}

		function create_custom_fields() {
			if ( function_exists( 'add_meta_box' ) ) {
				add_meta_box( 'offer-custom-fields', 'Offer Fields', array( $this, 'display_custom_fields' ), 'offer', 'normal' );
			}
		}

		function display_custom_fields() {
			global $post;
			wp_nonce_field( 'offer-custom-fields', 'offer-custom-fields_wpnonce', false );
			echo '<div class="form-wrap">';

			foreach ( $this->customFields as $customField ) {
				$output = false;
				if ( $post->post_type == 'offer' ) {
					$output = true;
				}
				if ( !current_user_can( $customField['capability'], $post->ID ) ) {
					$output = false;
				}

				if ( $output ) {
					echo '<div class="form-field">';
					switch ( $customField['type'] ) {
						case 'number':
						case 'date':
						case 'text': {
							echo '<label for="' . $this->prefix . $customField['name'] .'"><b>' . $customField['title'] . '</b></label>';
							echo '<input type="text" name="' . $this->prefix . $customField['name'] . '" id="' . $this->prefix . $customField['name'] . '"';
							echo ' value="' . htmlspecialchars( get_post_meta( $post->ID, $this->prefix . $customField['name'], true ) ) . '" />';
							break;
						}
					}
					if ( $customField['description'] ) {
						echo '<p>' . $customField['description'] . '</p>';
					}
					echo '</div>';
				}
			}

			echo '</div>';
		}

		function save_custom_fields( $post_id, $post ) {
			if ( !isset( $_POST['offer-custom-fields_wpnonce'] ) || !wp_verify_nonce( $_POST['offer-custom-fields_wpnonce'], 'offer-custom-fields' ) )
				return;
			if ( !current_user_can( 'edit_post', $post_id ) )
				return;
			if ( $post->post_type != 'offer' )
				return;

			foreach ( $this->customFields as $customField ) {
				if ( current_user_can( $customField['capability'], $post_id ) ) {
					if ( isset( $_POST[ $this->prefix . $customField['name'] ] ) && trim( $_POST[ $this->prefix . $customField['name'] ] ) ) {
						$value = $_POST[ $this->prefix . $customField['name'] ];
						update_post_meta( $post_id, $this->prefix . $customField['name'], $value );
					} else {
						delete_post_meta( $post_id, $this->prefix . $customField['name'] );
					}
				}
			}
		}
	}

}

if ( class_exists( 'OffersCustomFields' ) ) {
	$offersCustomFields = new OffersCustomFields();
}