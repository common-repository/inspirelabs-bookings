<?php
namespace WPDesk\Bookings\Admin\WC_Product;

use WPDesk\Bookings\Helpers\Common;

/**
 * WooCommerce Custom Product Type class helper
 */
class PriceListDataTab {

	/**
	 * WP Desk Plugin Info Object includes var such as plugin ver, name, id, dir, url, etc.
	 *
	 * @var object WPDesk_Plugin_Info.
	 */
	public $plugin_info;


	/**
	 * __construct
	 *
	 * @param object $plugin_info WPDesk_Plugin_Info.
	 */
	public function __construct( $plugin_info ) {
		$this->plugin_info = $plugin_info;
	}



	/**
	 * Run WordPress hooks
	 */
	public function hooks() {
		add_action( 'admin_enqueue_scripts', array( $this, 'screen_assets' ) );
		add_filter( 'product_type_selector', array( $this, 'wc_custom_product_type_selector' ) );
		add_action( 'woocommerce_product_data_panels', array( $this, 'product_data_tab_price_list' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'save_product_data_tab_price_list' ) );
		add_action( 'wp_ajax_get_pricelist', array( $this, 'get_pricelist' ) );
		add_action( 'woocommerce_new_product', array( $this, 'is_product_description_exist'), 50, 3 );
		add_action( 'woocommerce_update_product', array( $this, 'is_product_description_exist'), 50, 3 );
	}



	/**
	 * Current screen assets
	 */
	public function screen_assets() {
		$current_screen = get_current_screen();
		if ( is_a( $current_screen, 'WP_Screen' ) && 'product' === $current_screen->id ) {
			wp_enqueue_script('datepicker', $this->plugin_info->get_plugin_url() . '/assets/js/datepicker.min.js', array(), $this->plugin_info->get_version(), true);
			wp_enqueue_script('ProductSettingsIlabsHelper', $this->plugin_info->get_plugin_url() . '/assets/js/HelperSettingsIlabsBooking.js', array('datepicker'), $this->plugin_info->get_version() . rand( 1, 9999 ), true);
			wp_enqueue_script('ProductSettingsIlabsBooking', $this->plugin_info->get_plugin_url() . '/assets/js/ProductSettingsIlabsBooking.js', array('datepicker'), $this->plugin_info->get_version(), true);
			
			$translation_array = array(
                'accept_button' => __('Accept', 'inspirelabs-bookings'),
                'edit_button'   => __('Edit', 'inspirelabs-bookings'),
                'delete_button' => __('Delete', 'inspirelabs-bookings'),
                'Monday'        => __('Monday', 'inspirelabs-bookings'),
                'Tuesday'       => __('Tuesday', 'inspirelabs-bookings'),
                'Wednesday'     => __('Wednesday', 'inspirelabs-bookings'),
                'Thursday'      => __('Thursday', 'inspirelabs-bookings'),
                'Friday'        => __('Friday', 'inspirelabs-bookings'),
                'Saturday'      => __('Saturday', 'inspirelabs-bookings'),
                'Sunday'        => __('Sunday', 'inspirelabs-bookings')
            );

			wp_localize_script(
				'ProductSettingsIlabsBooking',
				'price_list',
				array(
					'ajax_url' => admin_url('admin-ajax.php'),
					//'locale' => 'pl-PL', // For date formatting.
					'locale' => 'en-US', // For date formatting.
					'translation' => array(
						'days' => array_values(Common::short_days_translation()),
						'months' => array_values(Common::month_names_translation()),
					),
					'currency' => get_woocommerce_currency_symbol(),
					'duration' => Common::duration_options(),
					'security' => wp_create_nonce('get_pricelist'),
					'price_placeholder' => __('Price List', 'inspirelabs-bookings'),
					'post_id' => get_the_ID(),
					'transl' => $translation_array,
				)
			);
		}
	}



	/**
	 * Adds Custom Product Type to Type Selector
	 *
	 * @param  array $types - product types.
	 *
	 * @return array $types
	 */
	public function wc_custom_product_type_selector( $types ) {
		$types['booking'] = __( 'Booking', 'inspirelabs-bookings' );
		return $types;
	}



