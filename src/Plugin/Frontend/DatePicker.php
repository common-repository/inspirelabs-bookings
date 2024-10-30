<?php

namespace WPDesk\Bookings\Frontend;

use WPDesk\Bookings\Helpers\PriceList;
use WPDesk\Bookings\Helpers\Common;

/**
 * Booking Product Type Datepicker
 * Includes all methods for the datepicker rendering and ajax month switching.
 *
 * @package WPDesk\Bookings
 */
class DatePicker {

	/**
	 * Load date picker on front
	 *
	 * @param  false|string $date        date('Y-m-d').
	 * @param  int          $iterations - number of months to show, where 1 is only current.
	 */
	public function date_picker( $date = false, $iterations = 3 ) {

		global $product;
		if ( ! $date ) {
			$date = new \DateTime();
		}
		?>
		<div class="datepicker">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php
					$price_list = new PriceList( $product->get_id() );
					$price_list->set_limit_date( new \DateTime() );

					// Current month.
					$this->render_datepicker( $price_list->price_list( (int) $date->format( 'Y' ), (int) $date->format( 'n' ) ) );

					// Next month.
					for ( $n = 1; $n <= $iterations; $n++ ) {
						$date->modify( 'first day of +1 month' );
						$this->render_datepicker( $price_list->price_list( (int) $date->format( 'Y' ), (int) $date->format( 'n' ) ) );
					}
					?>
				</div>
			</div>
			<button type="button" class="datepicker-navigation navigation-prev">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26.08 45.23"><path d="M22.78 0l3.3 3.3L6.6 22.61l19.48 19.32-3.3 3.3L0 22.61 22.78 0"/></svg>
			</button>
			<button type="button" class="datepicker-navigation navigation-next">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26.08 45.23"><path d="M3.3 45.23L0 41.93l19.48-19.32L0 3.3 3.3 0l22.78 22.61L3.3 45.23"/></svg>
			</button>
		</div>
		<input type="hidden" name="booking-details">
		<?php
	}



	/**
	 * Datepicker rendering
	 *
	 * @param array $price_list Whole month divided into single days with price and booking details.
	 *
	 * @return void
	 */
	public function render_datepicker( array $price_list ) : void {
		?>
		<section class="month swiper-slide" id="<?php echo esc_attr( $price_list['Y'] . '-' . $price_list['n'] ); ?>">
			<header>
				<?php echo esc_html( $price_list['M'] ); ?>
				<?php echo esc_html( $price_list['Y'] ); ?>
			</header>
			<div class="days">
				<?php foreach ( $price_list['d'] as $id => $day ) : ?>

					<?php if ( $day ) : ?>
						<?php if ( array_key_exists( 'rules', $day ) ) : ?>

							<input
								type="checkbox"
								id="<?php echo esc_attr( $id . '-' . $price_list['n'] ); ?>"
								class="<?php echo esc_attr( $this->input_class( $day['rules'] ) ); ?>"
								data-charter="<?php echo esc_attr( $this->input_data_attr( $day['rules'] ) ); ?>"
								value="<?php echo esc_attr( gmdate( 'Y-m-d', mktime( 0, 0, 0, $price_list['n'], $day['day'], $price_list['Y'] ) ) ); ?>"
								autocomplete="off"
								<?php disabled( false === $day['available'] ); ?>
							>
						<?php else : ?>
							<input
								type="checkbox"
								disabled
							>
						<?php endif; ?>
						<label
							class="<?php echo esc_attr( implode( ' ', $day['class'] ) ); ?>"
							<?php echo ( array_key_exists( 'rules', $day ) ? 'for="' . esc_attr( $id . '-' . $price_list['n'] ) . '"' : '' ); ?>
							<?php echo ( $day['available'] && array_key_exists( 'rules', $day ) ? wp_kses_data( $this->hoover_tooltip( $day['rules'] ) ) : '' ); ?>
						>
							<div class="day__wrapper">
								<span class="day__no">
									<?php echo esc_html( $day['day'] ); ?>
								</span>
								<?php echo ( array_key_exists( 'rules', $day ) && $day['available'] ? wp_kses_post( $this->single_day_price( $day['rules'] ) ) : '' ); ?>
							</div>
						</label>
					<?php else : ?>
						<div class="day placeholder"></div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</section>
		<?php
	}



	/**
	 * Echoes rules as class name
	 *
	 * @param array $rules .
	 * @return string
	 */
	public function input_class( array $rules ) : string {
		$class = array();
		foreach ( $rules as $id => $rule ) :
			$class[] = $rule['duration'] . '-' . $id . ' ';
		endforeach;

		return implode( '', $class );
	}



	/**
	 * Create data attribute
	 *
	 * @param array $rules .
	 * @return false|string
	 */
	public function input_data_attr( array $rules ) : string {
		$result = array();
		foreach ( $rules as $id => $rule ) :
			array_push(
				$result,
				array(
					'pricelist' => (int) $id,
					'start'     => $rule['start'],
					'end'       => $rule['end'],
					'duration'  => $rule['duration'],
					'price'     => $rule['price'],
					'weekbegin' => $rule['weekbegin'],
				)
			);
		endforeach;

		return wp_json_encode( $result );

	}



	/**
	 * Single date price
	 *
	 * @param array $rules Single day price list rules.
	 *
	 * @return string
	 */
	public function single_day_price( array $rules ) : string {
		$rule = end( $rules );
		return '';
		// smallest symbol of currency on calendar
		//return '<small class="day__price">' . wc_price( $rule['price'] ) . '</small>';
	}



	/**
	 * Tooltip
	 *
	 * @param array $rules Single day price list rules.
	 *
	 * @return string
	 */
	public function hoover_tooltip( array $rules ) : string {
		$rule = end( $rules );
		return 'data-tippy-content="' . Common::duration_options( $rule['duration'] )['description'] . '"';
	}



	/**
	 * AJAX method to get given month
	 */
	public function load_month() {
		if ( ! wp_verify_nonce( $_POST['security'], 'load_month' ) ) {
			wp_die( 'Unauthorized' );
		}
		$price_list = new PriceList( sanitize_text_field( $_POST['product'] ) );
		$date       = new \DateTime();
		$price_list->set_limit_date( $date );
		$this->render_datepicker( $price_list->price_list( (int) sanitize_text_field( $_POST['year'] ), (int) sanitize_text_field( $_POST['month'] ) ) );
		wp_die();
	}

}
