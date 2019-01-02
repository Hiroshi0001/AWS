<?php
/* 予約確認ページに表示する1日受け放題の一覧をショートコードで追加 */
function onopod_oneday_list_func(){
	$u = wp_get_current_user();
	if($u->id){
		global $wpdb;
		$sql = "select * from wa_onopod_oneday_view where id = ".$u->id;
		$res = $wpdb->get_results($sql);
		foreach($res as $r){
			$tbody .= sprintf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>",
				$r->created,$r->event_name,$r->oneday_date_range);
		}
		
		$str = <<<EOD
<b>1日受け放題コース使用状況：</b>
<div class="table-wrap">
<table id="dbem-oneday-table" class="widefat post fixed">
<thead><tr>
  <th class="manage-column" scope="col">購入日</th>
  <th class="manage-column" scope="col">適用日(レッスン名)</th>
  <th class="manage-column" scope="col">使用可能期間</th>
</tr></thead>
<tbody>%s</tbody>
</table>
<p>※1日受け放題コースは、初回適用日から一か月の間に限り適用日の変更が可能です。</p>

</div>
<b>予約状況：</b>
EOD;
		return sprintf($str,$tbody);
	}
}
add_shortcode('onopod_oneday_list','onopod_oneday_list_func');

function onopod_oneday_list2_func(){
	$u = wp_get_current_user();
	if($u->id){

		$str = <<<EOD
<p>※レッスン開始後15分で参加者がいない場合、レッスンは休講となります。レッスンに遅刻した場合は、<a href="/contact">お問い合わせページ</a>より連絡をお願いします。</p>
EOD;
		return $str;
	}
}
add_shortcode('onopod_oneday_list2','onopod_oneday_list2_func');