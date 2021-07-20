<?php
/**
 * Move-in Ready custom IDX Carousel widget
 *
 * @package Move-in Ready\IDX
 * @author  Agent Evolution
 * @license GPL-2.0+
 * @link    
 * 
 * Creates a widget that outputs a carousel of IDX properties
 *
 * @subpackage Widgets
 * @see Equity_Idx_Api
 */
class Equity_MIR_IDX_Carousel_Widget extends WP_Widget {

	public $_idx;

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {

		$this->_idx = new Equity_Idx_Api;

		parent::__construct(
	 		'equity_carousel', // Base ID
			'Equity - IDX Property Carousel', // Name
			array(
				'description' => __( 'Displays a carousel of properties', 'equity' ),
				'classname'   => 'equity-idx-carousel-widget'
			)
		);
	}

	/**
	 * Returns the markup for the listings
	 *
	 * @uses Equity_IDX_Carousel_Widget::calc_percent()
	 * @param array $instance Previously saved values from database.
	 * @return string $output html markup for front end display
	 */
	public function body($instance) {

		wp_enqueue_style( 'owl-css' );
		wp_enqueue_script( 'owl' );

		$prev_link = apply_filters( 'idx_listing_carousel_prev_link', $idx_listing_carousel_prev_link_text = __( '<i class=\"fas fa-chevron-circle-left\"></i><span>Prev</span>', 'equity' ) );
		$next_link = apply_filters( 'idx_listing_carousel_next_link', $idx_listing_carousel_next_link_text = __( '<i class=\"fas fa-chevron-circle-right\"></i><span>Next</span>', 'equity' ) );

		if ( ($instance['properties']) == 'savedlinks') {
			$properties = $this->_idx->saved_link_properties($instance['saved_link_id']);
		} else {
			$properties = $this->_idx->client_properties($instance['properties']);
		}

		if ( empty($properties) ) {
			return 'No properties found';
		}	

		if( $instance['autoplay'] ) {
			$autoplay = 'autoPlay: true,';
		} else {
			$autoplay = '';
		}

		$display = $instance['display'];

		if($display === 1) {
			echo '
			<script>
			jQuery(function( $ ){
				$(".equity-listing-carousel-' . $display . '").owlCarousel({
					singleItem: true,
					' . $autoplay . '
					navigation: true,
					navigationText: ["' . $prev_link . '", "' . $next_link . '"],
					pagination: false,
					lazyLoad: true,
					addClassActive: true,
					itemsScaleUp: true
				});
			});
			</script>
			';
		} else {
			echo '
			<script>
			jQuery(function( $ ){
				$(".equity-listing-carousel-' . $display . '").owlCarousel({
					items: ' . $display . ',
					' . $autoplay . '
					navigation: true,
					navigationText: ["' . $prev_link . '", "' . $next_link . '"],
					pagination: false,
					lazyLoad: true,
					addClassActive: true,
					itemsScaleUp: true
				});
			});
			</script>
			';
		}

		// sort low to high
		usort($properties, array($this, 'price_cmp') );

		if ( 'high-low' == $instance['order'] ) {
			$properties = array_reverse($properties);
		}

		$max = $instance['max'];

		$total = count($properties);
		$count = 0;

		$output = '';

		$output .= sprintf('<div class="equity-idx-carousel equity-listing-carousel-%s">', $instance['display']);

		foreach ($properties as $prop) {

			if ( !empty($max) && $count == $max ) {
				return $output;
			}

			$prop_image_url = ( isset($prop['image']['0']['url']) ) ? $prop['image']['0']['url'] : '//mlsphotos.idxbroker.com/defaultNoPhoto/noPhotoFull.png';

			$count++;

			$output .= sprintf(
				'<div class="carousel-property">
					<a href="%2$s" class="carousel-photo">
						<img class="lazyOwl" data-src="%3$s" alt="%4$s" title="%4$s" />
						<div class="property-details">
							<span class="price">%1$s</span>
							<p class="address">
								<span class="street">%5$s %6$s %7$s %8$s</span>
								<span class="cityname">%9$s</span>,
								<span class="state"> %10$s</span>
							</p>
							<p class="beds-baths-sqft">
								<span class="beds">%11$s Beds</span>
								<span class="baths">%12$s Baths</span>
								<span class="sqft">%13$s Sq Ft</span>
							</p>
						</div>
					</a>
				</div>',
				$prop['listingPrice'],
				$this->_idx->details_url() . '/' . $prop['detailsURL'],
				$prop_image_url,
				$prop['remarksConcat'],
				$prop['streetNumber'],
				$prop['streetName'],				
				$prop['streetDirection'],
				$prop['unitNumber'],
				$prop['cityName'],
				$prop['state'],
				$prop['bedrooms'],
				$prop['totalBaths'],
				$prop['sqFt']
			);

		}

		$output .= '';

		return $output;
	}

