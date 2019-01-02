<?php

/** @var stdClass $payment_form */

$options   = get_option( 'fullstripe_options' );
$lockEmail = $options['lock_email_field_for_logged_in_users'];

$emailAddress   = "";
$isUserLoggedIn = is_user_logged_in();
if ( $lockEmail == '1' && $isUserLoggedIn ) {
	$current_user = wp_get_current_user();
	$emailAddress = $current_user->user_email;
}

$first_amount = null;

$form_id = esc_attr( $payment_form->name );

$wpfs_form_count = MM_WPFS::get_rendered_forms()->get_total();

$show_loading_id                        = 'show-loading' . '__' . $form_id;
$payment_form_submit_id                 = 'payment-form-submit' . '__' . $form_id;
$custom_amount_input_id                 = 'fullstripe-custom-amount__' . $form_id;
$list_of_amounts_custom_amount_input_id = 'fullstripe-list-of-amounts-custom-amount__' . $form_id;
$address_country_input_id               = 'fullstripe-address-country__' . $form_id;

$form_attributes = 'class="payment-form-compact"';
if ( $wpfs_form_count == 1 ) {
	$form_attributes .= ' ';
	$form_attributes .= 'id="payment-form-style"';
	$form_attributes .= ' ';
	$form_attributes .= 'data-form-id="' . $form_id . '"';
} else {
	$form_attributes .= ' ';
	$form_attributes .= 'id="payment-form-compact__' . $form_id . '"';
	$form_attributes .= ' ';
	$form_attributes .= 'data-form-id="' . $form_id . '"';
}

$currency_symbol = MM_WPFS::get_currency_symbol_for( $payment_form->currency );
$credit_card_image = MM_WPFS::get_credit_card_image_for( $payment_form->currency );

