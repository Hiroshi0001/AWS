<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2017.02.21.
 * Time: 16:11
 */

?>
<h2 id="edit-checkout-form-tabs" class="nav-tab-wrapper wpfs-admin-form-tabs">
	<a href="#edit-checkout-form-tab-payment" class="nav-tab"><?php esc_html_e( 'Payment', 'wp-full-stripe' ); ?></a>
	<a href="#edit-checkout-form-tab-appearance" class="nav-tab"><?php esc_html_e( 'Appearance', 'wp-full-stripe' ); ?></a>
	<a href="#edit-checkout-form-tab-actions-after-payment" class="nav-tab"><?php esc_html_e( 'Actions after payment', 'wp-full-stripe' ); ?></a>
</h2>
<form class="form-horizontal wpfs-admin-form" action="" method="POST" id="edit-checkout-form">
	<p class="tips"></p>
	<input type="hidden" name="action" value="wp_full_stripe_edit_checkout_form">
	<input type="hidden" name="formID" value="<?php echo $form->checkoutFormID; ?>">
	<div id="edit-checkout-form-tab-payment" class="wpfs-tab-content">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Form Type:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td><?php esc_html_e( 'Popup payment form', 'wp-full-stripe' ); ?></td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Form Name:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_name_ck" id="form_name_ck" value="<?php echo $form->name; ?>" maxlength="<?php echo $form_data::NAME_LENGTH; ?>">

					<p class="description"><?php esc_html_e( 'This name will be used to identify this form in the shortcode i.e. [fullstripe_checkout form="FormName"].', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label" for="currency"><?php esc_html_e( "Payment Currency: ", 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<div class="ui-widget">
						<select id="currency" name="form_currency">
							<option value=""><?php esc_attr_e( 'Select from the list or start typing', 'wp-full-stripe' ); ?></option>
							<?php
							foreach ( MM_WPFS::get_available_currencies() as $currency_key => $currency_obj ) {
								$option = '<option value="' . $currency_key . '"';
								$option .= ' data-currency-symbol="' . MM_WPFS::get_currency_symbol_for( $currency_key ) . '""';
								if ( $form->currency === $currency_key ) {
									$option .= ' selected="selected"';
								}
								$option .= '>';
								$option .= $currency_obj['name'] . ' (' . $currency_obj['code'] . ')';
								$option .= '</option>';
								echo $option;
							}
							?>
						</select>
					</div>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Payment Amount:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_amount_ck" id="form_amount_ck" value="<?php echo $form->amount; ?>"/>

					<p class="description"><?php esc_html_e( 'The amount this form will charge your customer, in cents, i.e. for $10.00 enter 1000.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Use Bitcoin?', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<span id="bitcoin_usage_info_panel" <?php echo ($form->currency === "usd") ? 'style="display: none;"' : ''?>>
						<p class="alert alert-info"><?php printf( __( "In order to use Bitcoin for payments, you have to set the form currency to USD, and you have to link an US bank account to your Stripe account, then <a href=\"%s\">enable Bitcoin</a> on your Stripe account.", "wp-full-stripe" ), admin_url( "admin.php?page=fullstripe-settings" ), "https://dashboard.stripe.com/account/bitcoin/enable" ); ?></p>
					</span>
					<span id="bitcoin_usage_panel" <?php echo ($form->currency === "usd") ? '' : 'style="display: none;"'?>>
						<label class="radio inline">
							<input type="radio" name="form_use_bitcoin" id="use_bitcoin_no" value="0" <?php echo ( $form->useBitcoin == '0' ) ? 'checked' : '' ?> >
							<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
						</label>
						<label class="radio inline">
							<input type="radio" name="form_use_bitcoin" id="use_bitcoin_yes" value="1" <?php echo ( $form->useBitcoin == '1' ) ? 'checked' : '' ?> >
							<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Allow to use Bitcoin for payments.', 'wp-full-stripe' ); ?></p>
					</span>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Use Alipay?', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<span id="alipay_usage_info_panel" <?php echo ($form->currency === "usd") ? 'style="display: none;"' : ''?>>
						<p class="alert alert-info"><?php printf( __( "In order to use AliPay for payments, you have to set the form currency to USD, and you have to link an US bank account to your Stripe account.", "wp-full-stripe" ), admin_url( "admin.php?page=fullstripe-settings" ) ); ?></p>
					</span>
					<span id="alipay_usage_panel" <?php echo ($form->currency === "usd") ? '' : 'style="display: none;"'?>>
						<label class="radio inline">
							<input type="radio" name="form_use_alipay" id="use_alipay_no" value="0" <?php echo ( $form->useAlipay == '0' ) ? 'checked' : '' ?> >
							<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
						</label>
						<label class="radio inline">
							<input type="radio" name="form_use_alipay" id="use_alipay_yes" value="1" <?php echo ( $form->useAlipay == '1' ) ? 'checked' : '' ?> >
							<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Accept payments from hundreds of millions of new customers using Alipay, China’s most popular payment method.', 'wp-full-stripe' ); ?></p>
					</span>
				</td>
			</tr>
		</table>
	</div>
	<div id="edit-checkout-form-tab-appearance" class="wpfs-tab-content">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Form Title:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="company_name_ck" id="company_name_ck" value="<?php echo $form->companyName; ?>" maxlength="<?php echo $form_data::COMPANY_NAME_LENGTH; ?>">

					<p class="description"><?php esc_html_e( 'Used as the title of the checkout form.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Product Description:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="prod_desc_ck" id="prod_desc_ck" value="<?php echo $form->productDesc; ?>" maxlength="<?php echo $form_data::PRODUCT_DESCRIPTION_LENGTH; ?>">

					<p class="description"><?php esc_html_e( 'A short description (one line) about the product sold using this form.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Open Form Button Text:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="open_form_button_text_ck" id="open_form_button_text_ck" value="<?php echo $form->openButtonTitle; ?>" maxlength="<?php echo $form_data::OPEN_BUTTON_TITLE_LENGTH; ?>">

					<p class="description"><?php esc_html_e( 'The text on the button used to pop open this form.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Payment Button Text:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_button_text_ck" id="form_button_text_ck" value="<?php echo $form->buttonTitle; ?>" maxlength="<?php echo $form_data::BUTTON_TITLE_LENGTH; ?>">

					<p class="description"><?php esc_html_e( 'The text on the payment button. Use {{amount}} to show the payment amount on this button.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Include Billing Address Field?', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_show_address_input_ck" id="hide_address_input_ck" value="0" <?php echo ( $form->showBillingAddress == '0' ) ? 'checked' : '' ?> >
						<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_show_address_input_ck" id="show_address_input_ck" value="1" <?php echo ( $form->showBillingAddress == '1' ) ? 'checked' : '' ?> >
						<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'Should this payment form also ask for the customers billing address?', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Include Remember Me Field?', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_show_remember_me_ck" id="hide_remember_me_ck" value="0" <?php echo ( $form->showRememberMe == '0' ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_show_remember_me_ck" id="show_remember_me_ck" value="1" <?php echo ( $form->showRememberMe == '1' ) ? 'checked' : '' ?> >
						<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'Show the Stripe Remember Me checkbox, allowing users to save their information with Stripe for later use.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Image:', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<input id="form_checkout_image" type="text" name="form_checkout_image" value="<?php echo $form->image; ?>" maxlength="<?php echo $form_data::IMAGE_LENGTH; ?>" placeholder="<?php esc_attr_e( 'Enter image URL', 'wp-full-stripe' ); ?>">
					<button id="upload_image_button" class="button" type="button" value="Upload Image"><?php esc_html_e( 'Upload Image', 'wp-full-stripe' ); ?></button>
					<p class="description"><?php esc_html_e( 'A square image of your brand or product which is shown on the form. Min size 128px x 128px.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Disable Button Styling?', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_disable_styling_ck" id="form_disable_styling_ck_no" value="0" <?php echo ( $form->disableStyling == '0' ) ? 'checked' : '' ?> >
						<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_disable_styling_ck" id="form_disable_styling_ck_yes" value="1" <?php echo ( $form->disableStyling == '1' ) ? 'checked' : '' ?> >
						<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'Disable the styling on the checkout button if you are noticing conflicts with your theme.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
		</table>
	</div>
	<div id="edit-checkout-form-tab-actions-after-payment" class="wpfs-tab-content">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Send Email Receipt?', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_send_email_receipt" value="0" <?php echo ( $form->sendEmailReceipt == '0' ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_send_email_receipt" value="1" <?php echo ( $form->sendEmailReceipt == '1' ) ? 'checked' : '' ?>>
						<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'Send an email receipt on successful payment?', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Redirect On Success?', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_do_redirect_ck" id="do_redirect_no_ck" value="0" <?php echo ( $form->redirectOnSuccess == '0' ) ? 'checked' : '' ?> >
						<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_do_redirect_ck" id="do_redirect_yes_ck" value="1" <?php echo ( $form->redirectOnSuccess == '1' ) ? 'checked' : '' ?> >
						<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'When payment is successful you can choose to redirect to another page or post.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<?php include( 'redirect_to_for_edit_checkout.php' ); ?>
		</table>
	</div>
	<p class="submit">
		<button class="button button-primary" type="submit"><?php esc_html_e( 'Save Changes', 'wp-full-stripe' ); ?></button>
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-payments&tab=forms' ); ?>" class="button"><?php esc_html_e( 'Cancel', 'wp-full-stripe' ); ?></a>
		<img src="<?php echo plugins_url( '../img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
	</p>
</form>
