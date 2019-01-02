<?php

/** @var stdClass $subscription_form */
/** @var array $plans */

$options   = get_option( 'fullstripe_options' );
$lockEmail = $options['lock_email_field_for_logged_in_users'];

$emailAddress   = "";
$isUserLoggedIn = is_user_logged_in();
if ( $lockEmail == '1' && $isUserLoggedIn ) {
	$current_user = wp_get_current_user();
	$emailAddress = $current_user->user_email;
}

$form_id = esc_attr( $subscription_form->name );

$wpfs_form_count = MM_WPFS::get_rendered_forms()->get_total();

$show_loading_id               = 'show-loading__' . $form_id;
$show_loading_coupon_id        = 'show-loading-coupon__' . $form_id;
$payment_form_submit_id        = 'payment-form-submit__' . $form_id;
$payment_form_coupon_submit_id = 'fullstripe-check-coupon-code__' . $form_id;
$coupon_input_id               = 'fullstripe-coupon-input__' . $form_id;
$plan_input_id                 = 'fullstripe-plan__' . $form_id;
$address_country_input_id      = 'fullstripe-address-country__' . $form_id;
$form_attributes               = 'class="form-horizontal payment-form subscription-form"';
if ( $wpfs_form_count == 1 ) {
	$form_attributes .= ' ';
	$form_attributes .= 'id="payment-form"';
	$form_attributes .= ' ';
	$form_attributes .= 'data-form-id="' . $form_id . '"';
} else {
	$form_attributes .= ' ';
	$form_attributes .= 'id="subscription-form__' . $form_id . '"';
	$form_attributes .= ' ';
	$form_attributes .= 'data-form-id="' . $form_id . '"';
}

