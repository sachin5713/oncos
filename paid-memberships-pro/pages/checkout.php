<?php
/**
 * Template: Checkout
 *
 * See documentation for how to override the PMPro templates.
 * @link https://www.paidmembershipspro.com/documentation/templates/
 *
 * @version 2.0.2
 *
 * @author Paid Memberships Pro
 */

global $gateway, $pmpro_review, $skip_account_fields, $pmpro_paypal_token, $wpdb, $current_user, $pmpro_msg, $pmpro_msgt, $pmpro_requirebilling, $pmpro_level, $pmpro_levels, $tospage, $pmpro_show_discount_code, $pmpro_error_fields, $pmpro_default_country;

global $discount_code, $username, $password, $password2, $bfirstname, $blastname, $baddress1, $baddress2, $bcity, $bstate, $bzipcode, $bcountry, $bphone, $bemail, $bconfirmemail, $CardType, $AccountNumber, $ExpirationMonth,$ExpirationYear;

/**
 * Filter to set if PMPro uses email or text as the type for email field inputs.
 *
 * @since 1.8.4.5
 *
 * @param bool $use_email_type, true to use email type, false to use text type
 */
$pmpro_email_field_type = apply_filters('pmpro_email_field_type', true);

// Set the wrapping class for the checkout div based on the default gateway;
$default_gateway = pmpro_getOption( 'gateway' );
if ( empty( $default_gateway ) ) {
	$pmpro_checkout_gateway_class = 'pmpro_checkout_gateway-none';
} else {
	$pmpro_checkout_gateway_class = 'pmpro_checkout_gateway-' . $default_gateway;
}
?>

<?php do_action('pmpro_checkout_before_form'); ?>

