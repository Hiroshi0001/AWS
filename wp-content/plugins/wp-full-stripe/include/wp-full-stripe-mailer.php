<?php

/**
 * Created by PhpStorm.
 * User: tnagy
 * Date: 2016.02.26.
 * Time: 14:16
 */
class MM_WPFS_Mailer {

	public function send_email( $email, $subject, $message ) {
		$options = get_option( 'fullstripe_options' );

		$name = html_entity_decode( get_bloginfo( 'name' ) );

		$admin_email  = get_bloginfo( 'admin_email' );
		$sender_email = $admin_email;
		if ( isset( $options['email_receipt_sender_address'] ) && ! empty( $options['email_receipt_sender_address'] ) ) {
			$sender_email = $options['email_receipt_sender_address'];
		}
		$headers[] = "From: $name <$sender_email>";

		$headers[] = "Content-type: text/html";

		wp_mail( $email,
			apply_filters( 'fullstripe_email_subject_filter', $subject ),
			apply_filters( 'fullstripe_email_message_filter', $message ),
			apply_filters( 'fullstripe_email_headers_filter', $headers ) );

		if ( $options['admin_payment_receipt'] == 'website_admin' || $options['admin_payment_receipt'] == 'sender_address' ) {
			$receipt_to = $admin_email;
			if ( $options['admin_payment_receipt'] == 'sender_address' && isset( $options['email_receipt_sender_address'] ) && ! empty( $options['email_receipt_sender_address'] ) ) {
				$receipt_to = $options['email_receipt_sender_address'];
			}
			wp_mail( $receipt_to,
				"COPY: " . apply_filters( 'fullstripe_email_subject_filter', $subject ),
				apply_filters( 'fullstripe_email_message_filter', $message ),
				apply_filters( 'fullstripe_email_headers_filter', $headers ) );
		}
	}

	public function send_payment_email_receipt( $email, $currency, $amount, $billingName, $billingAddress, $productName, $custom_input_values = null ) {

		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$date_format = get_option( 'date_format' );
		$options     = get_option( 'fullstripe_options' );
		$name        = get_bloginfo( 'name' );

		$emailReceipts   = json_decode( $options['email_receipts'] );
		$subject         = $emailReceipts->paymentMade->subject;
		$message         = stripslashes( $emailReceipts->paymentMade->html );
		$currency_symbol = MM_WPFS::get_currency_symbol_for( $currency );

		$message = str_replace(
			MM_WPFS_Utils::get_payment_macros(),
			array(
				sprintf( '%s%0.2f', $currency_symbol, $amount / 100 ),
				$name,
				$billingName,
				$email,
				$billingAddress['line1'],
				$billingAddress['line2'],
				$billingAddress['city'],
				$billingAddress['state'],
				$billingAddress['country'],
				$billingAddress['zip'],
				$productName,
				date( $date_format )
			),
			$message );

		$message = MM_WPFS_Utils::replace_custom_fields( $message, $custom_input_values );

		$this->send_email( $email, $subject, $message );
	}

	public function send_subscription_started_email_receipt( $email, $plan_name, $plan_currency, $plan_setup_fee, $plan_amount, $cardholder_name, $billing_address, $product_name, $custom_input_values = null ) {
		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$date_format = get_option( 'date_format' );
		$options     = get_option( 'fullstripe_options' );
		$name        = get_bloginfo( 'name' );

		$emailReceipts   = json_decode( $options['email_receipts'] );
		$subject         = $emailReceipts->subscriptionStarted->subject;
		$message         = stripslashes( $emailReceipts->subscriptionStarted->html );
		$currency_symbol = MM_WPFS::get_currency_symbol_for( $plan_currency );

		$message = str_replace(
			MM_WPFS_Utils::get_subscription_started_macros(),
			array(
				sprintf( '%s%0.2f', $currency_symbol, $plan_setup_fee / 100 ),
				$plan_name,
				sprintf( '%s%0.2f', $currency_symbol, $plan_amount / 100 ),
				sprintf( '%s%0.2f', $currency_symbol, $plan_amount / 100 ),
				$name,
				$cardholder_name,
				$email,
				$billing_address['line1'],
				$billing_address['line2'],
				$billing_address['city'],
				$billing_address['state'],
				$billing_address['country'],
				$billing_address['zip'],
				$product_name,
				date( $date_format )
			),
			$message );

		$message = MM_WPFS_Utils::replace_custom_fields( $message, $custom_input_values );

		$this->send_email( $email, $subject, $message );
	}

	public function send_subscription_finished_email_receipt( $email, $plan_name, $plan_currency, $plan_amount, $cardholder_name, $billing_address, $product_name ) {
		if ( defined( 'WP_FULL_STRIPE_DEMO_MODE' ) ) {
			return;
		}

		$date_format = get_option( 'date_format' );
		$options     = get_option( 'fullstripe_options' );
		$name        = get_bloginfo( 'name' );

		$emailReceipts   = json_decode( $options['email_receipts'] );
		$subject         = $emailReceipts->subscriptionFinished->subject;
		$message         = stripslashes( $emailReceipts->subscriptionFinished->html );
		$currency_symbol = MM_WPFS::get_currency_symbol_for( $plan_currency );

		$message = str_replace(
			MM_WPFS_Utils::get_subscription_finished_macros(),
			array(
				$plan_name,
				sprintf( '%s%0.2f', $currency_symbol, $plan_amount / 100 ),
				sprintf( '%s%0.2f', $currency_symbol, $plan_amount / 100 ),
				$name,
				$cardholder_name,
				$email,
				$billing_address['line1'],
				$billing_address['line2'],
				$billing_address['city'],
				$billing_address['state'],
				$billing_address['zip'],
				$product_name,
				date( $date_format )
			),
			$message );

		$this->send_email( $email, $subject, $message );
	}

}