<?php
$auth = SwpmAuth::get_instance();
get_header();
?>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
if(!$auth->userData){
	$msg = "ログインしていません。";
}else{
	$e = $auth->userData->email;

	// stripeに送信
	//wa_fullstripe_subscribersからstripeSubscriptionIDを取得
	$options = get_option( 'fullstripe_options' );
	$key_str = ($options['apiMode'] === 'test') ? 'secretKey_test':'secretKey_live';

	$sql =<<<EOD
select stripeSubscriptionID from wa_fullstripe_subscribers
where status = 'running'
and email = %s order by created desc limit 1
EOD;

	$s = $wpdb->get_row($wpdb->prepare($sql,$e));
	$res = sub_cancel($options[$key_str],$s->stripeSubscriptionID);
	if($res == ""){
		// wa_fullstripe_subscribersのstatusをcancelledに変更
		$wpdb->update("wa_fullstripe_subscribers", 
		      array( "status" => "cancelled"), 
		      array('email' => $e), 
		      array("%s"), 
		      array("%s"));
		// wa_swpm_members_tblのmembership_levelを5に変更
		$wpdb->update("wa_swpm_members_tbl", 
		      array( "membership_level" => 5), 
		      array('email' => $e), 
		      array("%d"), 
		      array("%s"));
		$msg = "解約を完了しました。";
	}else{
		$msg = "解約は既に完了しています。";
	//	var_dump($s->stripeSubscriptionID);
	//	var_dump($res);
	}

	while ( have_posts() ) : the_post();
		get_template_part( 'template-parts/page/content', 'page' );
	endwhile; // End of the loop.
}
echo "<p>".$msg."</p>";
?>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->
<?php
get_footer();

function sub_cancel( $key, $subID) {
	if ( $key != '' && $key != 'YOUR_TEST_SECRET_KEY' && $key != 'YOUR_LIVE_SECRET_KEY' && $subID != "") {
		try {
			\Stripe\Stripe::setApiKey( $key );
			$sub = \Stripe\Subscription::retrieve($subID);
			$sub->cancel();
		} catch ( Exception $e ) {
			return $e;
		}
	}else{
		return "subID[".$subID."]を確認してください。";
	}
}
