<?php
/**
 * Table with booking details show under the order item table.
 *
 * @package WPDesk\PluginTemplate
 *
 * @var string $date_format WordPress date format from settings.
 * @var array  $selected_dates Array of strings with selected days.
 * @var array  $booking_details Includes order details like price, duration, date, etc.
 * @var string $booking_key Key used to identity booking.
 */

use WPDesk\Bookings\Helpers\Common;

?>

<div class="view">
	<table class="display_meta">
		<tbody>
		<tr>
			<th><?php esc_html_e( 'Date from', 'inspirelabs-bookings' ); ?></th>
			<td><?php echo esc_html( date_i18n( $date_format, strtotime( reset( $selected_dates ) ) ) ); ?></td>
		</tr>
		<tr>
			<th><?php esc_html_e( 'Date to', 'inspirelabs-bookings' ); ?></th>
			<td><?php echo esc_html( date_i18n( $date_format, strtotime( end( $selected_dates ) ) ) ); ?></td>
		</tr>
		<?php if (end($booking_details)['price'] === '0.00') { ?>
			<tr>
				<th><?php esc_html_e( 'Nights no', 'inspirelabs-bookings' ); ?></th>
				<td><?php echo esc_html( count( $selected_dates ) - 1 ); ?></td>
			</tr>
		<?php } ?>
		<tr>
			<th><?php esc_html_e( 'Selected days', 'inspirelabs-bookings' ); ?></th>
			<td>
				<ol class="display_meta_bookings_details">
					<?php foreach ( $booking_details as $day => $details ) : ?>
						<li>
							<?php echo esc_html( date_i18n( $date_format, strtotime( $day ) ) ); ?>,
							<?//php esc_html_e( 'per', ''inspirelabs-bookings'' ); ?>
							<?//php echo esc_html( Common::duration_options( $details['duration'] )['label'] ); ?>
							<?php echo wp_kses_data( wc_price( (float) $details['price'] ) ); ?>
						</li>
					<?php endforeach; ?>
				</ol>
			</td>
		</tr>
		</tbody>
	</table>
	<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=ilabs-bookings' ) . '&booking=' . $booking_key ); ?>"><?php esc_html_e( 'Edit booking', 'inspirelabs-bookings' ); ?></a>
</div>
