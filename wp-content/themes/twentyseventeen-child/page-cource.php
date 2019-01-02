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
			if($auth->userData->membership_level != "5"){
				//コースを購入済
$str =<<<EOD
<div class="entry-content">
<p>既にコースを購入済です。コースを変更する場合は、先にコースの解約を行って下さい。</p>
<a href="%s/payment"/>支払状況確認</a>
</div>
EOD;

echo sprintf($str,home_url());

			}else{
while ( have_posts() ) : the_post();
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php twentyseventeen_edit_link( get_the_ID() ); ?>
	</header><!-- .entry-header -->
	<div class="entry-content">
	<p><a href="/admission">規約</a>に同意の上、下記の入力フォームでコースを選択してください。</p>
		<?php
			the_content();
			wp_link_pages( array(
				'before' => '<div class="page-links">' . __( 'Pages:', 'twentyseventeen' ),
				'after'  => '</div>',
			) );
		?>
	</div><!-- .entry-content -->
</article><!-- #post-## -->
<?php
endwhile; // End of the loop.
	}
}
?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->
<script>
jQuery(document).ready(function($){
	$("[id^='fullstripe_email']").val("<?php echo $auth->userData->email; ?>");
	id = "[id^='fullstripe-plan-details']";
	str = $(id).text().replace(".",",").replace("per month","/月");
	$(id).text(str);
	$(id).hide();
	$("option[value=p<?php echo $auth->userData->membership_level; ?>]").remove();

});
</script>
<?php get_footer();
