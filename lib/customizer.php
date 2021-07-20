<?php
/* Adds Customizer options for Move-in Ready
 */

class MOVE_IN_READY_Customizer extends EQUITY_Customizer_Base {

	/**
	 * Register theme specific customization options
	 */
	public function register( $wp_customize ) {

		$this->colors( $wp_customize );
		
	}
	
	//* Colors
	private function colors( $wp_customize ) {
		$wp_customize->add_section(
			'colors',
			array(
				'title'    => __( 'Custom Colors', 'must-see'),
				'priority' => 200,
			)
		);

		//* Setting key and default value array
		$settings = array(
			'primary_color'       => '',
			'primary_color_hover' => '',
			'primary_color_light' => '',
		);

		foreach ( $settings as $setting => $default ) {

			$wp_customize->add_setting(
				$setting,
				array(
					'default' => $default,
					'type'    => 'theme_mod'
				)
			);

		}

		//* Primary Color
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'primary_color',
				array(
					'label'       => __( 'Primary Color', 'must-see' ),
					'description' => 'Used for links, buttons, top bar and footer background.',
					'section'     => 'colors',
					'settings'    => 'primary_color',
					'priority'    => 100
				)
			)
		);

		//* Primary Hover Color
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'primary_color_hover',
				array(
					'label'       => __( 'Primary Hover Color', 'must-see' ),
					'description' => 'Used for hover states and borders - should be slightly darker (or lighter) than the primary color.',
					'section'     => 'colors',
					'settings'    => 'primary_color_hover',
					'priority'    => 100
				)
			)
		);

		//* Primary Color light
		$wp_customize->add_control(
			new WP_Customize_Color_Control(
				$wp_customize,
				'primary_color_light',
				array(
					'label'       => __( 'Primary Color Light', 'must-see' ),
					'description' => 'Used primarily for header menu borders and labels - should be lighter than the primary but in the same hue.',
					'section'     => 'colors',
					'settings'    => 'primary_color_light',
					'priority'    => 100
				)
			)
		);
	}

	//* Render CSS
	public function render() {
		?>
		<!-- begin Child Customizer CSS -->
		<style type="text/css">
			<?php

			//* Primary color - link color
			self::generate_css( '
				a,
				.idx-content .IDX-wrapper-standard a,
				.ae-iconbox i[class*="fa-"],
				.ae-iconbox a i[class*="fa-"],
				.ae-iconbox.type-2:hover i[class*="fa-"],
				.ae-iconbox.type-2:hover a i[class*="fa-"],
				.ae-iconbox.type-3:hover i[class*="fa-"],
				.ae-iconbox.type-3:hover a i[class*="fa-"]
				', 'color', 'primary_color' );

			//* Primary color - backgrounds
			self::generate_css('
				.button:not(.secondary),
				button:not(.secondary),
				input[type="button"],
				input[type="submit"],
				.IDX-wrapper-standard .IDX-btn,
				.IDX-wrapper-standard .IDX-btn-primary,
				.IDX-wrapper-standard .IDX-btn-default,
				.IDX-wrapper-standard #IDX-newSearch,
				.IDX-wrapper-standard #IDX-saveProperty,
				.IDX-wrapper-standard .IDX-removeProperty,
				.IDX-wrapper-standard #IDX-saveSearch,
				.IDX-wrapper-standard #IDX-modifySearch,
				.IDX-wrapper-standard #IDX-submitBtn,
				.IDX-wrapper-standard #IDX-resetBtn,
				.IDX-wrapper-standard #IDX-refineSearchFormToggle,
				.IDX-wrapper-standard #IDX-formReset,
				.IDX-wrapper-standard #IDX-formReset-bottom,
				.IDX-wrapper-standard .IDX-panel-primary>.IDX-panel-heading,
				.IDX-wrapper-standard .IDX-navbar-default,
				.IDX-wrapper-standard .IDX-navigation,
				.IDX-wrapper-standard #IDX-mapHeader-Search,
				.IDX-wrapper-standard .IDX-nav-pills>li.IDX-active>a,
				.IDX-wrapper-standard .IDX-nav-pills>li.IDX-active>a:focus,
				.IDX-wrapper-standard .IDX-nav-pills>li.IDX-active>a:hover,
				.featured-page .more-link,
				.featured-post .more-link,
				.contain-to-grid,
				.top-header,
				.nav-main .top-header,
				.top-bar,
				.nav-main.top-bar,
				.top-bar.expanded .title-area,
				.nav-main .top-bar.expanded .title-area,
				.top-bar-section ul li,
				.nav-main .top-bar-section ul li,
				.top-bar-section ul li.active > a,
				.nav-main .top-bar-section ul li.active > a,
				.top-bar-section .dropdown li label,
				.top-bar-section .dropdown li a,
				.top-bar-section .dropdown li:not(.has-form) a:not(.button),
				.nav-main .top-bar-section .dropdown li:not(.has-form) a:not(.button),
				.top-bar-section li:not(.has-form) a:not(.button),
				.nav-main .top-bar-section li:not(.has-form) a:not(.button),
				.nav-main .top-bar-section li.active:not(.has-form) a:not(.button),
				.nav-main .top-bar-section ul li:hover:not(.has-form) > a,
				footer.site-footer,
				.ae-iconbox.type-2 i,
				.ae-iconbox.type-3 i,
				ul.pagination li.current a,
				ul.pagination li.current button
				',
				'background-color', 'primary_color'
			);

			//* Primary color hover - hover color
			self::generate_css('
				a:hover,
				a:focus
				',
				'color', 'primary_color_hover'
			);

			//* Primary color hover - background color
			self::generate_css('
				.button:not(.secondary):hover,
				button:not(.secondary):hover,
				input[type="button"]:hover,
				input[type="submit"]:hover,
				.button:not(.secondary):focus,
				button:not(.secondary):focus,
				input[type="button"]:focus,
				input[type="submit"]:focus,
				.featured-page .more-link:hover,
				.featured-post .more-link:hover,
				ul.pagination li.current a:hover,
				ul.pagination li.current a:focus,
				ul.pagination li.current button:hover,
				ul.pagination li.current button:focus,
				.nav-main .top-bar-section li:not(.has-form) a:not(.button):hover,
				.IDX-wrapper-standard .IDX-btn:hover,
				.IDX-wrapper-standard .IDX-btn-default:hover,
				.IDX-wrapper-standard .IDX-btn-primary:hover,
				.IDX-wrapper-standard #IDX-newSearch:hover,
				.IDX-wrapper-standard #IDX-saveProperty:hover,
				.IDX-wrapper-standard .IDX-removeProperty:hover,
				.IDX-wrapper-standard #IDX-saveSearch:hover,
				.IDX-wrapper-standard #IDX-modifySearch:hover,
				.IDX-wrapper-standard #IDX-submitBtn:hover,
				.IDX-wrapper-standard #IDX-resetBtn:hover,
				.IDX-wrapper-standard #IDX-refineSearchFormToggle:hover,
				.IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-nav>.IDX-active>a,
				.IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-nav>.IDX-active>a:focus,
				.IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-nav>.IDX-active>a:hover,
				.IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-nav>li>a:focus,
				.IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-nav>li>a:hover,
				.IDX-wrapper-standard .IDX-searchNavItem a:hover
				',
				'background-color', 'primary_color_hover', '', ' !important'
			);

			//* Primary color hover - border color
			self::generate_css('
				.button,
				button,
				input[type="button"],
				input[type="submit"],
				.idx-content .IDX-wrapper-standard .IDX-panel-primary,
				.idx-content .IDX-wrapper-standard .IDX-panel-primary>.IDX-panel-heading,
				.idx-content .IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-collapse,
				.idx-content .IDX-wrapper-standard .IDX-navbar-default .IDX-navbar-form,
				.idx-content .IDX-wrapper-standard .IDX-navbar-default
				',
				'border-color', 'primary_color_hover'
			);

			//* Primary color light - color
			self::generate_css('
				.nav-main.top-bar.expanded .toggle-topbar a,
				.nav-main .top-bar-section .dropdown label,
				.top-bar.expanded .toggle-topbar a,
				.top-bar-section .dropdown label
				',
				'color', 'primary_color_light'
			);

			//* Primary color light - border color
			self::generate_css('
				.nav-main .top-bar-section .divider,
				.nav-main .top-bar-section [role="separator"],
				.nav-main .top-bar-section > ul > .divider,
				.nav-main .top-bar-section > ul > [role="separator"],
				.top-bar-section .divider,
				.top-bar-section [role="separator"],
				.top-bar-section > ul > .divider,
				.top-bar-section > ul > [role="separator"] 
				',
				'border-color', 'primary_color_light'
			);

			?>
		</style>
		<!-- end Child Customizer CSS -->
		<?php
	}
	
}

add_action( 'init', 'move_in_ready_customizer_init' );
/**
 * Instantiate EQUITY_Customizer
 * 
 * @since 1.0
 */
function move_in_ready_customizer_init() {
	new MOVE_IN_READY_Customizer;
}