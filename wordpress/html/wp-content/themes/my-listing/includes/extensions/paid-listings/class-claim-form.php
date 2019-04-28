<?php

namespace MyListing\Ext\Paid_Listings;

class Claim_Form extends \WP_Job_Manager_Form {
	use \MyListing\Src\Traits\Instantiatable;

	public $form_data = array();
	public $step = 0;
	public $listing_id = 0;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Set Listing ID.
		$this->listing_id = absint( $_GET['listing_id'] );

		// Form name.
		$this->form_name = 'case27_paid_listing_submit_claim';

		// Steps.
		$steps  = array(
			'login_register' => array(
				'name'     => __( 'Login / Register', 'my-listing' ),
				'view'     => array( $this, 'login_register_view' ),
				'handler'  => array( $this, 'login_register_handler' ),
				'priority' => 1,
				'submit'   => __( 'Register Account &rarr;', 'my-listing' ),
			),
			'claim_package' => array(
				'name'     => __( 'Choose a package', 'my-listing' ),
				'view'     => array( $this, 'claim_package_view' ),
				'handler'  => array( $this, 'claim_package_handler' ),
				'priority' => 2,
				'submit'   => __( 'Select Package &rarr;', 'my-listing' ),
			),
			'claim_listing' => array(
				'name'     => __( 'Claim Listing', 'my-listing' ),
				'view'     => array( $this, 'claim_listing_view' ),
				'priority' => 4,
				'submit'   => __( 'Submit Claim &rarr;', 'my-listing' ),
			),
		);
		$this->steps = apply_filters( 'case27_paid_listing_claim_form_steps', $steps );
		uasort( $this->steps, array( $this, 'sort_by_priority' ) );

