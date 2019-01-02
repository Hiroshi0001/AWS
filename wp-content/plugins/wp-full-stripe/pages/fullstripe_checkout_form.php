<?php

/** @var stdClass $checkout_form */

$form_id = esc_attr( $checkout_form->name );

$form_attributes = 'class="fullstripe_checkout_form checkout-form"';
$form_attributes .= ' ';
$form_attributes .= 'id="checkout-form__' . $form_id . '"';
$form_attributes .= ' ';
$form_attributes .= 'data-form-id="' . $form_id . '"';

?>
<form action="" method="POST" <?php echo $form_attributes; ?>>
	<input type="hidden" name="action" value="fullstripe_checkout_form_charge"/>
	<input type="hidden" name="companyName" value="<?php echo $checkout_form->companyName; ?>"/>
	<input type="hidden" name="productDesc" value="<?php MM_WPFS::echo_translated_label( $checkout_form->productDesc ); ?>"/>
	<input type="hidden" name="amount" value="<?php echo $checkout_form->amount; ?>"/>
	<input type="hidden" name="buttonTitle" value="<?php MM_WPFS::echo_translated_label( $checkout_form->buttonTitle ); ?>"/>
	<input type="hidden" name="sendEmailReceipt" value="<?php echo $checkout_form->sendEmailReceipt; ?>"/>
	<input type="hidden" name="showBillingAddress" value="<?php echo $checkout_form->showBillingAddress; ?>"/>
	<input type="hidden" name="showRememberMe" value="<?php echo $checkout_form->showRememberMe; ?>"/>
	<input type="hidden" name="image" value="<?php echo $checkout_form->image; ?>"/>
	<input type="hidden" name="currency" value="<?php echo $checkout_form->currency; ?>"/>
	<input type="hidden" name="name" value="<?php echo $checkout_form->name; ?>"/>
	<input type="hidden" name="formId" value="<?php echo $checkout_form->checkoutFormID; ?>"/>
	<input type="hidden" name="redirectOnSuccess" value="<?php echo $checkout_form->redirectOnSuccess; ?>"/>
	<input type="hidden" name="redirectPostID" value="<?php echo $checkout_form->redirectPostID; ?>"/>
	<input type="hidden" name="redirectUrl" value="<?php echo $checkout_form->redirectUrl; ?>"/>
	<input type="hidden" name="redirectToPageOrPost" value="<?php echo $checkout_form->redirectToPageOrPost; ?>"/>
	<input type="hidden" name="formShowDetailedSuccessPage" value="<?php echo $checkout_form->showDetailedSuccessPage; ?>"/>
	<input type="hidden" name="useBitcoin" value="<?php echo $checkout_form->useBitcoin; ?>">
	<input type="hidden" name="useAlipay" value="<?php echo $checkout_form->useAlipay; ?>">
	<button class="fullstripe_checkout_button <?php echo ( $checkout_form->disableStyling == '0' ) ? 'stripe-button-el' : '' ?> " type="submit">
		<span class="fullstripe_checkout_button_text" <?php echo ( $checkout_form->disableStyling == '0' ) ? 'style="display: block; min-height: 30px;"' : '' ?> ><?php MM_WPFS::echo_translated_label( $checkout_form->openButtonTitle ); ?></span>
	</button>
	<img src="<?php echo plugins_url( '/img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="loading-animation" id="show-loading__<?php echo $form_id; ?>" style="display: none;"/>
</form>

