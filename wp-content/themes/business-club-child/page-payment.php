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
$sql = "select * from wa_swpm_payments_view "
	."where email = '" . $auth->userData->email . "' order by txn_date desc";
$results = $wpdb->get_results($sql);
$str = "";
foreach ($results as $value) {
	$str .= '<li>'.$value->txn_date." - ".$value->alias.'</li>';
}
if($str!=""){
	echo '<ul class="entry-content">'.$str."</ul>";
}

			}
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .wrap -->
<script>
jQuery(document).ready(function($){
});
</script>
<?php get_footer();
