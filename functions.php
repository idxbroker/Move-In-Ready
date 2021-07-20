<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Move-in Ready Child Theme', 'move-in-ready' ) );
define( 'CHILD_THEME_URL', 'http://www.agentevolution.com/shop/move-in-ready/' );
define( 'CHILD_THEME_VERSION', '1.0.12' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'move-in-ready', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'move-in-ready' ) );

//* Add Theme Support
add_theme_support( 'equity-top-header-bar' );
add_theme_support( 'equity-after-entry-widget-area' );

//* Add small 90x90 thumbnail image size
add_image_size( 'mini', '90', '90', true);
add_image_size( 'listings_large', '720', '410', true);

//* Create additional color style options
add_theme_support( 'equity-style-selector', array(
	'mir-green'  => __( 'Green', 'move-in-ready' ),
	'mir-red'    => __( 'Red', 'move-in-ready' ),
	'mir-purple' => __( 'Purple', 'move-in-ready' ),
	'mir-grey'   => __( 'Grey', 'move-in-ready' ),
	'mir-custom' => __( 'Use Customizer', 'move-in-ready' ),
) );

//* Load fonts 
add_filter( 'equity_google_fonts', 'move_in_ready_fonts' );
function move_in_ready_fonts( $equity_google_fonts ) {
	$equity_google_fonts = 'Montserrat:700|Roboto:400,300';
	return $equity_google_fonts;
}

// Add class to body for easy theme identification.
add_filter( 'body_class', 'add_theme_body_class' );
function add_theme_body_class( $classes ) {
	$classes[] = 'home-theme--move-in-ready';
	return $classes;
}

//* Register new IDX Carousel widget
add_action('widgets_init', 'move_in_ready_idx_carousel');
function move_in_ready_idx_carousel() {
	if ( class_exists( 'Equity_IDX_Carousel_Widget' ) ) {
		unregister_widget('Equity_IDX_Carousel_Widget');
		require_once get_stylesheet_directory() . '/lib/widgets/Equity_MIR_IDX_Carousel_Widget.php';
		register_widget('Equity_MIR_IDX_Carousel_Widget');
	}
}

//* Add top header nav
add_theme_support( 'equity-menus', array( 'main' => __( 'Main Menu', 'move-in-ready' ), 'top-header-right' => __( 'Top Header Right', 'move-in-ready' ) ) );

//* Redefine top header widget width
add_filter( 'top_header_left_widget_widths', 'mir_top_header_left_width');
function mir_top_header_left_width() {
	$top_header_left_widget_widths = 'small-12 medium-4 large-3';
	return $top_header_left_widget_widths;
}

//* Register widget areas
equity_register_widget_area(
	array(
		'id'          => 'home-top-left',
		'name'        => __( 'Home Top Left', 'move-in-ready' ),
		'description' => __( 'This is the Top Left section of the Home page, recommended to use a slider or IDX carousel widget.', 'move-in-ready' ),
	)
);
equity_register_widget_area(
	array(
		'id'          => 'home-top-right',
		'name'        => __( 'Home Top Right', 'move-in-ready' ),
		'description' => __( 'This is the Top Right section of the Home page, recommended to use an IDX search widget.', 'move-in-ready' ),
	)
);
equity_register_widget_area(
	array(
		'id'          => 'home-middle',
		'name'        => __( 'Home Middle', 'move-in-ready' ),
		'description' => __( 'This is the Middle section of the Home page, recommended for calls to action using theme shortcodes.', 'move-in-ready' ),
	)
);
equity_register_widget_area(
	array(
		'id'          => 'home-bottom-left',
		'name'        => __( 'Home Bottom Left', 'move-in-ready' ),
		'description' => __( 'This is the Bottom Left section of the Home page.', 'move-in-ready' ),
	)
);
equity_register_widget_area(
	array(
		'id'          => 'home-bottom-right',
		'name'        => __( 'Home Bottom Right', 'move-in-ready' ),
		'description' => __( 'This is the Bottom Right section of the Home page.', 'move-in-ready' ),
	)
);

