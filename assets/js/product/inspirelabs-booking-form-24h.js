class BookingForm {

	/**
	 * Booking Form class constructor
	 */
	constructor( container ) {

		this.container       = container;
		if(this.container.offsetWidth < 800) {
			if (container.classList.contains('inspirelabs_wide_tabs_woo')) {
				container.classList.remove('inspirelabs_wide_tabs_woo');
			}
		}
		this.bookingDetInput = document.getElementsByName("booking-details");
		this.bookingDetList  = container.querySelector(".booking__summary > .booking__summary__items");
		this.bookingTotal    = container.querySelector(".booking__summary > .booking__summary__total > .price");
		this.bookingTotalRow = container.querySelector(".booking__summary > .booking__summary__total");
		this.addToCartBtn    = container.querySelector(".booking__summary > .add_to_cart_button");
		this.daysCheckboxes  = [];
		this.swiper        = new Swiper('.datepicker > .swiper-container', {
			slidesPerView: 1,
			spaceBetween: 0,
			slideClass: 'month',
			breakpoints: {
				576: {
					slidesPerView: 2,
				},
				768: {
					slidesPerView: 1,
				},
				992: {
					slidesPerView: 2,
				},
			},
		});
		this.prevMonthBtn    = container.querySelector(".datepicker-navigation.navigation-prev");
		this.nextMonthBtn    = container.querySelector(".datepicker-navigation.navigation-next");

		this.setChekboxDays();
		this.setChekboxDaysEvents();
		this.setNavigationEvents();
	}



	/**
	 * Set array of days checkboxes
	 */
	setChekboxDays(){
		this.daysCheckboxes = Array.from( this.container.querySelectorAll(".datepicker .month .days input[type='checkbox']") );
	}



	/**
	 * Return all checked days as array
	 */
	getCheckedDays() {
		return this.daysCheckboxes.filter(function(checkbox){
			return checkbox.checked;
		});
	}



	/**
	 * Add event listeners to days checkboxes and navigation buttons
	 */
	setChekboxDaysEvents() {
		tippy('[data-tippy-content]');
		if (this.daysCheckboxes && this.daysCheckboxes.length > 0) {
			this.daysCheckboxes.forEach((checkbox) => {
				checkbox.addEventListener('click', (event) => this.checkDay(event.target));
			});
		}
	}



	/**
	 * Add event listeners to months navigation
	 */
	setNavigationEvents() {
		this.prevMonthBtn.addEventListener('click', (event) => this.getPrevMonth());
		this.nextMonthBtn.addEventListener('click', (event) => this.getNextMonth());
	}



	/**
	 * Select days event
	 *
	 * @param DOM checbox Checkbox input
	 */
	checkDay(checkbox) {

		let parent = this,
			checkedDays = parent.getCheckedDays(),
			firstSelectedDay = parent.findFirstDay(checkedDays[0]),
			lastSelectedDay = null;

		// Remove all classes from all checkboxes.
		this.daysCheckboxes.forEach((singleDay) => {
			singleDay.classList.remove("first", "last");
		});

		// Deselect checked days
		if (checkbox.checked == false) {

			// Uncheck checked checkboxes.
			checkedDays.forEach((singleDay) => {
				singleDay.checked = false;
			});

		} else {

			// There is one selected day
			if (checkedDays.length === 1) {
				lastSelectedDay = parent.findLastDay(this.daysCheckboxes[this.daysCheckboxes.indexOf(firstSelectedDay) + 1] );

				// There are more days selected
			} else {
				lastSelectedDay = parent.findLastDay( checkedDays[checkedDays.length-1]);
			}

			// Remove all classes from all checkboxes.
			this.daysCheckboxes.forEach((singleDay) => {
				singleDay.checked = false;
			});

			let is_single_booking_in_range = this.checkRange(firstSelectedDay, lastSelectedDay);

			if(!is_single_booking_in_range) {
				let checkBookedDays = this.checkBookedDays(firstSelectedDay, lastSelectedDay);
				checkBookedDays.forEach((singleDay, index) => {
					singleDay.checked = true;
				});
				if(typeof(checkBookedDays[0]) !== 'undefined') {
					checkBookedDays[0].classList.add("first");
					checkBookedDays[checkBookedDays.length-1].classList.add("last");
				} else {
					checkedDays.forEach((singleDay) => {
						singleDay.checked = false;
					});
				}

			} else {
				checkedDays.forEach((singleDay) => {
					singleDay.checked = false;
				});
			}
		}

		parent.updateReservation(this.daysCheckboxes.slice(this.daysCheckboxes.indexOf(firstSelectedDay), this.daysCheckboxes.indexOf(lastSelectedDay)+1));
	}



	/**
	 * Additional validation
	 * Check possibility (bug) to book if single booked day appear inside the range
	 * or bug with week booking
	 * @param DOM checbox firstSelectedDay input
	 * @param DOM checbox lastSelectedDay input
	 */

	checkRange(firstSelectedDay, lastSelectedDay) {
		let $flag = false;
		let checkBookedDays = this.checkBookedDays(firstSelectedDay, lastSelectedDay);
		let checkedDayScheme = this.testSelectedDay(firstSelectedDay);

		if (checkedDayScheme.scheme.length === 1) {

			if (checkedDayScheme.scheme.includes('week') || checkedDayScheme.scheme.includes('period')) {
				if(checkBookedDays[0] !== 'undefined') {
					checkBookedDays.forEach((singleDay, index, array) => {
						if(index !== 0 && index !== array.length - 1 ) {
							let label_classlist = jQuery(singleDay).next('label').attr('class').split(/\s+/);
							jQuery.each(label_classlist, function(index, item) {
								if (item === 'deny-reservation') {
									$flag = true;
								}
							});
						}
					});
				}
			} else {

				if(checkBookedDays[0] !== 'undefined' && checkBookedDays.length > 2) {
					checkBookedDays.forEach((singleDay, index, array) => {
						if(index !== 0 && index !== array.length - 1 ) {
							let label_classlist = jQuery(singleDay).next('label').attr('class').split(/\s+/);
							jQuery.each(label_classlist, function(index, item) {
								if (item === 'first-day-book' || item === 'last-day-book') {
									$flag = true;
								}
							});
						}
					});
				}

				if(checkBookedDays[0] !== 'undefined' && checkBookedDays.length === 2) {
					if( jQuery(firstSelectedDay).next('label').hasClass('first-day-book')
						&& jQuery(lastSelectedDay).next('label').hasClass('last-day-book') )
					{
						$flag = true;
					}
				}

			}

		}

		return $flag;
	}



	/**
	 * Find first day of selection
	 *
	 * @param DOM checbox Checkbox input
	 */
	findFirstDay(checkbox) {
		let alert = document.getElementsByClassName('entire-period-booking-alert');
		let parent           = this,
			checkedDayScheme = this.testSelectedDay(checkbox),
			firstDayCheckbox = checkbox;

		if (checkedDayScheme.scheme.length === 1) {
			if (checkedDayScheme.scheme.includes('day')) {
				// Do nothing, it is default value.
			}

			// week range logic
			if (checkedDayScheme.scheme.includes('week') ) {
				let data_arr =  JSON.parse(checkbox.dataset.charter);

				let startDay = +data_arr[0].weekbegin;
				let startDayClass = '';
				let daysInWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

				daysInWeek.forEach((day, index) => {
					if(startDay === index+1) {
						startDayClass = day;
					}
				});

				let find = '';
				let label = checkbox.nextElementSibling;

				if(label.classList.contains(startDayClass) ) {
					find = checkbox;
				} else {
					let nearest_prev = parent.getPrevSibling(label, '.' + startDayClass);
					if('undefined' === typeof(nearest_prev) ) {
						let checkedDays = parent.getCheckedDays();
						checkedDays.forEach((singleDay) => {
							singleDay.checked = false;
						});
						let message = 'alerttext_'  + startDay;
						if (Object.keys(booking.alerttext).length > 1) {
							jQuery(alert).text(booking.alerttext[message]);
							jQuery(alert).show();
						}
						return;

					} else {
						jQuery(alert).hide();
						find = nearest_prev.previousElementSibling;
						firstDayCheckbox = find;
					}
				}
			}


			if ( checkedDayScheme.scheme.includes('period') ) {
				checkedDayScheme.class.forEach(function(singleClassName) {
					let daysToSelect = parent.daysCheckboxes.filter(input => input.classList.contains(singleClassName));
					firstDayCheckbox = daysToSelect[0];
				});
			}
		}

		return firstDayCheckbox;
	}



	/**
	 * Function-helper
	 *
	 * @param elem
	 * @param selector
	 */

	getNextSibling(elem, selector) {

		// Get the next sibling element
		var sibling = elem.nextElementSibling;

		// If there's no selector, return the first sibling
		if (!selector) return sibling;

		// If the sibling matches our selector, use it
		// If not, jump to the next sibling and continue the loop
		while (sibling) {
			if (sibling.matches(selector)) return sibling;
			sibling = sibling.nextElementSibling
		}
	};



	/**
	 * Function-helper
	 *
	 * @param elem
	 * @param selector
	 */
	getPrevSibling(elem, selector) {

		// Get the next sibling element
		var sibling = elem.previousElementSibling;

		// If there's no selector, return the first sibling
		if (!selector) return sibling;

		// If the sibling matches our selector, use it
		// If not, jump to the next sibling and continue the loop
		while (sibling) {
			if (sibling.matches(selector)) return sibling;
			sibling = sibling.previousElementSibling
		}
	};



	/**
	 * Find last day of selection
	 *
	 * @param DOM checbox Checkbox input
	 */
	findLastDay(checkbox) {
		let alert = document.getElementsByClassName('entire-period-booking-alert');
		let parent           = this,
			checkedDayScheme = this.testSelectedDay(checkbox),
			lastDayCheckbox  = checkbox;

		if (checkedDayScheme.scheme.includes('day')) {
			// Do nothing, it is default value.
		}

		// week range logic for 24h-mode: start (check-in) and end (check-out) same day
		if (checkedDayScheme.scheme.includes('week') ) {
			let data_arr =  JSON.parse(checkbox.dataset.charter);
			let startDay = +data_arr[0].weekbegin;

			let endDay = startDay;

			let daysInWeek = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

			let endDayClass = '';

			daysInWeek.forEach(function(elem, index) {
				if(index+1 === endDay) {
					endDayClass = elem;
				}
			});

			let findClass = '.' + endDayClass;
			let find = '';
			let label = checkbox.nextElementSibling;

			if(label.classList.contains(endDayClass) ) {
				find = checkbox;
			} else {
				let nearest_next = parent.getNextSibling(label, findClass);
				if('undefined' === typeof(nearest_next) ) {
					let checkedDays = parent.getCheckedDays();
					checkedDays.forEach((singleDay) => {
						singleDay.checked = false;
					});
					let message = 'alerttext_'  + startDay;
					if (Object.keys(booking.alerttext).length > 1) {
						jQuery(alert).text(booking.alerttext[message]);
						jQuery(alert).show();
					}
					return;

				} else {
					jQuery(alert).hide();
					find = nearest_next.previousElementSibling;
					lastDayCheckbox = find;
				}
			}
		}


		if ( checkedDayScheme.scheme.includes('period') ) {
			checkedDayScheme.class.forEach(function(singleClassName) {
				let daysToSelect = parent.daysCheckboxes.filter(input => input.classList.contains(singleClassName));
				lastDayCheckbox = daysToSelect[daysToSelect.length - 1];
			});
		}
		return lastDayCheckbox;
	}



	/**
	 * Find last day of selection
	 *
	 * @param DOM checbox Checkbox input
	 */
	checkBookedDays(firstSelectedDay, lastSelectedDay) {
		let selectedDays = this.daysCheckboxes.slice(this.daysCheckboxes.indexOf(firstSelectedDay), this.daysCheckboxes.indexOf(lastSelectedDay)+1);

		// Get booked days form selection
		let bookedDays   = selectedDays.filter(function(checkbox){
			return checkbox.disabled;
		});

		if (bookedDays.length > 0) {
			firstSelectedDay = this.findFirstDay( bookedDays[bookedDays.length-1] );
			return this.daysCheckboxes.slice(this.daysCheckboxes.indexOf(firstSelectedDay)+1, this.daysCheckboxes.indexOf(lastSelectedDay)+1)
		} else {
			return selectedDays;
		}
	}



	/**
	 * Test checked date for price list scheme
	 *
	 * @param DOM checbox Checkbox input
	 */
	testSelectedDay(checkbox){

		let schemes = ['week', 'period', 'day'],
			details = {
				scheme : [],
				class : []
			};

		schemes.forEach(function(scheme) {
			let regex =  new RegExp( scheme + "-.*");
			if(checkbox !== undefined ) {
				if (checkbox.className.split(' ').some(function(c){ return regex.test(c); })) {
					details.scheme.push(scheme);
					details.class.push(Array.from(checkbox.classList).find(value => regex.test(value)));
				}
			}
		});

		return details;
	}



	/**
	 * Update Reservation Object
	 */
	updateReservation() {

		if (this.getCheckedDays().length > 0) {

			let fixed_period = false;

			// Create reservation Obj
			let reservation = new Object;
			this.getCheckedDays().forEach((checkbox) => {
				let checkedDayScheme = this.testSelectedDay(checkbox);
				if (checkedDayScheme.scheme.includes('week') || checkedDayScheme.scheme.includes('period')) {
					fixed_period = true;
				}
				reservation[checkbox.value] = JSON.parse(checkbox.dataset.charter);
			});

			// Set last day price to zero if there are more days than 1
			if (Object.keys(reservation).length > 1) {
				let lastDay = Object.keys(reservation)[Object.keys(reservation).length - 1];
				if (reservation[lastDay].length > 1) {
					reservation[lastDay].splice(1, 1);
				}
				reservation[lastDay][0]['price'] = '0.00';
			}

			// Remove unnecessary second price list from single day
			// For e.g. if first day has two price lists
			// Important for form submission
			Object.keys(reservation).forEach( (day) => {

				if (reservation[day].length > 1) {
					let indexNo = Object.keys(reservation).findIndex( (key) => {
						return key == day;
					});
					if (indexNo == 0) {
						reservation[day].splice(0, 1);
					} else {
						reservation[day].splice(1, 1);
					}
				}
			});

			let begin = Object.keys(reservation)[0];
			let end = Object.keys(reservation)[Object.keys(reservation).length-1];

			if(fixed_period && (begin === end) ){
				this.calculatePrice(); // Empty summary list and set total to zero
				this.bookingDetInput[0].value = ''; // Empty input value

			} else {
				this.calculatePrice(reservation); // Calculate price and populate summary list
				this.bookingDetInput[0].value = JSON.stringify(reservation); // Set the input value for form submission
			}

		} else {
			this.calculatePrice(); // Empty summary list and set total to zero
			this.bookingDetInput[0].value = ''; // Empty input value
		}
	}



	/**
	 * Update Reservation Object
	 *
	 * @param false|obj reservation Reservation object to calculate price from
	 */
	calculatePrice(reservation = false) {

		this.bookingDetList.innerHTML = ""; // Empty items list
        this.bookingDetList.style.display = "none";
        if (reservation) {
			let total = 0;

			// First item with check in date
			let liCheckIn = document.createElement("LI")
			liCheckIn.appendChild(document.createTextNode(booking.alerttext.checkin + ': '));
			let liCheckInDay = document.createElement("TIME");
			liCheckInDay.setAttribute("datetime", Object.keys(reservation)[0]);
			liCheckInDay.appendChild(document.createTextNode(Object.keys(reservation)[0]));
			liCheckIn.appendChild(liCheckInDay);
			this.bookingDetList.appendChild(liCheckIn);

			// Sum single days price to total
			Object.keys(reservation).forEach( (day, index) => {
				total += parseFloat(reservation[day][0]['price']);

				if ( index != ( Object.keys(reservation).length - 1)) {
					let li = document.createElement("LI");
					li.classList.add('countable');
					let spanDay = document.createElement("TIME");
					spanDay.setAttribute("datetime", day);
					spanDay.appendChild(document.createTextNode(day));
					li.appendChild(spanDay);
					let spanPrice = document.createElement("SPAN");
					spanPrice.classList.add("price");
					spanPrice.appendChild(document.createTextNode('1x ' + reservation[day][0]['price'] + ' ' + booking.currency));
					li.appendChild(spanPrice);
					this.bookingDetList.appendChild(li);
				}
				// make font less when very long list of day for booking
				if(index > 6) {
					jQuery(this.bookingDetList).css('font-size', '0.75em');
				} else {
					jQuery(this.bookingDetList).attr("style", "");
				}
			});

			// Last day with checkout date
			let liCheckOut = document.createElement("LI");
			liCheckOut.appendChild(document.createTextNode(booking.alerttext.checkout + ': '));
			let liCheckOutDay = document.createElement("TIME");
			liCheckOutDay.setAttribute("datetime", Object.keys(reservation)[Object.keys(reservation).length-1]);
			liCheckOutDay.appendChild(document.createTextNode(Object.keys(reservation)[Object.keys(reservation).length-1]));
			liCheckOut.appendChild(liCheckOutDay);
			this.bookingDetList.appendChild(liCheckOut);

			this.bookingDetList.style.removeProperty('display');
			this.bookingTotal.textContent = total.toFixed(2) + ' ' + booking.currency; // Set total price
			this.addToCartBtn.disabled = false; // Enable add to cart button
            // change calculation if short booking fee is enabled
            let duration = Object.keys(reservation).length;
            this.addShortBookingFees(duration, total);

		} else {
			this.addToCartBtn.disabled = true; // Disable add to cart button
			this.bookingTotal.textContent = '0,00 ' + booking.currency; // Set placeholder for price
            jQuery('.short_booking_notice').remove();
		}
	}

    /**
     * Update Calculation if short booking fees option is not empty
     *
     * @param duration reservation duration
     * @param total amount of booking without fees
     */
    addShortBookingFees(duration, total) {
	    let nights = duration - 1;
	    let fees = booking.short_booking_fees;

	    let short_booking_notice = document.createElement("DIV");
        short_booking_notice.classList.add('short_booking_notice');

	    if( nights < 4 && !jQuery.isEmptyObject(fees) ) {
            let fee = + fees[nights];

            if( fee > 0 ) {
                let fee_value = total * fee / 100;
                let total_with_fee = fee_value + total;

                jQuery('.short_booking_notice').remove();

                let spanPrice = document.createElement("SPAN");
                spanPrice.classList.add("price");
                spanPrice.appendChild(document.createTextNode(fee_value.toFixed(2) + ' ' + booking.currency));
                short_booking_notice.innerText = booking.alerttext.short_booking_text + ' ';
                short_booking_notice.appendChild(spanPrice);

                this.bookingTotal.textContent = total_with_fee.toFixed(2) + ' ' + booking.currency;
                this.bookingTotalRow.before(short_booking_notice);
            } else {
                jQuery('.short_booking_notice').remove();
                this.bookingTotal.textContent = total.toFixed(2) + ' ' + booking.currency;
            }
        } else {
            jQuery('.short_booking_notice').remove();
            this.bookingTotal.textContent = total.toFixed(2) + ' ' + booking.currency;
        }
    }



	/**
	 * Return all visible and/or previously loaded months as array
	 */
	getLoadedMonths() {
		let loadedMonths = [];
		Array.from( this.container.querySelectorAll(".datepicker .month") ).forEach( (month) => {
			loadedMonths.push(month.id);
		});
		return loadedMonths;
	}



	/**
	 * Go to next month and/or load next month from ajax
	 */
	getPrevMonth() {
		if (this.swiper.isBeginning) {
			let earliestMonth = new Date(this.getLoadedMonths()[0]);
			earliestMonth.setMonth(earliestMonth.getMonth() - 1); // Reduce month number by 1
			let year = earliestMonth.getFullYear(),
				month = earliestMonth.getMonth();
			this.ajaxGetMonth(year, month, 'prepend');
		} else {
			this.swiper.slidePrev();
		}
	}



	/**
	 * Go to previous month and/or load previous month from ajax
	 */
	getNextMonth(){
		if (this.swiper.isEnd) {
			let latestMonth = new Date(this.getLoadedMonths()[this.getLoadedMonths().length - 1]);
			latestMonth.setMonth(latestMonth.getMonth() + 1); // Increase month number by 1
			let year = latestMonth.getFullYear(),
				month = latestMonth.getMonth();
			this.ajaxGetMonth(year, month, 'append');
		} else {
			this.swiper.slideNext();
		}
	}



	/**
	 * AJAX get month and add it to swiper slider
	 *
	 * @param year  int
	 * @param month int
	 * @param direction string Available options: prepend or append.
	 */
	ajaxGetMonth(year,month,direction) {
		let parent = this;
		jQuery.ajax({
			type: 'POST',
			url: booking.ajaxUrl,
			dataType: 'html',
			tryCount : 0,
			retryLimit : 3,
			data: {
				action:  'load_month',
				security: booking.security,
				year:    year,
				month:   month + 1, // +1 caused by js format
				product: booking.product,
			},
			beforeSend: function(){
				parent.prevMonthBtn.disabled = true;
				parent.nextMonthBtn.disabled = true;
				jQuery(parent.container).find('.datepicker').addClass('spinner');
			},
			success:function(month){
				if ('prepend' === direction){
					let earliest = document.getElementById(parent.getLoadedMonths()[0]);
					parent.swiper.prependSlide(month);
					parent.swiper.slideTo(0);
				} else {
					let latest = document.getElementById( parent.getLoadedMonths()[parent.getLoadedMonths().length-1] );
					parent.swiper.appendSlide(month);
					parent.swiper.slideTo(parent.swiper.slides.length - 1);
				}
				jQuery(parent.container).find('.datepicker').removeClass('spinner');
				parent.setChekboxDays();
				parent.setChekboxDaysEvents();
				parent.prevMonthBtn.disabled = false;
				parent.nextMonthBtn.disabled = false;
			},
			error : function(xhr, textStatus, errorThrown ) {
				if (textStatus == 'timeout') {
					this.tryCount++;
					if (this.tryCount <= this.retryLimit) {
						$.ajax(this);
						return;
					}
					return;
				}
			}
		});
	}

}

// Load script when DOM is loaded
new BookingForm( document.getElementById('inspirelabs-booking-form') );
