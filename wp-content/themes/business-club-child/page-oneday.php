<?php
$auth = SwpmAuth::get_instance();
get_header();
?>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
			if(!$auth->userData){
			echo "ログインしていません。";
			}else{
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/content', 'page' );

				endwhile; // End of the loop.
			}
			if(preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/",$_GET["d"])){
				$oneday_date = $_GET["d"];
			}
			if(is_numeric($_GET["pid"])){
				$pid = $_GET["pid"];
			}
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->
<script>
jQuery(document).ready(function($){
	oneday_date = "<?php echo $oneday_date; ?>";
	pid = "<?php echo $pid; ?>";
	if(oneday_date != ""){
		$("#fullstripe-custom-input__n1__1").val(oneday_date).attr("readonly","readonly");
	}
	if(pid != ""){
		$("#payment-form > fieldset > div:last-child")
			.append('<p>購入処理後に<a href="https://www.onopod.tk/any/events/?p=' + pid + '">詳細ページ</a>に戻り予約を完了してください。</p>');
	}

});
</script>
<?php get_footer();