	/**
	 * Create options for select - years
	 *
	 * @param  int $product_id of current product.
	 *
	 * @return array $price lists existing and new pricelist
	 */
	public function select_year_options( int $product_id ) : array {

		// Create new price lists.
		$new_price_lists = range( gmdate( 'Y' ), gmdate( 'Y', strtotime( '+2 years' ) ), 1 );
		$new_price_lists = array_values( $new_price_lists );
		$price_lists     = $new_price_lists;

		// Get post meta.
		$post_meta          = get_post_meta( $product_id );
		$filtered_post_meta = preg_grep( '/booking_price_list_/', array_keys( $post_meta ) );

		// Check if there exists any pricelist and gets theirs years.
		if ( $filtered_post_meta ) {
			// Get existing price lists.
			$existing_price_lists = array();
			array_walk(
				$filtered_post_meta,
				function ( &$value ) use ( &$existing_price_lists ) {
					$year = (int) substr( $value, -4 );
					$existing_price_lists[] = $year;
					return $existing_price_lists;
				}
			);

			$price_lists = array_merge( $existing_price_lists, $new_price_lists );
			$price_lists = array_unique( $price_lists );
		}

		// Sort before return.
		sort( $price_lists );

		return $price_lists;
	}


	/**
	 * Product data tab content: price list
	 */
	public function product_data_tab_price_list() {

		global $post;
		$price_list = get_post_meta( $post->ID, 'booking_price_list_' . date( 'Y' ), true );

		?>
		<div id="booking_price_list" class="panel woocommerce_options_panel hidden">
			<section class="options_group">

				<!-- Header -->
				<header class="row--header">

					<div class="switchers">

						<!-- Years pricelist select -->
						<div class="select--year__wrapper">
							<select class="select--year" name="cz-year" autocomplete="off">
								<?php foreach ( $this->select_year_options( (int) $post->ID ) as $year ) : ?>
									<option value="<?php echo esc_attr( $year ); ?>" <?php selected( $year, gmdate( 'Y' ) ); ?>><?php echo esc_html( $year ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>

					</div>

					<!-- Table header -->
					<div class="row">
						<div class="row__start">
							<?php esc_html_e( 'Start date', 'inspirelabs-bookings' ); ?>
						</div>
						<div class="row__end">
							<?php esc_html_e( 'End date', 'inspirelabs-bookings' ); ?>
						</div>
						<div class="row__price">
							<?php esc_html_e( 'Price per day (night)', 'inspirelabs-bookings' ); ?>
						</div>
						<div class="row__duration">
							<?php esc_html_e( 'Duration', 'inspirelabs-bookings' ); ?>
						</div>
						<div class="row__actions">
							<?php esc_html_e( 'Options', 'inspirelabs-bookings' ); ?>
						</div>
					</div>
				</header>

				<!-- Single price list row -->
				<div class="rows rows--price_list">
					<?php $this->populate_price_list_rows( $price_list ); ?>
				</div>

				<!-- Row template -->
				<footer class="toolbar">
					<button class="row-add button button-primary" type="button">
						<?php esc_html_e( 'Add row', 'inspirelabs-bookings' ); ?>
					</button>
				</footer>

			</section>
		</div>
		<?php
	}



	/**
	 * Populate price list with rows
	 *
	 * @param  array $price_list get post meta value.
	 */
	public function populate_price_list_rows( $price_list ) {

		if ( ! empty( $price_list ) ) :
			foreach ( $price_list as $row_id => $row_value ) :	?>

				<div class="row row--single" data-row="<?php echo esc_attr( $row_id ); ?>">

					<!-- Start date -->
					<div class="row__start">
						<span class="start-date-wrapper">
							<?php echo esc_html( gmdate( 'd.m.Y', strtotime( $row_value['start'] ) ) ); ?>
						</span>
						<input
							type="hidden"
							class="start-date"
							name="cz-pricerow[<?php echo esc_attr( $row_id ); ?>][start]"
							value="<?php echo esc_attr( $row_value['start'] ); ?>"
						>
					</div>

					<!-- End date -->
					<div class="row__end">
						<span class="end-date-wrapper">
							<?php echo esc_html( gmdate( 'd.m.Y', strtotime( $row_value['end'] ) ) ); ?>
						</span>
						<input
							type="hidden"
							class="end-date"
							name="cz-pricerow[<?php echo esc_attr( $row_id ); ?>][end]"
							value="<?php echo esc_attr( $row_value['end'] ); ?>"
						>
					</div>

					<!-- Price -->
					<div class="row__price">
						<span class="price-wrapper">
							<?php echo esc_html( get_woocommerce_currency_symbol() ); ?> <?php echo esc_html( $row_value['price'] ); ?>
						</span>

						<span>
							<input
								class="price"
								type="text"
								name="cz-pricerow[<?php echo esc_attr( $row_id ); ?>][price]"
								value="<?php echo esc_attr( $row_value['price'] ); ?>"
							>
						</span>

						<!-- Hidden select for week range -->
						<span class="startday-wrapper">
							<span class="startday-select-title">Day to start:</span>
							<select class="select--day-of-week" name="cz-pricerow[<?php echo esc_attr( $row_id ); ?>][weekbegin]" autocomplete="off">
								<?php foreach ( Common::daysofweek() as $index => $day ) : ?>
									<?php if($index == $row_value['weekbegin']) : ?>
										<option value="<?php echo esc_attr( $index ); ?>" selected="selected">
											<?php echo esc_html( $day ); ?>
										</option>
									<?php else : ?>
										<option value="<?php echo esc_attr( $index ); ?>">
											<?php echo esc_html( $day ); ?>
										</option>
									<?php endif; ?>
								<?php endforeach; ?>
							</select>
						</span>

					</div>

					<!-- Duration -->
					<div class="row__duration">
						<?php foreach ( Common::duration_options() as $index => $option ) : ?>
							<input
								type="radio"
								id="cz-pricerow-<?php echo esc_attr( $row_id ); ?>-<?php echo esc_attr( $index ); ?>"
								name="cz-pricerow[<?php echo esc_attr( $row_id ); ?>][duration]"
								value="<?php echo esc_attr( $index ); ?>"
								<?php checked( $row_value['duration'], $index ); ?>
							>
							<label class="option" for="cz-pricerow-<?php echo esc_attr( $row_id ); ?>-<?php echo esc_attr( $index ); ?>">
								<span class="option__name">
									<?php echo esc_html( $option['label'] ); ?>
								</span>
								<?php if ( isset( $option['description'] ) ) : ?>
									<small class="option__description">
										<?php echo esc_html( $option['description'] ); ?>
									</small>
								<?php endif; ?>
							</label>
						<?php endforeach; ?>
					</div>

					<!-- Actions -->
					<div class="row__actions">
						<button class="row-edit" type="button">
							<?php esc_html_e( 'Edit', 'inspirelabs-bookings' ); ?>
						</button>
						<button class="row-delete" type="button">
							<?php esc_html_e( 'Delete', 'inspirelabs-bookings' ); ?>
						</button>
					</div>
				</div>

			<?php
			endforeach;
		endif;
	}



	/**
	 * Submit price list values
	 *
	 * @param  int $post_id - current post id.
	 */
	public function save_product_data_tab_price_list( $post_id ) {
        if( isset( $_POST['cz-pricerow'] ) && isset( $_POST['cz-year'] ) ) {
		    $year = sanitize_text_field( wp_unslash( $_POST['cz-year'] ) );
            $price_row = wp_unslash( $_POST['cz-pricerow'] );
            update_post_meta($post_id, 'booking_price_list_' . $year, $price_row);
        }
	}


	/**
	 * We need Woocommerce tab description to show form with calendar before it
	 * so if user doesn't put any content - add dummy data
	 *
	 * @param  int $post_id - current post id.
	 */
	public function is_product_description_exist( $product_id ) {

		if ( 'product' === get_post($product_id)->post_type ) {
			if ('booking' === \WC_Product_Factory::get_product_type($product_id)) {

				$product = wc_get_product( $product_id );

				if (empty( $product->get_description() )) {
					$args = array();
					$args['ID'] = $product_id;
					$args['post_content'] = '.';
					wp_update_post($args);
				}
			}
		}
	}



	/**
	 * Get current price list for AJAX call
	 */
	public function get_pricelist() {
		check_ajax_referer( 'get_pricelist', 'security' );

		if ( isset( $_POST['year'] ) && isset( $_POST['post_id'] ) ) :

			$post_id   = sanitize_text_field( $_POST['post_id'] );
			$year      = sanitize_text_field( $_POST['year'] );
			$post_meta = get_post_meta($post_id, 'booking_price_list_' . $year, true);

			if ( $post_meta ) :
				wp_send_json_success( array_values( $post_meta ) );


			else :
				wp_send_json_error();
			endif;

		else :
			wp_send_json_error();

		endif;
	}

}
