<?php
/**
 * Functions hooked to custom hook.
 *
 * @package Business_Club
 */

if ( ! function_exists( 'business_club_skip_to_content' ) ) :

	/**
	 * Add skip to content.
	 *
	 * @since 1.0.0
	 */
	function business_club_skip_to_content() {
		?><a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'business-club' ); ?></a><?php
	}
endif;

add_action( 'business_club_action_before', 'business_club_skip_to_content', 15 );

if ( ! function_exists( 'business_club_site_branding' ) ) :

	/**
	 * Site branding.
	 *
	 * @since 1.0.0
	 */
	function business_club_site_branding() {
		?>
		<div class="site-branding">

			<?php business_club_the_custom_logo(); ?>

			<?php $show_title = business_club_get_option( 'show_title' ); ?>
			<?php $show_tagline = business_club_get_option( 'show_tagline' ); ?>

			<?php if ( true === $show_title || true === $show_tagline ) : ?>
				<div id="site-identity">
					<?php if ( true === $show_title ) : ?>
						<?php if ( is_front_page() && is_home() ) : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
						<?php else : ?>
							<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ( true === $show_tagline ) : ?>
						<p class="site-description"><?php bloginfo( 'description' ); ?></p>
					<?php endif; ?>
				</div><!-- #site-identity -->
			<?php endif; ?>

		</div><!-- .site-branding -->
		<div class="right-head">
			<?php
			$show_search_in_header = business_club_get_option( 'show_search_in_header' );
			if ( true === $show_search_in_header ) : ?>
				<div class="header-search-box">
					<a href="#" class="search-icon"><i class="fa fa-search"></i></a>
					<div class="search-box-wrap">
						<?php get_search_form(); ?>
					</div>
				</div><!-- .header-search-box -->
			<?php endif; ?>
			<?php if ( business_club_woocommerce_status() ) : ?>
				<div class="cart-section">
					<div class="shopping-cart-views">
						<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents">
							<i class="fa fa-shopping-cart"></i>
							<span class="cart-value"><?php echo wp_kses_data( WC()->cart->get_cart_contents_count() );?></span>
						</a>
					</div><!-- .shopping-cart-views -->
				 </div><!-- .cart-section -->
			<?php endif; ?>
		</div><!-- .right-head -->
		<div id="main-nav" class="clear-fix">
			<nav id="site-navigation" class="main-navigation" role="navigation">
				<div class="wrap-menu-content">
					<?php
					wp_nav_menu(
						array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'fallback_cb'    => 'business_club_primary_navigation_fallback',
						)
					);
					?>
				</div><!-- .wrap-menu-content -->
			</nav><!-- #site-navigation -->
		</div><!-- #main-nav -->
	<?php
	}

endif;

add_action( 'business_club_action_header', 'business_club_site_branding' );

if ( ! function_exists( 'business_club_mobile_navigation' ) ) :

	/**
	 * Mobile navigation.
	 *
	 * @since 1.0.0
	 */
	function business_club_mobile_navigation() {
		?>
		<a id="mobile-trigger" href="#mob-menu"><i class="fa fa-bars"></i></a>
		<div id="mob-menu">
			<?php
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => '',
				'fallback_cb'    => 'business_club_primary_navigation_fallback',
			) );
			?>
		</div>
		<?php
	}

endif;

add_action( 'business_club_action_before', 'business_club_mobile_navigation', 20 );

if ( ! function_exists( 'business_club_footer_copyright' ) ) :

	/**
	 * Footer copyright.
	 *
	 * @since 1.0.0
	 */
	function business_club_footer_copyright() {

		// Check if footer is disabled.
		$footer_status = apply_filters( 'business_club_filter_footer_status', true );

		if ( true !== $footer_status ) {
			return;
		}

		// Copyright text.
		$copyright_text = business_club_get_option( 'copyright_text' );
		$copyright_text = apply_filters( 'business_club_filter_copyright_text', $copyright_text );
		?>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<?php
			$footer_menu_content = wp_nav_menu( array(
				'theme_location' => 'footer',
				'container'      => 'div',
				'container_id'   => 'footer-navigation',
				'depth'          => 1,
				'fallback_cb'    => false,
			) );
			?>
		<?php endif; ?>
		<?php if ( ! empty( $copyright_text ) ) : ?>
			<div class="copyright">
				<?php echo wp_kses_post( $copyright_text ); ?>
			</div>
		<?php endif; ?>
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'https://wordpress.org/', 'business-club' ) ); ?>"><?php printf( esc_html__( 'Powered by %s', 'business-club' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( esc_html__( '%1$s by %2$s', 'business-club' ), 'Business Club', '<a href="http://axlethemes.com">Axle Themes</a>' ); ?>
		</div>
		<?php
	}

endif;

add_action( 'business_club_action_footer', 'business_club_footer_copyright', 10 );

