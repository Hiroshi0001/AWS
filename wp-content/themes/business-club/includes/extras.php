<?php
/**
 * Functions hooked to core hooks.
 *
 * @package Business_Club
 */

if ( ! function_exists( 'business_club_implement_excerpt_length' ) ) :

	/**
	 * Implement excerpt length.
	 *
	 * @since 1.0.0
	 *
	 * @param int $length The number of words.
	 * @return int Excerpt length.
	 */
	function business_club_implement_excerpt_length( $length ) {

		if ( is_admin() ) {
			return $length;
		}

		$excerpt_length = business_club_get_option( 'excerpt_length' );
		$excerpt_length = apply_filters( 'business_club_filter_excerpt_length', $excerpt_length );

		if ( absint( $excerpt_length ) > 0 ) {
			$length = absint( $excerpt_length );
		}

		return $length;

	}

endif;

add_filter( 'excerpt_length', 'business_club_implement_excerpt_length', 999 );

if ( ! function_exists( 'business_club_implement_read_more' ) ) :

	/**
	 * Implement excerpt read more.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more The string shown within the more link.
	 * @return string The excerpt.
	 */
	function business_club_implement_read_more( $more ) {

		return '&hellip;';

	}

endif;

add_filter( 'excerpt_more', 'business_club_implement_read_more' );

if ( ! function_exists( 'business_club_content_more_link' ) ) :

	/**
	 * Implement content read more.
	 *
	 * @since 1.0.0
	 *
	 * @param string $more_link Read More link element.
	 * @param string $more_link_text Read More text.
	 * @return string Link.
	 */
	function business_club_content_more_link( $more_link, $more_link_text ) {

		return '&hellip;';

	}

endif;

add_filter( 'the_content_more_link', 'business_club_content_more_link', 10, 2 );

if ( ! function_exists( 'business_club_custom_body_class' ) ) :

	/**
	 * Custom body class.
	 *
	 * @since 1.0.0
	 *
	 * @param string|array $input Array of classes.
	 * @return array Modified array of classes.
	 */
	function business_club_custom_body_class( $input ) {

		// Adds a class of group-blog to blogs with more than 1 published author.
		if ( is_multi_author() ) {
			$input[] = 'group-blog';
		}

		// Global layout.
		global $post;
		$global_layout = business_club_get_option( 'global_layout' );
		$global_layout = apply_filters( 'business_club_filter_theme_global_layout', $global_layout );

		// Check if single template.
		if ( $post && is_singular() ) {
			$post_options = get_post_meta( $post->ID, 'business_club_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$global_layout = $post_options['post_layout'];
			}
		}

		$input[] = 'global-layout-' . esc_attr( $global_layout );

		// Common class for three columns.
		switch ( $global_layout ) {
			case 'three-columns':
				$input[] = 'three-columns-enabled';
			break;

			default:
			break;
		}

		return $input;
	}

endif;

add_filter( 'body_class', 'business_club_custom_body_class' );

if ( ! function_exists( 'business_club_custom_content_width' ) ) :

	/**
	 * Custom content width.
	 *
	 * @since 1.0.0
	 */
	function business_club_custom_content_width() {

		global $post, $wp_query, $content_width;

		$global_layout = business_club_get_option( 'global_layout' );
		$global_layout = apply_filters( 'business_club_filter_theme_global_layout', $global_layout );

		// Check if single template.
		if ( $post  && is_singular() ) {
			$post_options = get_post_meta( $post->ID, 'business_club_settings', true );
			if ( isset( $post_options['post_layout'] ) && ! empty( $post_options['post_layout'] ) ) {
				$global_layout = $post_options['post_layout'];
			}
		}

		switch ( $global_layout ) {
			case 'no-sidebar':
				$content_width = 1220;
				break;

			case 'three-columns':
				$content_width = 570;
				break;

			case 'left-sidebar':
			case 'right-sidebar':
				$content_width = 895;
				break;

			default:
				break;
		}

	}
endif;

add_filter( 'template_redirect', 'business_club_custom_content_width' );