		// Get step.
		if ( isset( $_POST['step'] ) ) {
			$this->step = is_numeric( $_POST['step'] ) ? max( absint( $_POST['step'] ), 0 ) : array_search( $_POST['step'], array_keys( $this->steps ) );
		} elseif ( ! empty( $_GET['step'] ) ) {
			$this->step = is_numeric( $_GET['step'] ) ? max( absint( $_GET['step'] ), 0 ) : array_search( $_GET['step'], array_keys( $this->steps ) );
		}
	}

	/**
	 * Login Register View
	 *
	 * @since 1.0.0
	 */
	public function login_register_view() {
		//
	}


	/**
	 * Login Register Handler. Also handle all request.
	 *
	 * @since 1.0.0
	 */
	public function login_register_handler() {
		// Current Claim URL:
		$claim_url = add_query_arg( array(
			'listing_id' => $this->listing_id,
		), get_permalink() );

		// If not logged in, redirect user to login/register page.
		if ( ! is_user_logged_in() ) {
			return wp_safe_redirect( add_query_arg( [
				'redirect_to' => $claim_url,
				'notice' => 'login-required',
			], wc_get_page_permalink('myaccount') ) );
		}

		global $current_user;

		// User visiting claim page to claim a listing, check if they already submit claim.
		if ( ! isset( $_GET['_claim_id'] ) && is_user_logged_in() ) {
			$claims = case27_paid_listing_claim_get_claims( array(
				'listing_id' => absint( $this->listing_id ),
				'user_id'    => absint( get_current_user_id() ),
			) );

			// Claim found. Bail. Use it.
			if ( $claims && isset( $claims[0] ) ) {
				wp_safe_redirect( esc_url_raw( add_query_arg( '_claim_id', absint( $claims[0] ), $claim_url ) ) );
				exit;
			}
		}

		// Go to next step.
		$this->next_step();

		// If claim already set, move to next step.
		if ( isset( $_GET['_claim_id'] ) || case27_paid_listing_is_claimed( $this->listing_id ) ) {
			$this->next_step();
			return;
		}

		if ( ! empty( $_POST['listing_package'] ) ) {
			$listing = \MyListing\Src\Listing::get( $this->listing_id );
			if ( ! ( $listing && $listing->type && Util::validate_package( $_POST['listing_package'], $listing->type->get_slug() ) ) ) {
				return $this->add_error( _x( 'Failed to create claim.', 'Claim listing form', 'my-listing' ) );
			}

			// package is valid
			$package = get_post( $_POST['listing_package'] );

			// product selected
			if ( $package->post_type === 'product' ) {
				$product = wc_get_product( $package );
				if ( ! ( $product && $product->is_type( [ 'job_package', 'job_package_subscription' ] ) && get_post_meta( $product->get_id(), '_use_for_claims', true ) ) ) {
					return $this->add_error( _x( 'Invalid product selected.', 'Claim listing form', 'my-listing' ) );
				}

				$skip_checkout = apply_filters( 'mylisting\packages\free\skip-checkout', true ) === true;

				// If `skip-checkout` setting is enabled for free products,
				// create the user package and assign it to the listing.
				if ( $product->get_price() == 0 && $skip_checkout && $product->get_meta( '_disable_repeat_purchase' ) !== 'yes' ) {
					$user_package_id = case27_paid_listing_add_package( [
						'product_id'     => $product->get_id(),
						'duration'       => $product->get_duration(),
						'limit'          => $product->get_limit(),
						'featured'       => $product->is_listing_featured(),
						'use_for_claims' => $product->is_use_for_claims(),
					] );

					// Use it.
					if ( $user_package_id ) {
						$claim_id = case27_paid_listing_claim_create_claim( [
							'listing_id'      => $this->listing_id,
							'user_package_id' => $user_package_id,
						] );

						// Refresh, use claim ID in URL.
						wp_safe_redirect( esc_url_raw( add_query_arg( '_claim_id', absint( $claim_id ), $claim_url ) ) );
						exit;
					}

					return;
				}

				// otherwise add product to cart
				return case27_paid_listing_use_product_to_listing( $product->get_id(), $this->listing_id, $is_claim = true );
			}

			// user package selected
			if ( $package->post_type === 'case27_user_package' && is_user_logged_in() ) {
				$package = case27_paid_listing_get_package( $package->ID );
				if ( ! ( $package->has_package() && $package->get_status() === 'publish' && $package->is_use_for_claims() ) ) {
					return $this->add_error( _x( 'Invalid package selected.', 'Claim listing form', 'my-listing' ) );
				}

				// create claim
				$claim_id = case27_paid_listing_claim_create_claim( [
					'listing_id'      => $this->listing_id,
					'user_package_id' => $package->get_id(),
				] );

				if ( ! $claim_id ) {
					return $this->add_error( _x( 'Something went wrong. Please try again.', 'Claim listing form', 'my-listing' ) );
				}

				// success
				wp_safe_redirect( esc_url_raw( add_query_arg( '_claim_id', absint( $claim_id ), $claim_url ) ) );
				exit;
			}
		}
	}

	/**
	 * Select Claim Package View.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function claim_package_view() {
		$products = self::get_products();
		$user_packages = self::get_user_packages();
		$listing = \MyListing\Src\Listing::get( $this->listing_id );
		if ( ! ( $listing && $listing->type ) ) {
			return;
		}

		if ( $listing->get_author_id() === absint( get_current_user_id() ) ) {
			echo '<div class="job-manager-error">' . __( 'Invalid request. You cannot claim your own listing.', 'my-listing' ) . '</div>';
			return;
		}

		$tree = Util::get_package_tree_for_listing_type( $listing->type );
		?>
		<section class="i-section c27-packages">
			<div class="container">
				<div class="row section-title">
					<h2 class="case27-primary-text"><?php _e( 'Choose a Package', 'my-listing' ) ?></h2>
					<p><?php _e( 'Pricing', 'my-listing' ) ?></p>
				</div>
				<form method="post" id="job_package_selection">
					<div class="job_listing_packages">
						<?php require locate_template( 'templates/add-listing/choose-package.php' ) ?>
					</div>
				</form>
			</div>
		</section>
		<?php
	}

	/**
	 * Claim Listing View. Display claim info.
	 *
	 * @since 1.0.0
	 */
	public function claim_listing_view() {
		case27_paid_listing_claim_info( isset( $_GET['_claim_id'] ) ? $_GET['_claim_id'] : false, $this->listing_id );
	}

	/**
	 * Claim Listing Handler
	 * This function only loaded when use submit the form.
	 *
	 * @since 3.0.0
	 */
	public function claim_listing_handler() {
		// Claim already created.
		if ( isset( $_GET['_claim_id'] ) && $_GET['_claim_id'] ) {
			return;
		}
	}

	/* === UTILITY === */

	/**
	 * Get Step Title/Name.
	 *
	 * @since 1.0.0
	 */
	public function get_step_title() {
		$step_key = $this->get_step_key( $this->step );
		return isset( $this->steps[ $step_key ]['name'] ) ? $this->steps[ $step_key ]['name'] : '';
	}

	/**
	 * Get Step Submit Text.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_step_submit() {
		$step_key = $this->get_step_key( $this->step );
		return isset( $this->steps[ $step_key ]['submit'] ) ? $this->steps[ $step_key ]['submit'] : '';
	}

	/**
	 * Get Products
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_products() {
		$product_ids = case27_paid_listing_claim_get_products( isset( $_GET['listing_id'] ) ? absint( $_GET['listing_id'] ) : false );
		$products = array();
		if ( $product_ids ) {
			foreach ( $product_ids as $product_id ) {
				$products[ $product_id ] = wc_get_product( $product_id );
			}
		}
		return $products;
	}

	/**
	 * Get User Packages
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	public static function get_user_packages() {
		$packages_ids = case27_paid_listing_claim_get_user_packages( isset( $_GET['listing_id'] ) ? absint( $_GET['listing_id'] ) : false );
		$packages = array();
		foreach ( $packages_ids as $package_id ) {
			$packages[ $package_id ]= case27_paid_listing_get_package( $package_id );
		}
		return $packages;
	}
}