if ( ! function_exists( 'business_club_add_sidebar' ) ) :

	/**
	 * Add sidebar.
	 *
	 * @since 1.0.0
	 */
	function business_club_add_sidebar() {

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

		// Include primary sidebar.
		if ( 'no-sidebar' !== $global_layout ) {
			get_sidebar();
		}

		// Include secondary sidebar.
		switch ( $global_layout ) {
			case 'three-columns':
				get_sidebar( 'secondary' );
				break;

			default:
				break;
		}

	}

endif;

add_action( 'business_club_action_sidebar', 'business_club_add_sidebar' );

if ( ! function_exists( 'business_club_custom_posts_navigation' ) ) :

	/**
	 * Posts navigation.
	 *
	 * @since 1.0.0
	 */
	function business_club_custom_posts_navigation() {

		the_posts_pagination();

	}

endif;

add_action( 'business_club_action_posts_navigation', 'business_club_custom_posts_navigation' );

if ( ! function_exists( 'business_club_add_image_in_single_display' ) ) :

	/**
	 * Add image in single template.
	 *
	 * @since 1.0.0
	 */
	function business_club_add_image_in_single_display() {

		if ( has_post_thumbnail() ) {

			$args = array(
				'class' => 'business-club-post-thumb aligncenter',
			);

			the_post_thumbnail( 'large', $args );
		}

	}

endif;

add_action( 'business_club_single_image', 'business_club_add_image_in_single_display' );

if ( ! function_exists( 'business_club_add_breadcrumb' ) ) :

	/**
	 * Add breadcrumb.
	 *
	 * @since 1.0.0
	 */
	function business_club_add_breadcrumb() {

		// Bail if home page.
		if ( is_front_page() || is_home() ) {
			return;
		}

		echo '<div id="breadcrumb"><div class="container">';
		business_club_breadcrumb();
		echo '</div><!-- .container --></div><!-- #breadcrumb -->';

	}

endif;

add_action( 'business_club_action_before_content', 'business_club_add_breadcrumb', 7 );

if ( ! function_exists( 'business_club_footer_goto_top' ) ) :

	/**
	 * Go to top.
	 *
	 * @since 1.0.0
	 */
	function business_club_footer_goto_top() {
		echo '<a href="#page" class="scrollup" id="btn-scrollup"><i class="fa fa-angle-up"></i></a>';
	}

endif;

add_action( 'business_club_action_after', 'business_club_footer_goto_top', 20 );

if ( ! function_exists( 'business_club_add_front_page_widget_area' ) ) :

	/**
	 * Add front page widget area.
	 *
	 * @since 1.0.0
	 */
	function business_club_add_front_page_widget_area() {

		if ( is_front_page() && ! is_home() && is_active_sidebar( 'sidebar-front-page-widget-area' ) ) {
			echo '<div id="sidebar-front-page-widget-area" class="widget-area">';
			dynamic_sidebar( 'sidebar-front-page-widget-area' );
			echo '</div><!-- #sidebar-front-page-widget-area -->';
		}

	}

endif;

add_action( 'business_club_action_before_content', 'business_club_add_front_page_widget_area', 7 );

if ( ! function_exists( 'business_club_add_footer_widgets' ) ) :

	/**
	 * Add footer widgets.
	 *
	 * @since 1.0.0
	 */
	function business_club_add_footer_widgets() {

		get_template_part( 'template-parts/footer-widgets' );

	}

endif;

add_action( 'business_club_action_before_footer', 'business_club_add_footer_widgets', 5 );

if ( ! function_exists( 'business_club_add_top_head_content' ) ) :

	/**
	 * Add top head section.
	 *
	 * @since 1.0.0
	 */
	function business_club_add_top_head_content() {
		$contact_number        = business_club_get_option( 'contact_number' );
		$contact_email         = business_club_get_option( 'contact_email' );
		$show_social_in_header = business_club_get_option( 'show_social_in_header' );

		if ( empty( $contact_number ) && empty( $contact_email ) ) {
			$contact_status = false;
		} else {
			$contact_status = true;
		}

		if ( false === $contact_status && ( false === business_club_get_option( 'show_social_in_header' ) || false === has_nav_menu( 'social' ) ) ) {
			return;
		}
		?>
		<div id="tophead">
			<div class="container">
				<div id="quick-contact">
					<ul>
						<?php if ( ! empty( $contact_number ) ) : ?>
							<li class="quick-call">
								<a href="<?php echo esc_url( 'tel:' . preg_replace( '/\D+/', '', $contact_number ) ); ?>"><?php echo esc_html( $contact_number ); ?></a>
							</li>
						<?php endif; ?>
						<?php if ( ! empty( $contact_email ) ) : ?>
							<li class="quick-email">
								<a href="<?php echo esc_url( 'mailto:' . $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a>
							</li>
						<?php endif; ?>
					</ul>
				</div><!-- #quick-contact -->

				<?php if ( true === $show_social_in_header && has_nav_menu( 'social' ) ) : ?>
					<div id="header-social">
						<?php the_widget( 'Business_Club_Social_Widget' ); ?>
					</div><!-- .header-social -->
				<?php endif; ?>

			</div><!-- .container -->
		</div><!-- #tophead -->
		<?php
	}

endif;

add_action( 'business_club_action_before_header', 'business_club_add_top_head_content', 5 );
