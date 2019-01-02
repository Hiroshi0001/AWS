<?php
/**
 * Search form.
 *
 * @package Business_Club
 */

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php echo esc_html_x( 'Search for:', 'label', 'business-club' ); ?></span>
		<input type="search" class="search-field" value="<?php echo get_search_query(); ?>" name="s" placeholder="<?php echo esc_attr_x( 'Search&hellip;', 'placeholder', 'business-club' ); ?>" />
	</label>
	<input type="submit" class="search-submit" value="<?php echo esc_attr( '&#xf002;' ); ?>" />
</form><!-- .search-form -->