?>
<form action="" method="POST" <?php echo $form_attributes; ?>>
	<fieldset>
		<div id="legend__<?php echo $form_id; ?>">
            <span class="fullstripe-form-title">
                <?php MM_WPFS::echo_translated_label( $subscription_form->formTitle ); ?>
            </span>
		</div>
		<input type="hidden" name="action" value="wp_full_stripe_subscription_charge"/>
		<input type="hidden" name="formId" value="<?php echo $subscription_form->subscriptionFormID; ?>"/>
		<input type="hidden" name="formName" value="<?php echo $subscription_form->name; ?>"/>
		<input type="hidden" name="formDoRedirect" value="<?php echo $subscription_form->redirectOnSuccess; ?>"/>
		<input type="hidden" name="formRedirectPostID" value="<?php echo $subscription_form->redirectPostID; ?>"/>
		<input type="hidden" name="formRedirectUrl" value="<?php echo $subscription_form->redirectUrl; ?>"/>
		<input type="hidden" name="formRedirectToPageOrPost" value="<?php echo $subscription_form->redirectToPageOrPost; ?>"/>
		<input type="hidden" name="formShowDetailedSuccessPage" value="<?php echo $subscription_form->showDetailedSuccessPage; ?>"/>
		<input type="hidden" name="sendEmailReceipt" value="<?php echo $subscription_form->sendEmailReceipt; ?>"/>
		<input type="hidden" name="showAddress" value="<?php echo $subscription_form->showAddress; ?>"/>
		<?php if ( $subscription_form->showCustomInput == 1 && $subscription_form->customInputs ): ?>
			<input type="hidden" name="customInputTitle" value="<?php echo $subscription_form->customInputTitle; ?>"/>
			<input type="hidden" name="customInputs" value="<?php echo $subscription_form->customInputs; ?>"/>
			<input type="hidden" name="customInputRequired" value="<?php echo $subscription_form->customInputRequired; ?>"/>
		<?php endif; ?>
		<!-- Name -->
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Card Holder\'s Name', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<input type="text" autocomplete="off" class="input-xlarge fullstripe-form-input" name="fullstripe_name" id="fullstripe_name__<?php echo $form_id; ?>" data-stripe="name">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Email Address', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<?php if ( $lockEmail == '1' && $isUserLoggedIn ): ?>
					<label class="fullstripe-data-label"><?php echo $emailAddress; ?></label>
					<input type="hidden" value="<?php echo $emailAddress; ?>" name="fullstripe_email" id="fullstripe_email__<?php echo $form_id; ?>">
				<?php else: ?>
					<input type="text" class="input-xlarge fullstripe-form-input" name="fullstripe_email" id="fullstripe_email__<?php echo $form_id; ?>">
				<?php endif; ?>
			</div>
		</div>
		<?php if ( $subscription_form->showCustomInput == 1 ): ?>
			<?php
			$customInputs = array();
			if ( $subscription_form->customInputs != null ) {
				$customInputs = explode( '{{', $subscription_form->customInputs );
			}
			?>
			<?php if ( $subscription_form->customInputs == null ): ?>
				<div class="control-group">
					<label class="control-label fullstripe-form-label"><?php MM_WPFS::echo_translated_label( $subscription_form->customInputTitle ); ?></label>

					<div class="controls">
						<input type="text" class="input-xlarge fullstripe-form-input" name="fullstripe_custom_input" id="fullstripe-custom-input__<?php echo $form_id; ?>">
					</div>
				</div>
			<?php endif; ?>
			<?php foreach ( $customInputs as $i => $label ): ?>
				<div class="control-group">
					<label class="control-label fullstripe-form-label"><?php MM_WPFS::echo_translated_label( $label ); ?></label>

					<div class="controls">
						<input type="text" class="input-xlarge fullstripe-form-input" name="fullstripe_custom_input[]" id="fullstripe-custom-input__<?php echo $form_id . '__' . ( $i + 1 ); ?>">
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Subscription Plan', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<select id="<?php echo $plan_input_id; ?>" name="fullstripe_plan" class="fullstripe-plan fullstripe-form-input input-xlarge" data-form-id="<?php echo $form_id; ?>">
					<?php foreach ( $plans as $plan ): ?>
						<?php
						$setup_fee = 0;
						if ( isset( $plan->metadata ) && isset( $plan->metadata->setup_fee ) ) {
							$setup_fee = $plan->metadata->setup_fee;
						}
						?>
						<option value="<?php echo esc_attr( $plan->id ); ?>"
						        data-value="<?php echo esc_attr( $plan->id ); ?>"
						        data-amount="<?php echo esc_attr( $plan->amount ); ?>"
						        data-interval="<?php echo esc_attr( MM_WPFS::get_translated_interval_label( $plan->interval, $plan->interval_count ) ); ?>"
						        data-interval-count="<?php echo esc_attr( $plan->interval_count ); ?>"
						        data-currency="<?php echo esc_attr( MM_WPFS::get_currency_symbol_for( $plan->currency ) ); ?>"
						        data-setup-fee="<?php echo esc_attr( $setup_fee ); ?>">
							<?php echo esc_html( MM_WPFS::translate_label( $plan->name ) ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
		<?php if ( $subscription_form->showAddress == 1 ): ?>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'Billing Address Street', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" name="fullstripe_address_line1" id="fullstripe_address_line1__<?php echo $form_id; ?>" class="fullstripe-form-input input-xlarge"><br/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'Billing Address Line 2', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" name="fullstripe_address_line2" id="fullstripe_address_line2__<?php echo $form_id; ?>" class="fullstripe-form-input input-xlarge"><br/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'City', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" name="fullstripe_address_city" id="fullstripe_address_city__<?php echo $form_id; ?>" class="fullstripe-form-input input-xlarge"><br/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'Zip', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" name="fullstripe_address_zip" id="fullstripe_address_zip__<?php echo $form_id; ?>" class="fullstripe-form-input input-medium"><br/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'State', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" name="fullstripe_address_state" id="fullstripe_address_state__<?php echo $form_id; ?>" class="fullstripe-form-input input-medium"><br/>
				</div>
			</div>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'Country', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<select name="fullstripe_address_country" id="<?php echo $address_country_input_id; ?>" class="fullstripe-form-input input-xlarge">
						<option value=""><?php echo esc_html( __( 'Select country', 'wp-full-stripe' ) ); ?></option>
						<?php
						foreach ( MM_WPFS::get_available_countries() as $country_key => $country_obj ) {
							$option = '<option value="' . $country_key . '"';
							$option .= '>';
							$option .= MM_WPFS::translate_label( $country_obj['name'] );
							$option .= '</option>';
							echo $option;
						}
						?>
					</select><br/>
				</div>
			</div>
		<?php endif; ?>
		<!-- Card Number -->
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Card Number', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<input type="text" autocomplete="off" class="input-xlarge fullstripe-form-input" size="20" data-stripe="number">
			</div>
		</div>
		<!-- Expiry-->
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Card Expiry Date', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<input type="text" size="2" data-stripe="exp-month" class="fullstripe-form-input input-mini"/>
				<span> / </span>
				<input type="text" size="4" data-stripe="exp-year" class="fullstripe-form-input input-mini"/>
			</div>
		</div>
		<!-- CVV -->
		<div class="control-group">
			<label class="control-label fullstripe-form-label"><?php _e( 'Card CVV', 'wp-full-stripe' ); ?></label>

			<div class="controls">
				<input type="password" autocomplete="off" class="input-mini fullstripe-form-input" size="4" maxlength="4" data-stripe="cvc"/>
			</div>
		</div>
		<?php if ( $subscription_form->showCouponInput == 1 ): ?>
			<div class="control-group">
				<label class="control-label fullstripe-form-label"><?php _e( 'Coupon Code', 'wp-full-stripe' ); ?></label>

				<div class="controls">
					<input type="text" class="input-medium fullstripe-form-input" name="fullstripe_coupon_input" id="<?php echo $coupon_input_id; ?>">
					<button id="<?php echo $payment_form_coupon_submit_id; ?>" class="payment-form-coupon" data-form-id="<?php echo $form_id; ?>"><?php _e( 'Apply', 'wp-full-stripe' ); ?></button>
					<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" id="<?php echo $show_loading_coupon_id; ?>" class="loading-animation"/>
				</div>
			</div>
		<?php endif; ?>
		<!-- Submit -->
		<div class="control-group">
			<div class="controls">
				<button id="<?php echo $payment_form_submit_id; ?>" type="submit"><?php MM_WPFS::echo_translated_label( $subscription_form->buttonTitle ); ?></button>
				<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" id="<?php echo $show_loading_id; ?>" class="loading-animation"/>
			</div>
		</div>
	</fieldset>
</form>
