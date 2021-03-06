<?php get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/page/content', 'event-page' );

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
function isZeroReservation(){
	global $wpdb,$EM_Event;

	$sql = sprintf("select count(*) as ct from wa_em_bookings where event_id= %d and booking_status in (0,1)",
			$EM_Event->event_id);
	$res = $wpdb->get_results($sql);
	return ($EM_Event->event_start_date == date("Y-m-d")
			&& ($res[0]->ct == 0));
}
function freetrial_enable(){
	global $wpdb;
	$u = wp_get_current_user();
	$sql = sprintf("select count(*) as ct from wa_em_bookings where person_id = %d and booking_status in (0,1)",
			$u->id);
	$res = $wpdb->get_results($sql);
	return (!($res[0]->ct > 0));
}
function isOneday(){
	global $wpdb,$auth,$EM_Event;
	$sql = "select count(*) as ct from wa_onopod_oneday_tbl "
		. "where email = '" . $auth->userData->email . "' "
		. "and oneday_date_first <= '" . $EM_Event->event_start_date . "' "
		. "and adddate(oneday_date_first,interval 1 month) > '" . $EM_Event->event_start_date . "' "
		. "and (oneday_date is null or oneday_date ='" . $EM_Event->event_start_date . "')";

	$res = $wpdb->get_results($sql);
	return ($res[0]->ct > 0);
}
function isReservable($u,$cs){
	if(isZeroReservation()){
		//当日になっても予約が0件
		return 13;
	}else if(is_null($u->membership_level)){
		//未ログイン
		return 11;
	}else{
		$str = "cource-".$u->membership_level;
		if($u->account_state != "active"){
			//期限切れ
			return 12;
		}else{
			//契約しているコース内
			foreach($cs as $c){
				if("cource-".$u->membership_level == $c->slug){
					return 1;
				}
			}
			if(freetrial_enable()){
				//無料体験が使用可能
				return 2;
			}else if(isOneday()){
				//1日受け放題適用可能
				return 3;
			}else{
				//適用なし 1日受け放題ボタンを表示
				return 0;
			}
		}
	}
}
$auth = SwpmAuth::get_instance();
$oneday_onclick = sprintf("location.href='%s/oneday/?d=%s&pid=%s'",
						site_url(),
						$EM_Event->event_start_date,
						$EM_Event->post_id);
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
			//適用なし
			bstr = '<input type="button" class="em-booking-submit" id="button_oneday" value="一日受け放題コースを購入" onClick="' + "<?php echo $oneday_onclick; ?>" + '">';
			$(".tr_booking").html(bstr);
		}else if(reservable==1){
			//予約可能なレッスン
		}else if(reservable==2){
			//無料体験
			$("#em-booking-submit").val("無料体験予約");
			$("#em-booking-submit").after("<p>※お一人様一回限り</p>");
			$("#em-booking textarea[name=booking_comment]").val("firstfree:初回無料体験");
			
		}else if(reservable==3){
			//一日受け放題で予約可能なレッスン
			$("#em-booking-submit").val("1日受け放題で予約");
			$("#em-booking textarea[name=booking_comment]").val("oneday:1日受け放題");
		}else if(reservable==11){
			//未ログイン
			$("#em-booking-submit").val("無料体験予約");
			$("#em-booking-submit").after("<p>※お一人様一回限り</p>");
			$("#em-booking textarea[name=booking_comment]").val("firstfree:登録無し無料体験");
		}else if(reservable==12){
			//アカウントが無効、期限切れ
			str = "アカウントが無効、期限切れのため、レッスンを予約できません。";
			$(".tr_booking").html(str);
		}else if(reservable==13){
			//当日になっても予約が0件
			//str = "・午前0時までに予約が入らなかったので休講となりました。";
			str = "・このレッスンのご予約は終了となりました。";
			$(".tr_booking").html(str);
		}

		$("label[for=booking_comment],textarea[name=booking_comment],.em-tickets-spaces").hide();
	}

});
</script>
<?php
get_footer();