//* Home page - define home page widget areas for welcome screen display check
add_filter('equity_theme_widget_areas', 'move_in_ready_home_widget_areas');
function move_in_ready_home_widget_areas($active_widget_areas) {
	$active_widget_areas = array( 'home-top-left', 'home-top-right', 'home-middle', 'home-bottom-left', 'home-bottom-right' );
	return $active_widget_areas;
}

//* Default widget content
if ( ! is_active_sidebar( 'top-header-left' ) ) {
	add_action('equity_top_header_left', 'top_header_left_default_widget');
}
function top_header_left_default_widget() {
	the_widget( 'WP_Widget_Text', array( 'text' => '[social_icons]') );
}

//* Home page - markup and default widgets
function equity_child_home() {
	?>
	
	<div class="home-top">
		<div class="row">
			<div class="columns small-12 large-8 home-top-left">
			<?php 
				if ( ! is_active_sidebar( 'home-top-left' ) ) {
					the_widget( 'WP_Widget_Text', array( 'text' => 'Add an IDX Carousel widget or Slider widget here.'), array( 'before_widget' => '<aside class="widget-area">', 'after_widget' => '</aside>' ) );
					
				} else {
					equity_widget_area( 'home-top-left' );
				}
			?>
			</div><!-- end .columns .small-12 .large-8 .home-top-left -->
			<div class="columns small-12 large-4 home-top-right">
			<?php 
				if ( ! is_active_sidebar( 'home-top-right' ) ) {
					the_widget( 'WP_Widget_Text', array( 'title' => 'Property Search', 'text' => 'Add an IDX Search widget or WP Listings Search widget here.'), array( 'before_widget' => '<aside class="widget-area">', 'after_widget' => '</aside>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>' ) );
					
				} else {
					equity_widget_area( 'home-top-right' );
				}
			?>
			</div><!-- end .columns .small-12 .large-4 .home-top-right -->
		</div><!-- .end .row -->
	</div><!-- end .home-top -->

	<div class="home-middle">
		<div class="row">
			<div class="columns small-12">
			<?php
				if ( ! is_active_sidebar( 'home-middle' ) ) {
					the_widget( 'WP_Widget_Text', array( 'text' => 'Add calls to action here using the theme\'s column and button shortcodes.'), array( 'before_widget' => '<aside class="widget-area">', 'after_widget' => '</aside>' ) );
				} else {
					equity_widget_area( 'home-middle' );
				}
			?>
			</div><!-- end .columns .small-12 -->
		</div><!-- end .row -->
	</div><!-- end .home-middle -->

	<div class="home-bottom">
		<div class="row">
			<div class="columns small-12 large-8 home-bottom-left">
				<?php
				if ( ! is_active_sidebar( 'home-bottom-left' ) ) {
					the_widget( 'WP_Widget_Text', array( 'title' => 'Our Communities', 'text' => 'Add an IDX City links widget or any other available widget(s) here.'), array( 'before_widget' => '<aside class="widget-area">', 'after_widget' => '</aside>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>' ) );
					
				} else {
					equity_widget_area( 'home-bottom-left' );
				} ?>
			</div><!-- .columns .small-12 .large-8 .home-bottom-left -->
			<div class="columns small-12 large-4 home-bottom-right">
				<?php
				if ( ! is_active_sidebar( 'home-bottom-right' ) ) {
					the_widget( 'WP_Widget_Text', array( 'title' => 'Featured Blog Post', 'text' => 'Add a Featured Post widget or any other available widget(s) here.'), array( 'before_widget' => '<aside class="widget-area">', 'after_widget' => '</aside>', 'before_title' => '<h4 class="widget-title">', 'after_title' => '</h4>' ) );
					
				} else {
					equity_widget_area( 'home-bottom-right' );
				} ?>
			</div><!-- .columns .small-12 .large-4 .home-bottom-right -->
		</div><!-- end .row -->
	</div><!-- end .home-bottom -->

<?php
}

//* Includes

# Theme Customizatons
require_once get_stylesheet_directory() . '/lib/customizer.php';