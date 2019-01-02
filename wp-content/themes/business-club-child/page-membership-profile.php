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
jQuery(function($){
	$("tr.swpm-registration-firstname-row")
		.insertBefo($("tr.swpm-profile-lastname-row"));
});
</script>
<?php get_footer();
