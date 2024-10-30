jQuery("#enable_24h").change(function() {
	if(this.checked) {
		jQuery('.checkbox--24h__wrapper').css('background', '#a6e57a');
	} else {
		jQuery('.checkbox--24h__wrapper').css('background', '#ddd');
	}
});

jQuery(document).on('ready', function(){

	jQuery('.row-edit').on('click', function() {

		let input = jQuery(this).parent().prev('.row__duration').find('input');
		let active_point = jQuery(this).parent().prev('.row__duration').find('input:checked');
		let active_point_id = jQuery(this).parent().prev('.row__duration').find('input:checked').attr('id');
		if(active_point_id.indexOf('week') >= 0) {
			let day_select_wrapper = jQuery(active_point).parent().prev('.row__price');
			let day_select = jQuery(active_point).parent().prev('.row__price').find('.startday-wrapper');
			jQuery(day_select_wrapper).css('display', 'block');
			jQuery(day_select_wrapper).find('input.price').css('margin-bottom', '10px');
			jQuery(day_select).show();

			let label = jQuery(active_point).parent().find('label');
			let label_id = jQuery(active_point).parent().find('label').attr('for');

			jQuery(label).on('click', function() {
				if(label_id.indexOf('week') === -1) {
					jQuery(day_select).hide();
				} else {
					jQuery(day_select).show();
				}
			});

			jQuery('.price-list-cancel').on('click', function() {
				//jQuery(day_select).hide();
			})

		}

		jQuery('.option').on('click', function() {

			let label = jQuery(this).attr('for');
			let day_select_wrapper = jQuery(this).parent().prev('.row__price');
			let day_select = jQuery(this).parent().prev('.row__price').find('.startday-wrapper');

			if(label.indexOf('week') === -1) {
				jQuery(day_select).hide();
				jQuery(day_select_wrapper).css('display', 'flex');
			} else {
				jQuery(day_select_wrapper).css('display', 'block');
				jQuery(day_select_wrapper).find('input.price').css('margin-bottom', '10px');
				jQuery(day_select).show();
			}
		});

		jQuery('.price-list-cancel').on('click', function() {
			//jQuery('.startday-wrapper').hide();
			//jQuery('.row__price').css('display', 'flex');
			jQuery(this).parent().prev('.row__price').css('display', 'flex');
		})
	});
});
