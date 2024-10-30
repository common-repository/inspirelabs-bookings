(function ($) {
    $(document).ready(function (){
        show_advance();
    });

    $( document).on( 'updated_checkout', function(){
        show_advance();
    });

    $( document).on( 'updated_cart_totals', function(){
        show_advance();
    });

    function show_advance() {

        if(typeof booking_data.advance != 'undefined' && booking_data.advance !== null) {

            let order_total = $('.order-total');
            let advance = $(order_total).clone();
            $(advance).find('th').text(booking_data.advance + ' (' + booking_data.advance_percent + ')'); // for desktop version
            $(advance).find('td').attr('data-title', booking_data.advance); // for mobile version
            let cart_subtotal = $('.cart-subtotal');
            let fees = $('.shop_table').find('.fee');
            if(fees.length > 1) {
                $('.shop_table').find('.fee').eq(fees.length - 1).before(advance);
            } else {
                $(cart_subtotal).after(advance);
            }
        }
    }

})(jQuery);