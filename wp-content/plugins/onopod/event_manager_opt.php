<?php
/**** イベントマネージャー関連の追加項目 ****/
/* 独自のプレースホルダーを作成 */
function my_em_custom_placeholders($replace, $EM_Event, $result){
	if(is_array($EM_Event->attributes["level"])){
		$EM_Event->attributes["level"] = $EM_Event->attributes["level"][0];
	}
	switch( $result ){
		case '#_CATEGORYC':
			$arr = array();
			foreach($EM_Event->get_categories() as $EM_Category){
				$arr[] = $EM_Category->slug . ",";
			}
			$replace = implode(",",$arr);
		break;
		case '#_TAGC':
			$arr = array();
			$tags = get_the_terms($EM_Event->post_id, EM_TAXONOMY_TAG);
			if($tags){
				foreach($tags as $tag){ 
					$arr[] = $tag->slug;
				}
			}
			$replace = implode(",",$arr);
		break;
		case "#_ATT{level}":
		case '#_LEVELC':
			$replace = $EM_Event->attributes["level"];
		break;
		case '#_OWNERC':
			$replace = $EM_Event->event_owner;
		break;
		case '#_OWNERN':
			$u = new WP_User($EM_Event->event_owner);
			$replace = $u->last_name ? $u->last_name : "未定";
		break;
		case '#_OWNER':
			$u = new WP_User($EM_Event->event_owner);
			$replace = $u->last_name ? sprintf('<a href="%s/?author=%s">%s</a>',
				site_url(),
				$u->id,
				$u->last_name." ".$u->first_name) : "未定";
		break;
		case '#_EVENTSYMBOL':
			$eventname = $EM_Event->event_name;
			switch( $eventname ){
				case 'ジルバ＆ブルース':
					$replace ='J&B';
				break;
				case 'ルンバ':
					$replace ='R';
				break;
				case 'チャチャチャ':
					$replace ='C';
				break;
				case 'サンバ':
					$replace ='S';
				break;
				case 'ジャイブ':
					$replace ='J';
				break;
				case 'パソドブレ':
					$replace ='P';
				break;
				case 'ワルツ':
					$replace ='W';
				break;
				case 'タンゴ':
					$replace ='T';
				break;
				case 'スロー':
				case 'スローフォックストロット':
					$replace ='F';
				break;
				case 'クイック':
				case 'クイックステップ':
					$replace ='Q';
				break;
				case 'ヴェニーズ':
				case 'ヴェニーズワルツ':
					$replace ='V';
				break;
				default:
					$replace = $eventname;
				break;
			}
		break;
		case '#_LEVELSHORT':
			switch($EM_Event->attributes["level"]){
				case '★':
					$replace = '★1';		
				break;
				case '★★':
					$replace = '★2';		
				break;
				case '★★★':
					$replace = '★3';		
				break;
				case '★★★★':
					$replace = '★4';		
				break;
				case '★★★★★':
					$replace = '★5';		
				break;
				default:
					$replace = $EM_Event->attributes["level"]; 
				break;
			}
			
		break;
	}
	return $replace;
}
add_filter('em_event_output_placeholder','my_em_custom_placeholders',1,3);

/* カレンダーに使用する検索をショートコードで追加 */
function onopod_search_func(){
	$t = '<label for="%s" style="font-weight:normal;display:inline;">
		<input type="%s" name="%s" value="%s" id="%s" %s>%s</label>&nbsp;';
	$n = "none";
	/* カテゴリー */
	$ca = sprintf($t,$n,"radio","category",$n,$n,"checked","選択なし");
	foreach(get_terms(EM_TAXONOMY_CATEGORY,array('orderby'=>'slug')) as $cat){
		$ca .= sprintf($t,$cat->slug,"radio","category",$cat->slug,$cat->slug,"",$cat->name);
	}
	/* タグ */
	foreach(get_terms(EM_TAXONOMY_TAG) as $tag){
		$tg .= sprintf($t,$tag->slug,"checkbox","tag",$tag->slug,$tag->slug,"",$tag->name);
	}
	
	/* レベル */
	for($i=1;$i<=5;$i++){
		$tmpstr = str_repeat("★",$i).str_repeat("☆",5-$i);
		$l .= sprintf('<option value="%s">%s</option>',$tmpstr,$tmpstr);
	}
	$lv = sprintf('<select name="level"><option value="none">未選択</option>%s</select>',$l);

	/* 講師 */
	foreach(get_users( array('role'=>'author')) as $u){
		if($u->last_name!=""){
			$c .= sprintf('<option value="%d">%s</option>',$u->id,$u->last_name." ".$u->first_name);
		}
	}
	$tc = sprintf('<select name="owner"><option value="none">未選択</option>%s</select>',$c);

	$str =<<<EOD
<table id="o_search">
<tr><th width="10%%">コース：</th><td colspan="3">%s</td></tr>
<!--<tr><th>特徴：</th><td colspan="3">%s</td></tr>
<tr><th>難易度：</th><td>%s</td><th width="10%%">講師：</th><td>%s</td></tr>-->
</table>
<script>
jQuery(function($){
	$("input[name=category],input[name=tag]").click(function(){
		flg = 0;
		$(".em-calendar li").css("background-color","#ffff00");
		$("#o_search input[type=radio]:checked,#o_search input[type=checkbox]:checked").each(function(){
			if($(this).val()!="none"){
				str = "[" + $(this).attr("name") + "*='" + $(this).val() + "']";
				$(".em-calendar li").not(str).css("background-color","");
				flg = 1;
			}
		});
		$("#o_search option:selected").each(function(){
			if($(this).val()!="none"){
				str = "[" + $(this).parent().attr("name") + "=" + $(this).val() + "]";
				$(".em-calendar li").not(str).css("background-color","");
				flg = 1;
			}
		});
		if(flg==0){
			$(".em-calendar li").css("background-color","");
		}
	});
	$("select[name=level],select[name=owner]").change(function(){
		flg = 0;
		$(".em-calendar li").css("background-color","#ffff00");
		$("#o_search input[type=radio]:checked,#o_search input[type=checkbox]:checked").each(function(){
			if($(this).val()!="none"){
				str = "[" + $(this).attr("name") + "*='" + $(this).val() + "']";
				$(".em-calendar li").not(str).css("background-color","");
				flg = 1;
			}
		});
		$("#o_search option:selected").each(function(){
			if($(this).val()!="none"){
				str = "[" + $(this).parent().attr("name") + "=" + $(this).val() + "]";
				$(".em-calendar li").not(str).css("background-color","");
				flg = 1;
			}
		});
		if(flg==0){
			$(".em-calendar li").css("background-color","");
		}
	});
});
</script>
EOD;
	return sprintf($str,$ca,$tg,$lv,$tc);
}
add_shortcode('onopod_search','onopod_search_func');
