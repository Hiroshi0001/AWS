<?php get_header();?>
<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'template-parts/content', 'page' );
				endwhile; // End of the loop.
			?>
		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->
<script>
jQuery(document).ready(function($){
	$(".swpm-registration-firstname-row")
		.insertAfter(jQuery(".swpm-registration-lastname-row"));
});
</script>
<?php get_footer();
