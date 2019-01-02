<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

get_header(); ?>

<div class="wrap">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/page/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->
<script>
jQuery(function($){

	$("#dbem-bookings-table td:nth-child(4)").each(function(){
		if($(this).text().indexOf("キャンセル") != -1){
			$(this).parent().hide();
		}
	});
	$("#dbem-bookings-table tr:visible").each(function(){
		if($(this).find("td:nth-child(5)").text().indexOf("キャンセル") != -1){
			c = new Date();
			d = new Date($(this).find("td:nth-child(2)").text());
			if(c > d){
				//$(this).find("td:nth-child(5)").text("");
			};
		}
	});

	$("#dbem-bookings-table > thead > tr").each(function(o){
		$(this).find("th").eq(0).before($(this).find("th").eq(1));
	});
	$("#dbem-bookings-table > tbody > tr").each(function(o){
		$(this).find("td").eq(0).before($(this).find("td").eq(1));
	});
	$("#dbem-bookings-table > thead > tr > th:nth-child(3),#dbem-bookings-table > tbody > tr > td:nth-child(3)").hide();
});
</script>
<?php get_footer();
