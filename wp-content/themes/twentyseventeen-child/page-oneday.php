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

					get_template_part( 'template-parts/page/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

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
		.append('<br><p>※購入処理後に<a href="<?php echo site_url(); ?>/events/?p=' + pid + '">詳細ページ</a>に戻り予約を完了してください。<br>※セキュリティコード：カード裏面の署名欄に印刷された3桁または4桁の数字です。</p>');
			

	}

});
</script>
<?php get_footer();
