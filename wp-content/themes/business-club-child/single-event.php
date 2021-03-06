<?php get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->

<?php
function isOneday(){
	global $wpdb,$auth,$EM_Event;
	$sql = "select count(*) as ct from wa_swpm_payments_view "
		."where email = '" . $auth->userData->email . "' and oneday = '".$EM_Event->event_start_date."'";
	$res = $wpdb->get_results($sql);
	return ($res[0]->ct > 0);
}
function isReservable($u,$cs){
	if(is_null($u->membership_level)){
		return 0;
	}else{
		$str = "cource-".$u->membership_level;
		if(isOneday()){
			return 4;
		}else if($u->account_state != "active"){
			return 3;
		}else{
			foreach($cs as $c){
				if("cource-".$u->membership_level == $c->slug){
					return 1;
				}
			}
		}
		return 2;
	}
}
$auth = SwpmAuth::get_instance();
?>
<script>
jQuery(document).ready(function($){
	bstr = "";
	str = "";
	reservable = <?php echo isReservable($auth->userData,$EM_Event->categories); ?>;
	
	if(typeof $("#em-booking form").html() === "undefined"){
		//予約受付終了
		//既に予約
	}else{
		if(reservable==0){
			//未ログイン
			$("#em-booking-submit").val("無料体験予約");
			$("#em-booking textarea[name=booking_comment]").val("無料体験");
		}else if(reservable==1){
			//予約可能なレッスン
			str = "このレッスンは現在のコースで予約可能です。";
			str = str + '<a href="/any/membership-login/">コースを確認</a>';
			$("#em-booking").before(str);
		}else if(reservable==4){
			//一日受け放題で予約可能なレッスン
			str = "このレッスンは一日受け放題で予約可能です。";
			str = str + '<a href="/any/membership-login/">コースを確認</a>';
			$("#em-booking").before(str);
		}else if(reservable==2){
			//予約不可能なレッスン
			str = "このレッスンは現在のコースで予約できません。";
			str = str + '<a href="/any/membership-login/">コースを確認</a>';
			$("#em-booking").before(str);
			
			$("#em-booking-submit").val("無料体験予約");
			$("#em-booking textarea[name=booking_comment]").val("無料体験");
			
			bstr = '<a href="https://www.onopod.tk/any/oneday/?d=<?php echo $EM_Event->event_start_date; ?>&pid=<?php echo $EM_Event->post_id; ?>">一日受け放題購入画面を開く</a>';
			$("#em-booking-submit").after(bstr);
		}else if(reservable==3){
			//アカウントが無効、期限切れ
			str = "アカウントが無効、期限切れのため、レッスンを予約できません。";
			str = str + '<a href="/any/membership-login/">コースを確認</a>';
			$("#em-booking").before(str);
			
			$("#em-booking-submit").val("無料体験予約");
			$("#em-booking textarea[name=booking_comment]").val("無料体験");
		}
		$("label[for=booking_comment],textarea[name=booking_comment],.em-tickets-spaces").hide();
	}

});
</script>
<?php
get_footer();
