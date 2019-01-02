<?php
/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2017.02.23.
 * Time: 14:57
 */
?>
<h2 id="create-payment-form-tabs" class="nav-tab-wrapper wpfs-admin-form-tabs">
	<a href="#create-payment-form-tab-payment" class="nav-tab"><?php esc_html_e( 'Payment', 'wp-full-stripe' ); ?></a>
	<a href="#create-payment-form-tab-appearance" class="nav-tab"><?php esc_html_e( 'Appearance', 'wp-full-stripe' ); ?></a>
	<a href="#create-payment-form-tab-custom-fields" class="nav-tab"><?php esc_html_e( 'Custom Fields', 'wp-full-stripe' ); ?></a>
	<a href="#create-payment-form-tab-actions-after-payment" class="nav-tab"><?php esc_html_e( 'Actions after payment', 'wp-full-stripe' ); ?></a>
</h2>
<form class="form-horizontal wpfs-admin-form" action="" method="POST" id="create-payment-form">
	<p class="tips"></p>
	<input type="hidden" name="action" value="wp_full_stripe_create_payment_form">
	<div id="create-payment-form-tab-payment" class="wpfs-tab-content">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Form Type:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td><?php esc_html_e( 'Inline payment form', 'wp-full-stripe' ); ?></td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Form Name:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_name" id="form_name" maxlength="<?php echo $form_data::NAME_LENGTH; ?>">

					<p class="description"><?php esc_html_e( 'This name will be used to identify this form in the shortcode i.e. [fullstripe_payment form="FormName"]', 'wp-full-stripe' ); ?></p>
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
								if ( "usd" === $currency_key ) {
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
					<label class="control-label"><?php esc_html_e( 'Payment Type: ', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_custom" id="set_specific_amount" value="specified_amount" checked="checked">
						<?php esc_html_e( 'Set Amount', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_custom" id="set_amount_list" value="list_of_amounts">
						<?php esc_html_e( 'Select Amount from List', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_custom" id="set_custom_amount" value="custom_amount">
						<?php esc_html_e( 'Custom Amount', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'Choose to set a specific amount or a list of amounts for this form, or allow customers to set custom amounts.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top" id="payment_amount_row">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Payment Amount:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_amount" id="form_amount"/>

					<p class="description"><?php esc_html_e( 'The amount this form will charge your customer, in cents. i.e. for $10.00 enter 1000.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top" id="payment_amount_list_row" style="display: none;">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Payment Amount Options:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<a href="#" class="button button-primary" id="add_payment_amount_button"><?php esc_html_e( 'Add', 'wp-full-stripe' ); ?></a>
					<input type="text" id="payment_amount_value" placeholder="<?php esc_attr_e( 'Amount', 'wp-full-stripe' ); ?>" maxlength="<?php echo $form_data::PAYMENT_AMOUNT_LENGTH; ?>"><input type="text" id="payment_amount_description" placeholder="<?php esc_attr_e( 'Description', 'wp-full-stripe' ); ?>" maxlength="<?php echo $form_data::PAYMENT_AMOUNT_DESCRIPTION_LENGTH; ?>"><br>
					<ul id="payment_amount_list"></ul>
					<input type="hidden" name="payment_amount_values">
					<input type="hidden" name="payment_amount_descriptions">

					<p class="description"><?php esc_html_e( 'The amount in cents, i.e. for $10.00 enter 1000. The description will be displayed in the dropdown for the amount. Use the {amount} placeholder to include the amount value. You can use drag\'n\'drop to reorder the payment amounts.', 'wp-full-stripe' ); ?></p>
					<label class="checkbox inline"><input type="checkbox" name="allow_custom_payment_amount" id="allow_custom_payment_amount" value="1"><?php esc_html_e( 'Allow Custom Amount to Be Entered?', 'wp-full-stripe' ); ?>
					</label>
				</td>
			</tr>
		</table>
	</div>
	<div id="create-payment-form-tab-appearance" class="wpfs-tab-content">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Form Style:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<select class="regular-text" name="form_style" id="form_style">
						<option value="0"><?php esc_html_e( 'Default', 'wp-full-stripe' ); ?></option>
						<option value="1"><?php esc_html_e( 'Compact', 'wp-full-stripe' ); ?></option>
					</select>

					<p class="description"><?php esc_html_e( 'Choose how you\'d like the form to look. (More coming soon!)', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Form Title:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_title" id="form_title" maxlength="<?php echo $form_data::FORM_TITLE_LENGTH; ?>" value="<?php esc_attr_e( 'Payment form', 'wp-full-stripe' ); ?>">

					<p class="description"><?php esc_html_e( 'The title of the form.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Payment Button Text:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_button_text" id="form_button_text" value="<?php esc_attr_e( 'Make Payment', 'wp-full-stripe' ); ?>" maxlength="<?php echo $form_data::BUTTON_TITLE_LENGTH; ?>">

					<p class="description"><?php esc_html_e( 'The text on the payment button.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Include Amount on Button?', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_button_amount" id="hide_button_amount" value="0">
						<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_button_amount" id="show_button_amount" value="1" checked="checked">
						<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'For set amount forms, choose to show/hide the amount on the payment button.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Include Billing Address Field?', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_show_address_input" id="hide_address_input" value="0" checked="checked">
						<?php esc_html_e( 'Hide', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_show_address_input" id="show_address_input" value="1">
						<?php esc_html_e( 'Show', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'Should this payment form also ask for the customers billing address?', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
		</table>
	</div>
	<div id="create-payment-form-tab-custom-fields" class="wpfs-tab-content">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Include Custom Input Fields?', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_include_custom_input" id="noinclude_custom_input" value="0" checked="checked">
						<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_include_custom_input" id="include_custom_input" value="1">
						<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'You can ask for extra information from the customer to be included in the payment details.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
		</table>
		<table id="customInputSection" class="form-table" style="display: none;">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Make Custom Input Fields Required?', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_custom_input_required" id="custom_input_required_no" value="0" checked="checked">
						<?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_custom_input_required" id="custom_input_required_yes" value="1">
						<?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Number of inputs:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<select id="customInputNumberSelect">
						<option value="1" selected="selected">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
					</select>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Custom Input Label 1:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_custom_input_label_1" id="form_custom_input_label_1"/>

					<p class="description"><?php esc_html_e( 'The text for the label next to the custom input field.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<tr valign="top" style="display: none;" class="ci2">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Custom Input Label 2:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_custom_input_label_2" id="form_custom_input_label_2"/>
				</td>
			</tr>
			<tr valign="top" style="display: none;" class="ci3">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Custom Input Label 3:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_custom_input_label_3" id="form_custom_input_label_3"/>
				</td>
			</tr>
			<tr valign="top" style="display: none;" class="ci4">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Custom Input Label 4:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_custom_input_label_4" id="form_custom_input_label_4"/>
				</td>
			</tr>
			<tr valign="top" style="display: none;" class="ci5">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Custom Input Label 5:', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<input type="text" class="regular-text" name="form_custom_input_label_5" id="form_custom_input_label_5"/>
				</td>
			</tr>
		</table>
	</div>
	<div id="create-payment-form-tab-actions-after-payment" class="wpfs-tab-content">
		<table class="form-table">
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Send Email Receipt?', 'wp-full-stripe' ); ?> </label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_send_email_receipt" value="0" checked="checked"> <?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_send_email_receipt" value="1"> <?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'Send an email receipt on successful payment?', 'wp-full-stripe' ); ?> </p>
				</td>
			</tr>
			<tr valign="top">
				<th scope="row">
					<label class="control-label"><?php esc_html_e( 'Redirect On Success?', 'wp-full-stripe' ); ?></label>
				</th>
				<td>
					<label class="radio inline">
						<input type="radio" name="form_do_redirect" id="do_redirect_no" value="0" checked="checked"> <?php esc_html_e( 'No', 'wp-full-stripe' ); ?>
					</label>
					<label class="radio inline">
						<input type="radio" name="form_do_redirect" id="do_redirect_yes" value="1"> <?php esc_html_e( 'Yes', 'wp-full-stripe' ); ?>
					</label>

					<p class="description"><?php esc_html_e( 'When payment is successful you can choose to redirect to another page or post.', 'wp-full-stripe' ); ?></p>
				</td>
			</tr>
			<?php include( 'redirect_to_for_create.php' ); ?>
		</table>
	</div>
	<p class="submit">
		<button class="button button-primary" type="submit"><?php esc_html_e( 'Create Form', 'wp-full-stripe' ); ?></button>
		<a href="<?php echo admin_url( 'admin.php?page=fullstripe-payments&tab=forms' ); ?>" class="button"><?php esc_html_e( 'Cancel', 'wp-full-stripe' ); ?></a>
		<img src="<?php echo plugins_url( '../img/loader.gif', dirname( __FILE__ ) ); ?>" alt="<?php esc_attr_e( 'Loading...', 'wp-full-stripe' ); ?>" class="showLoading"/>
	</p>
</form>
