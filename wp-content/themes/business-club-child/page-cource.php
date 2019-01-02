<?php
$auth = SwpmAuth::get_instance();
get_header();?>
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

});
</script>
<?php get_footer();