<div id="pmpro_level-<?php echo intval( $pmpro_level->id ); ?>" class="<?php echo esc_attr( pmpro_get_element_class( $pmpro_checkout_gateway_class, 'pmpro_level-' . $pmpro_level->id ) ); ?>">
<form id="pmpro_form" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_form' ) ); ?>" action="<?php if(!empty($_REQUEST['review'])) echo esc_url( pmpro_url("checkout", "?level=" . $pmpro_level->id) ); ?>" method="post">

	<input type="hidden" id="level" name="level" value="<?php echo esc_attr($pmpro_level->id) ?>" />
	<input type="hidden" id="checkjavascript" name="checkjavascript" value="1" />
	<?php if ($discount_code && $pmpro_review) { ?>
		<input class="<?php echo esc_attr( pmpro_get_element_class( 'input pmpro_alter_price', 'discount_code' ) ); ?>" id="discount_code" name="discount_code" type="hidden" size="20" value="<?php echo esc_attr($discount_code) ?>" />
	<?php } ?>

	<?php if($pmpro_msg) { ?>
		<div id="pmpro_message" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_message ' . $pmpro_msgt, $pmpro_msgt ) ); ?>">
			<?php echo wp_kses_post( apply_filters( 'pmpro_checkout_message', $pmpro_msg, $pmpro_msgt ) ); ?>
		</div>
	<?php } else { ?>
		<div id="pmpro_message" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_message' ) ); ?>" style="display: none;"></div>
	<?php } ?>

	<?php if($pmpro_review) { ?>
		<p><?php echo wp_kses( __( 'Almost done. Review the membership information and pricing below then <strong>click the "Complete Payment" button</strong> to finish your order.', 'paid-memberships-pro' ), array( 'strong' => array() ) ); ?></p>
	<?php } ?>

	<?php
		$include_pricing_fields = apply_filters( 'pmpro_include_pricing_fields', true );
		if ( $include_pricing_fields ) {
		?>
		<div id="pmpro_pricing_fields" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout', 'pmpro_pricing_fields' ) ); ?>">
			<h3>
				<?php if(count($pmpro_levels) > 1) { ?><span class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout-h3-msg' ) ); ?>"><a href="<?php echo esc_url( pmpro_url( "levels" ) ); ?>"><?php esc_html_e('change', 'paid-memberships-pro' );?></a></span><?php } ?>
			</h3>
			<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout-fields' ) ); ?>">

				<?php
					/**
					 * All devs to filter the level description at checkout.
					 * We also have a function in includes/filters.php that applies the the_content filters to this description.
					 * @param string $description The level description.
					 * @param object $pmpro_level The PMPro Level object.
					 */
					$level_description = apply_filters('pmpro_level_description', $pmpro_level->description, $pmpro_level);
					if ( ! empty( $level_description ) ) { ?>
						<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_level_description_text' ) );?>">
							<?php echo wp_kses_post( $level_description ); ?>
						</div>
						<?php
					}
				?>


				<?php do_action("pmpro_checkout_after_level_cost"); ?>

				<?php if($pmpro_show_discount_code) { ?>
					<?php if($discount_code && !$pmpro_review) { ?>
						<p id="other_discount_code_p" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_small', 'other_discount_code_p' ) ); ?>"><a id="other_discount_code_a" href="#discount_code"><?php esc_html_e('Click here to change your discount code.', 'paid-memberships-pro' );?></a></p>
					<?php } elseif(!$pmpro_review) { ?>
						<p id="other_discount_code_p" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_small', 'other_discount_code_p' ) ); ?>"><?php esc_html_e('Do you have a discount code?', 'paid-memberships-pro' );?> <a id="other_discount_code_a" href="#discount_code"><?php esc_html_e('Click here to enter your discount code', 'paid-memberships-pro' );?></a>.</p>
					<?php } elseif($pmpro_review && $discount_code) { ?>
						<p><strong><?php esc_html_e('Discount Code', 'paid-memberships-pro' );?>:</strong> <?php echo esc_html( $discount_code ); ?></p>
					<?php } ?>
				<?php } ?>

				<?php if($pmpro_show_discount_code) { ?>
				<div id="other_discount_code_tr" style="display: none;">
					<label for="other_discount_code"><?php esc_html_e('Discount Code', 'paid-memberships-pro' );?></label>
					<input id="other_discount_code" name="other_discount_code" type="text" class="<?php echo esc_attr( pmpro_get_element_class( 'input pmpro_alter_price', 'other_discount_code' ) ); ?>" size="20" value="<?php echo esc_attr($discount_code); ?>" />
					<input type="button" name="other_discount_code_button" id="other_discount_code_button" value="<?php esc_attr_e('Apply', 'paid-memberships-pro' );?>" />
				</div>
				<?php } ?>
			</div> <!-- end pmpro_checkout-fields -->
		</div> <!-- end pmpro_pricing_fields -->
		<?php
		} // if ( $include_pricing_fields )
	?>

	<?php
		do_action('pmpro_checkout_after_pricing_fields');
	?>

	<?php if(!$skip_account_fields && !$pmpro_review) { ?>

	<?php 
		// Get discount code from URL parameter, so if the user logs in it will keep it applied.
		$discount_code_link = !empty( $discount_code) ? '&discount_code=' . $discount_code : ''; 
	?>
	<div id="pmpro_user_fields oncos_registration_form" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout', 'pmpro_user_fields' ) ); ?>">
		<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout-fields' ) ); ?>">
		<div class="row">
			<div class="col-sm-6">
				<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-bfirstname', 'pmpro_checkout-field-bfirstname' ) ); ?>">
					<label for="bfirstname"><?php esc_html_e('First Name*', 'paid-memberships-pro' );?></label>
					<input id="bfirstname" name="bfirstname" type="text" class="<?php echo esc_attr( pmpro_get_element_class( 'input', 'bfirstname' ) ); ?>" size="30" value="<?php echo esc_attr($bfirstname); ?>" />
				</div> <!-- end pmpro_checkout-field-bfirstname -->
			</div>
			<div class="col-sm-6">
				<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-blastname', 'pmpro_checkout-field-blastname' ) ); ?>">
					<label for="blastname"><?php esc_html_e('Last Name*', 'paid-memberships-pro' );?></label>
					<input id="blastname" name="blastname" type="text" class="<?php echo esc_attr( pmpro_get_element_class( 'input', 'blastname' ) ); ?>" size="30" value="<?php echo esc_attr($blastname); ?>" />
				</div> <!-- end pmpro_checkout-field-blastname -->
			</div>
			<input id="username" name="username" type="hidden" class="<?php echo esc_attr( pmpro_get_element_class( 'input', 'username' ) ); ?>" size="30" value="<?php echo esc_attr($bemail); ?>" />
			<?php do_action('pmpro_checkout_after_username');?>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-bemail', 'pmpro_checkout-field-bemail' ) ); ?>">
					<label for="bemail"><?php esc_html_e('Email Address*', 'paid-memberships-pro' );?></label>
					<input id="bemail" name="bemail" type="<?php echo ($pmpro_email_field_type ? 'email' : 'text'); ?>" class="" size="30" value="<?php echo esc_attr($bemail); ?>" />
				</div> <!-- end pmpro_checkout-field-bemail -->
			</div>
			<div class="col-sm-6">
				<?php
					$pmpro_checkout_confirm_email = apply_filters("pmpro_checkout_confirm_email", true);
					if($pmpro_checkout_confirm_email) { ?>
						<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-bconfirmemail', 'pmpro_checkout-field-bconfirmemail' ) ); ?>">
							<label for="bconfirmemail"><?php esc_html_e('Confirm Email Address*', 'paid-memberships-pro' );?></label>
							<input id="bconfirmemail" name="bconfirmemail" type="<?php echo ($pmpro_email_field_type ? 'email' : 'text'); ?>" class="<?php echo esc_attr( pmpro_get_element_class( 'input', 'bconfirmemail' ) ); ?>" size="30" value="<?php echo esc_attr($bconfirmemail); ?>" />
						</div> <!-- end pmpro_checkout-field-bconfirmemail -->
					<?php } else { ?>
						<input type="hidden" name="bconfirmemail_copy" value="1" />
					<?php }
					do_action('pmpro_checkout_after_email');
				?>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-password', 'pmpro_checkout-field-password' ) ); ?>">
					<label for="password"><?php esc_html_e('Password*', 'paid-memberships-pro' );?></label>
					<input id="password" name="password" type="password" class="" size="30" value="<?php echo esc_attr($password); ?>" />
				</div> <!-- end pmpro_checkout-field-password -->
			</div>
			<div class="col-sm-6">		
				<?php
					$pmpro_checkout_confirm_password = apply_filters("pmpro_checkout_confirm_password", true);
					if($pmpro_checkout_confirm_password) { ?>
						<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_checkout-field pmpro_checkout-field-password2', 'pmpro_checkout-field-password2' ) ); ?>">
							<label for="password2"><?php esc_html_e('Confirm Password*', 'paid-memberships-pro' );?></label>
							<input id="password2" name="password2" type="password" class="<?php echo esc_attr( pmpro_get_element_class( 'input', 'password2' ) ); ?>" size="30" value="<?php echo esc_attr($password2); ?>" />
						</div> <!-- end pmpro_checkout-field-password2 -->
					<?php } else { ?>
						<input type="hidden" name="password2_copy" value="1" />
					<?php }
					do_action('pmpro_checkout_after_password');
				?>
			</div>
		</div>

			<script>
			    (function ($) {
			        $(document).ready(function () {
			            $('#bemail').on('input', function () {
			                var email = $('#bemail').val();
			                $('#username').val(email);
			            });
			        });
			    })(jQuery);
			</script>
			


		</div>  <!-- end pmpro_checkout-fields -->
	</div> <!-- end pmpro_user_fields -->
	<?php } elseif($current_user->ID && !$pmpro_review) { ?>
		<div id="pmpro_account_loggedin" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_message pmpro_alert', 'pmpro_account_loggedin' ) ); ?>">
			<?php
				$allowed_html = array(
					'a' => array(
						'href' => array(),
						'title' => array(),
						'target' => array(),
					),
					'strong' => array(),
				);
				echo wp_kses( sprintf( __('You are logged in as <strong>%s</strong>. If you would like to use a different account for this membership, <a href="%s">log out now</a>.', 'paid-memberships-pro' ), $current_user->user_login, wp_logout_url( esc_url_raw( $_SERVER['REQUEST_URI'] ) ) ), $allowed_html );
			?>
		</div> <!-- end pmpro_account_loggedin -->
	<?php } ?>

	<?php
		do_action('pmpro_checkout_after_user_fields');
	?>
	<div class="row">
		<div class="col-sm-12"><?php do_action('pmpro_checkout_boxes');?></div>
	</div>
	

	<?php do_action('pmpro_checkout_after_payment_information_fields'); ?>




	<?php
		do_action('pmpro_checkout_after_captcha');
	?>

	<?php do_action("pmpro_checkout_before_submit_button"); ?>

	<div class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_submit' ) ); ?>">
		<hr />
		<?php if ( $pmpro_msg ) { ?>
			<div id="pmpro_message_bottom" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_message ' . $pmpro_msgt, $pmpro_msgt ) ); ?>"><?php echo wp_kses_post( $pmpro_msg ); ?></div>
		<?php } else { ?>
			<div id="pmpro_message_bottom" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_message' ) ); ?>" style="display: none;"></div>
		<?php } ?>

		<?php if($pmpro_review) { ?>

			<span id="pmpro_submit_span">
				<input type="hidden" name="confirm" value="1" />
				<input type="hidden" name="token" value="<?php echo esc_attr($pmpro_paypal_token); ?>" />
				<input type="hidden" name="gateway" value="<?php echo esc_attr($gateway); ?>" />
				<input type="submit" id="pmpro_btn-submit" class="<?php echo esc_attr( pmpro_get_element_class( 'pmpro_btn pmpro_btn-submit-checkout', 'pmpro_btn-submit-checkout' ) ); ?>" value="<?php esc_attr_e('Complete Payment', 'paid-memberships-pro' );?> &raquo;" />
			</span>

		<?php } else { ?>

			<?php
				$pmpro_checkout_default_submit_button = apply_filters('pmpro_checkout_default_submit_button', true);
				if($pmpro_checkout_default_submit_button)
				{
				?>
				<span id="pmpro_submit_span">
					<input type="hidden" name="submit-checkout" value="1" />
					<input type="submit"  id="pmpro_btn-submit" class="<?php echo esc_attr( pmpro_get_element_class(  'pmpro_btn pmpro_btn-submit-checkout', 'pmpro_btn-submit-checkout' ) ); ?>" value="<?php if($pmpro_requirebilling) { esc_html_e('Submit and Check Out', 'paid-memberships-pro' ); } else { esc_html_e('Submit and Confirm', 'paid-memberships-pro' );}?> &raquo;" />
				</span>
				<?php
				}
			?>

		<?php } ?>

		<span id="pmpro_processing_message" style="visibility: hidden;">
			<?php
				$processing_message = apply_filters("pmpro_processing_message", __("Processing...", 'paid-memberships-pro' ));
				echo wp_kses_post( $processing_message );
			?>
		</span>
	</div>
</form>

<?php do_action('pmpro_checkout_after_form'); ?>

</div> <!-- end pmpro_level-ID -->
