<?php
/**
 * Offers CPT custom fields
 *
 * @package RedBalloonOffers
 */

if ( !class_exists( 'OffersCustomFields' ) ) {

	class OffersCustomFields
	{
		var $prefix = '_ocf_';
		var $customFields = array(
			array(
				'name'        => 'rate',
				'title'       => 'Rate',
				'type'        => 'number',
				'capability'  => 'edit_posts'
			),
			array(
				'name'        => 'adult',
				'title'       => 'Number of Adult',
				'type'        => 'number',
				'capability'  => 'edit_posts'
			),
			array(
				'name'        => 'child',
				'title'       => 'Number of Child',
				'type'        => 'number',
				'capability'  => 'edit_posts'
			),
			array(
				'name'        => 'cost',
				'title'       => 'Cost',
				'type'        => 'price',
				'capability'  => 'edit_posts'
			),
			array(
				'name'        => 'arrival',
				'title'       => 'Arrival Date',
				'type'        => 'date',
				'capability'  => 'edit_posts'
			),
			array(
				'name'        => 'departure',
				'title'       => 'Departure Date',
				'type'        => 'date',
				'capability'  => 'edit_posts'
			)
		);

		function __construct() {
			add_action( 'admin_menu', array( $this, 'create_custom_fields' ) );
			add_action( 'save_post', array( $this, 'save_custom_fields' ), 1, 2 );
		}

		function create_custom_fields() {
			if ( function_exists( 'add_meta_box' ) ) {
				add_meta_box( 'offers-custom-fields', 'Offer Fields', array( $this, 'display_custom_fields' ), 'offer', 'normal' );
			}
		}

		function display_custom_fields() {
			global $post;
			wp_nonce_field( 'offers-custom-fields', 'offers-custom-fields_wpnonce', false );
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
						case 'number': {
							$current_number = get_post_meta( $post->ID, $this->prefix . $customField['name'], true );
							echo '<label for="' . $this->prefix . $customField['name'] .'"><b>' . $customField['title'] . '</b></label>';
							echo '<select name="' . $this->prefix . $customField['name'] . '">';
							for ($i=1; $i<=5; $i++) {
								echo '<option value="' . $i . '"';
								if ( $current_number == $i ) echo ' selected';
								echo '>' . $i . '</option>';
							}
							echo '</select>';
							break;
						}
						case 'price': {
							$current_price = get_post_meta( $post->ID, $this->prefix . $customField['name'], true );
							echo '<label for="' . $this->prefix . $customField['name'] .'"><b>' . $customField['title'] . '</b></label>';
							echo '<input type="number" step="0.01" style="max-width:150px;" name="' . $this->prefix . $customField['name'] . '"';
							echo ' id="' . $this->prefix . $customField['name'] . '"';
							echo ' value="' . $current_price . '" />';
							echo '<p>Display format: $ ' . number_format( floatval( $current_price ), 2, '.', '' ) . ' USD/NIGHT</p>';
							break;
						}
						case 'date': {
							$current_date = get_post_meta( $post->ID, $this->prefix . $customField['name'], true );
							echo '<label for="' . $this->prefix . $customField['name'] .'"><b>' . $customField['title'] . '</b></label>';
							echo '<input type="date" name="' . $this->prefix . $customField['name'] . '"';
							echo ' id="' . $this->prefix . $customField['name'] . '"';
							echo ' value="';
							if ( !empty( $current_date ) ) {
								echo $current_date;
							} else {
								echo date('Y-m-d');
							}
							echo '" />';
							break;
						}
					}

					echo '</div>';
				}
			}

			echo '</div>';
		}

		function save_custom_fields( $post_id, $post ) {
			if ( !isset( $_POST['offers-custom-fields_wpnonce'] ) || !wp_verify_nonce( $_POST['offers-custom-fields_wpnonce'], 'offers-custom-fields' ) )
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