	/**
	 * Compares the price fields of two arrays
	 *
	 * @param array $a
	 * @param array $b
	 * @return int
	 */
	public function price_cmp($a, $b) {

		$a = $this->clean_price($a['listingPrice']);
		$b = $this->clean_price($b['listingPrice']);

		if ( $a == $b ) {
			return 0;
		}

		return ( $a < $b ) ? -1 : 1;
	}

	/**
	 * Removes the "$" and "," from the price field
	 *
	 * @param string $price
	 * @return mixed $price the cleaned price
	 */
	public function clean_price($price) {

		$patterns = array(
			'/\$/',
			'/,/'
		);

		$price = preg_replace($patterns, '', $price);

		return $price;
	}

	/**
	 * Echos saved link names wrapped in option tags
	 *
	 * This is just a helper to keep the html clean
	 *
	 * @param var $instance
	 */
	public function saved_link_options($instance) {

		$saved_links = $this->_idx->saved_links();

		if ( !is_array($saved_links) ) {
			return;
		}

		foreach($saved_links as $saved_link) {

			// display the link name if no link title has been assigned
			$link_text = empty( $saved_link->linkTitle ) ? $saved_link->linkName : $saved_link->linkTitle;

			echo '<option ', selected($instance['saved_link_id'], $saved_link->id, 0), ' value="', $saved_link->id, '">', $link_text, '</option>';

		}
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 * @param array $args Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		extract( $args );
		$title = $instance['title'];

		echo $before_widget;

		if ($instance['geoip'] && function_exists( 'turnkey_dashboard_setup' )) {
			$geoip_before = '[geoip-content ' . $instance['geoip'] .'="' . $instance['geoip-location'] . '"]';
			$geoip_after  = '[/geoip-content]';
			echo do_shortcode( $geoip_before . $before_title . $title . $after_title . $this->body($instance) . $geoip_after );
		} else {
			if ( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
			}

			echo $this->body($instance);
		}

		echo $after_widget;
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title']            = strip_tags( $new_instance['title'] );
		$instance['properties']       = strip_tags( $new_instance['properties'] );
		$instance['saved_link_id']    = (int) ( $new_instance['saved_link_id'] );
		$instance['display']          = (int) ( $new_instance['display'] );
		$instance['max']              = (int) ( $new_instance['max'] );
		$instance['order']            = strip_tags( $new_instance['order'] );
		$instance['autoplay']         = $new_instance['autoplay'];
		$instance['geoip']            = strip_tags( $new_instance['geoip'] );
		$instance['geoip-location']   = strip_tags( $new_instance['geoip-location'] );

		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		$_idx = $this->_idx;

		$defaults = array(
			'title'            => __( 'Properties', 'equity' ),
			'properties'       => 'featured',
			'saved_link_id'    => '',
			'display'          => 3,
			'max'              => 15,
			'order'            => 'high-low',
			'autoplay'         => 1,
			'geoip'            => '',
			'geoip-location'   => ''
		);

		$instance = wp_parse_args( (array) $instance, $defaults );

		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php esc_attr_e( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'properties' ); ?>"><?php _e( 'Properties to Display:', 'equity' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'properties' ); ?>" name="<?php echo $this->get_field_name( 'properties' ) ?>">
				<option <?php selected($instance['properties'], 'featured'); ?> value="featured"><?php _e( 'Featured', 'equity' ); ?></option>
				<option <?php selected($instance['properties'], 'soldpending'); ?> value="soldpending"><?php _e( 'Sold/Pending', 'equity' ); ?></option>
				<option <?php selected($instance['properties'], 'supplemental'); ?> value="supplemental"><?php _e( 'Supplemental', 'equity' ); ?></option>
				<option <?php selected($instance['properties'], 'historical'); ?> value="historical"><?php _e( 'Historical', 'equity' ); ?></option>
				<option <?php selected($instance['properties'], 'savedlinks'); ?> value="savedlinks"><?php _e( 'Use Saved Link', 'equity' ); ?></option>				
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'saved_link_id' ); ?>">Choose a saved link (if selected above):</label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'saved_link_id' ); ?>" name="<?php echo $this->get_field_name( 'saved_link_id' ) ?>">
				<?php $this->saved_link_options($instance); ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'display' ); ?>"><?php _e( 'Listings to show without scrolling:', 'equity' ); ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'display' ); ?>" name="<?php echo $this->get_field_name( 'display' ) ?>" value="<?php esc_attr_e( $instance['display'] ); ?>" size="3">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'max' ); ?>"><?php _e( 'Max number of listings to show:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'max' ); ?>" name="<?php echo $this->get_field_name( 'max' ); ?>" type="text" value="<?php esc_attr_e( $instance['max'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'order' ); ?>"><?php _e( 'Sort order:', 'equity' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'order' ); ?>" name="<?php echo $this->get_field_name( 'order' ) ?>">
				<option <?php selected($instance['order'], 'high-low'); ?> value="high-low"><?php _e( 'Highest to Lowest Price', 'equity' ); ?></option>
				<option <?php selected($instance['order'], 'low-high'); ?> value="low-high"><?php _e( 'Lowest to Highest Price', 'equity' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'autoplay' ); ?>"><?php _e( 'Autoplay?', 'equity' ); ?></label>
			<input type="checkbox" id="<?php echo $this->get_field_id( 'autoplay' ); ?>" name="<?php echo $this->get_field_name( 'autoplay' ) ?>" value="1" <?php checked( $instance['autoplay'], true ); ?>>
		</p>

		<?php if (function_exists( 'turnkey_dashboard_setup' ) ) { ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'geoip' ); ?>"><?php _e( 'Only show content for (optional):', 'equity' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'geoip' ); ?>" name="<?php echo $this->get_field_name( 'geoip' ) ?>">
				<option <?php selected($instance['geoip'], ''); ?> value=""><?php _e( 'All', 'equity' ); ?></option>
				<option <?php selected($instance['geoip'], 'region'); ?> value="region"><?php _e( 'State', 'equity' ); ?></option>
				<option <?php selected($instance['geoip'], 'city'); ?> value="city"><?php _e( 'City', 'equity' ); ?></option>
				<option <?php selected($instance['geoip'], 'postalcode'); ?> value="postalcode"><?php _e( 'Postal Code', 'equity' ); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'geoip-location' ); ?>"><?php _e( 'Enter location to show for: <br /><em> Values can be comma separated.<br />For State, use 2 letter abbreviation.</em>' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'geoip-location' ); ?>" name="<?php echo $this->get_field_name( 'geoip-location' ); ?>" type="text" value="<?php esc_attr_e( $instance['geoip-location'] ); ?>" />
		</p>

		<?php }
	}
}