?>
<h4><span class="fullstripe-form-title"><?php MM_WPFS::echo_translated_label( $payment_form->formTitle ); ?></span></h4>
<form action="" method="POST" <?php echo $form_attributes; ?>>
	<input type="hidden" name="action" value="wp_full_stripe_payment_charge"/>
	<input type="hidden" name="amount" value="<?php echo $payment_form->amount; ?>"/>
	<input type="hidden" name="formId" value="<?php echo $payment_form->paymentFormID; ?>"/>
	<input type="hidden" name="formName" value="<?php echo $payment_form->name; ?>"/>
	<input type="hidden" name="customAmount" value="<?php echo $payment_form->customAmount; ?>"/>
	<input type="hidden" name="formDoRedirect" value="<?php echo $payment_form->redirectOnSuccess; ?>"/>
	<input type="hidden" name="formRedirectPostID" value="<?php echo $payment_form->redirectPostID; ?>"/>
	<input type="hidden" name="formRedirectUrl" value="<?php echo $payment_form->redirectUrl; ?>"/>
	<input type="hidden" name="formRedirectToPageOrPost" value="<?php echo $payment_form->redirectToPageOrPost; ?>"/>
	<input type="hidden" name="formShowDetailedSuccessPage" value="<?php echo $payment_form->showDetailedSuccessPage; ?>"/>
	<input type="hidden" name="showAddress" value="<?php echo $payment_form->showAddress; ?>"/>
	<input type="hidden" name="sendEmailReceipt" value="<?php echo $payment_form->sendEmailReceipt; ?>"/>
	<?php if ( $payment_form->showCustomInput == 1 && $payment_form->customInputs ): ?>
		<input type="hidden" name="customInputs" value="<?php echo $payment_form->customInputs; ?>"/>
		<input type="hidden" name="customInputRequired" value="<?php echo $payment_form->customInputRequired; ?>"/>
	<?php endif; ?>
	<?php if ( $payment_form->customAmount == 'list_of_amounts' ): ?>
		<input type="hidden" name="allowListOfAmountsCustom" value="<?php echo $payment_form->allowListOfAmountsCustom; ?>"/>
	<?php endif; ?>
	<div class="_100">
		<label class="control-label fullstripe-form-label"><?php _e( 'Email Address', 'wp-full-stripe' ); ?></label>
		<?php if ( $lockEmail == '1' && $isUserLoggedIn ): ?>
			<br>
			<label class="fullstripe-data-label"><?php echo $emailAddress; ?></label>
			<input type="hidden" value="<?php echo $emailAddress; ?>" name="fullstripe_email" id="fullstripe_email__<?php echo $form_id; ?>">
		<?php else: ?>
			<input type="text" name="fullstripe_email" id="fullstripe_email__<?php echo $form_id; ?>">
		<?php endif; ?>
	</div>
	<?php if ( $payment_form->showCustomInput == 1 ): ?>
		<?php
		$customInputs = array();
		if ( $payment_form->customInputs != null ) {
			$customInputs = explode( '{{', $payment_form->customInputs );
		}
		?>
		<?php if ( $payment_form->customInputs == null ): ?>
			<div class="_100">
				<label class="control-label fullstripe-form-label"><?php MM_WPFS::echo_translated_label( $payment_form->customInputTitle ); ?></label>
				<input type="text" name="fullstripe_custom_input" id="fullstripe_custom_input__<?php echo $form_id; ?>">
			</div>
		<?php endif; ?>

		<?php foreach ( $customInputs as $i => $label ): ?>
			<div class="_100">
				<label class="control-label fullstripe-form-label"><?php MM_WPFS::echo_translated_label( $label ); ?></label>
				<input type="text" name="fullstripe_custom_input[]" id="fullstripe-custom-input__<?php echo $form_id . '__' . ( $i + 1 ); ?>">
			</div>
		<?php endforeach; ?>

	<?php endif; ?>
	<?php if ( $payment_form->customAmount == 'custom_amount' ): ?>
		<div class="_100">
			<label class="control-label fullstripe-form-label"><?php _e( 'Payment Amount', 'wp-full-stripe' ); ?></label>
			<input class="fullstripe-custom-amount" type="text" name="fullstripe_custom_amount" id="<?php echo $custom_amount_input_id; ?>">
		</div>
	<?php endif; ?>
	<?php if ( $payment_form->customAmount == 'list_of_amounts' ): ?>
		<div class="_100">
			<label class="control-label fullstripe-form-label"><?php _e( 'Payment Amount', 'wp-full-stripe' ); ?></label>
			<select class="fullstripe-custom-amount" name="fullstripe_custom_amount" id="<?php echo $custom_amount_input_id; ?>" data-button-title="<?php MM_WPFS::echo_translated_label( $payment_form->buttonTitle ); ?>" data-show-amount="<?php echo $payment_form->showButtonAmount; ?>" data-currency-symbol="<?php echo $currency_symbol; ?>" data-form-id="<?php echo $form_id; ?>">
				<?php
				$list_of_amounts = json_decode( $payment_form->listOfAmounts );
				$first_amount    = null;
				foreach ( $list_of_amounts as $index => $list_element ) {
					$amount            = $list_element[0];
					$description       = $list_element[1];
					$amount_label      = sprintf( "%s%0.2f", $payment_form->currency, ( $amount / 100 ) );
					$description_label = MM_WPFS::translate_label( $description );
					if ( strpos( $description, '{amount}' ) !== false ) {
						$description_label = str_replace( '{amount}', $amount_label, $description_label );
					}
					if ( is_null( $first_amount ) ) {
						$first_amount = $amount;
					}
					$option_row = '<option';
					$option_row .= ' value="' . ( $amount / 100 ) . '"';
					$option_row .= " data-amount-index=\"$index\"";
					$option_row .= '>';
					$option_row .= sprintf( $description_label );
					$option_row .= "</option>";
					echo $option_row;
				}
				if ( $payment_form->allowListOfAmountsCustom == '1' ) {
					echo '<option value="other">' . __( 'Other', 'wp-full-stripe' ) . '</option>';
				}
				?>
			</select>
			<?php if ( $payment_form->allowListOfAmountsCustom == '1' ): ?>
				<input type="text" name="fullstripe_list_of_amounts_custom_amount" id="<?php echo $list_of_amounts_custom_amount_input_id; ?>" style="display: none;" class="fullstripe-list-of-amounts-custom-amount" data-button-title="<?php MM_WPFS::echo_translated_label( $payment_form->buttonTitle ); ?>" data-show-amount="<?php echo $payment_form->showButtonAmount; ?>" data-currency-symbol="<?php echo $currency_symbol; ?>" data-form-id="<?php echo $form_id; ?>">
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php if ( $payment_form->showAddress == 1 ): ?>
		<div class="_100">
			<label class="control-label fullstripe-form-label"><?php _e( 'Billing Address Street', 'wp-full-stripe' ); ?></label>
			<input type="text" name="fullstripe_address_line1" id="fullstripe_address_line1__<?php echo $form_id; ?>">
		</div>
		<div class="_100">
			<label class="control-label fullstripe-form-label"><?php _e( 'Billing Address Line 2', 'wp-full-stripe' ); ?></label>
			<input type="text" name="fullstripe_address_line2" id="fullstripe_address_line2__<?php echo $form_id; ?>">
		</div>
		<div class="_100">
			<label class="control-label fullstripe-form-label"><?php _e( 'City', 'wp-full-stripe' ); ?></label>
			<input type="text" name="fullstripe_address_city" id="fullstripe_address_city__<?php echo $form_id; ?>">
		</div>
		<div class="_50">
			<label class="control-label fullstripe-form-label"><?php _e( 'Zip', 'wp-full-stripe' ); ?></label>
			<input type="text" name="fullstripe_address_zip" id="fullstripe_address_zip__<?php echo $form_id; ?>">
		</div>
		<div class="_50">
			<label class="control-label fullstripe-form-label"><?php _e( 'State', 'wp-full-stripe' ); ?></label>
			<input type="text" name="fullstripe_address_state" id="fullstripe_address_state__<?php echo $form_id; ?>">
		</div>
		<div class="_100">
			<label class="control-label fullstripe-form-label"><?php _e( 'Country', 'wp-full-stripe' ); ?></label>
			<select name="fullstripe_address_country" id="<?php echo $address_country_input_id; ?>">
				<option value=""><?php echo esc_html( __( 'Select country' ) ); ?></option>
				<?php
				foreach ( MM_WPFS::get_available_countries() as $country_key => $country_obj ) {
					$option = '<option value="' . $country_key . '"';
					$option .= '>';
					$option .= MM_WPFS::translate_label( $country_obj['name'] );
					$option .= '</option>';
					echo $option;
				}
				?>
			</select>
		</div>
	<?php endif; ?>
	<div class="_100" style="padding-bottom: 5px;">
		<img src="<?php echo plugins_url( '../img/' . $credit_card_image, dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Credit Cards', 'wp-full-stripe' ); ?>"/>
	</div>
	<div class="_50">
		<label class="control-label fullstripe-form-label"><?php _e( 'Card Holder\'s Name', 'wp-full-stripe' ); ?></label>
		<input type="text" name="fullstripe_name" id="fullstripe_name__<?php echo $form_id; ?>" data-stripe="name">
	</div>
	<div class="_50">
		<label class="control-label fullstripe-form-label"><?php _e( 'Card Number', 'wp-full-stripe' ); ?></label>
		<input type="text" autocomplete="off" size="20" data-stripe="number">
	</div>
	<div class="_50">
		<label class="control-label fullstripe-form-label"><?php _e( 'Card CVV', 'wp-full-stripe' ); ?></label>
		<input type="password" autocomplete="off" size="4" maxlength="4" data-stripe="cvc"/>
	</div>
	<div class="_25">
		<label class="control-label fullstripe-form-label"><?php _e( 'Month', 'wp-full-stripe' ); ?></label>
		<select data-stripe="exp-month">
			<option value="01"><?php _e( 'January', 'wp-full-stripe' ); ?></option>
			<option value="02"><?php _e( 'February', 'wp-full-stripe' ); ?></option>
			<option value="03"><?php _e( 'March', 'wp-full-stripe' ); ?></option>
			<option value="04"><?php _e( 'April', 'wp-full-stripe' ); ?></option>
			<option value="05"><?php _e( 'May', 'wp-full-stripe' ); ?></option>
			<option value="06"><?php _e( 'June', 'wp-full-stripe' ); ?></option>
			<option value="07"><?php _e( 'July', 'wp-full-stripe' ); ?></option>
			<option value="08"><?php _e( 'August', 'wp-full-stripe' ); ?></option>
			<option value="09"><?php _e( 'September', 'wp-full-stripe' ); ?></option>
			<option value="10"><?php _e( 'October', 'wp-full-stripe' ); ?></option>
			<option value="11"><?php _e( 'November', 'wp-full-stripe' ); ?></option>
			<option value="12"><?php _e( 'December', 'wp-full-stripe' ); ?></option>
		</select>
	</div>
	<div class="_25">
		<label class="control-label fullstripe-form-label"><?php _e( 'Year', 'wp-full-stripe' ); ?></label>
		<select data-stripe="exp-year">
			<?php
			$startYear = date( 'Y' );
			$numYears  = 20;
			for ( $i = 0; $i < $numYears; $i ++ ) {
				$yr = $startYear + $i;
				echo "<option value='" . $yr . "'>" . $yr . "</option>";
			}
			?>
		</select>
	</div>
	<div class="_100">
		<br/>
	</div>
	<div class="_100">
		<?php if ( $payment_form->customAmount == 'specified_amount' ): ?>
			<button id="<?php echo $payment_form_submit_id; ?>" type="submit"><?php MM_WPFS::echo_translated_label( $payment_form->buttonTitle ); ?><?php if ( $payment_form->showButtonAmount == 1 ) {
					echo sprintf( ' %s%0.2f', $currency_symbol, ( $payment_form->amount / 100.0 ) );
				} ?></button>
		<?php elseif ( $payment_form->customAmount == 'list_of_amounts' ): ?>
			<button id="<?php echo $payment_form_submit_id; ?>" type="submit"><?php MM_WPFS::echo_translated_label( $payment_form->buttonTitle ); ?><?php if ( $payment_form->showButtonAmount == 1 ) {
					echo sprintf( ' %s%0.2f', $currency_symbol, ( $first_amount / 100.0 ) );
				} ?></button>
		<?php else: ?>
			<button id="<?php echo $payment_form_submit_id; ?>" type="submit"><?php MM_WPFS::echo_translated_label( $payment_form->buttonTitle ); ?></button>
		<?php endif; ?>
		<img src="<?php echo plugins_url( '../img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php _e( 'Loading...', 'wp-full-stripe' ); ?>" id="<?php echo $show_loading_id; ?>" class="loading-animation"/>
	</div>
</form>