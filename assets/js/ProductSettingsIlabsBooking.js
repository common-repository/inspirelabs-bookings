class ProductSettingsIlabsBooking {



	/**
	 * Constructor
	 * @param string container Container that contains price list
	 */
	constructor( container )
	{

		this.container = container;
		this.rowsIds   = new Array();
		this.setRowsActions();

		// Select: Year
		this.container.querySelector(".select--year__wrapper > select.select--year ").addEventListener('change', (event) => this.yearSelect(event.target.value));

		// Button: Add row
		this.container.querySelector("footer.toolbar > .row-add").addEventListener("click", () => this.newRow());

	}



	/**
	 * Add event listeners to action buttons
	 */
	setRowsActions()
	{
		let parent = this,
			rowsIdsArray = [],
			rows = this.container.querySelectorAll(".row[data-row]");

		if (rows && rows.length > 0) {
			rows.forEach(function (row) {

				let thisRowId = Number(row.dataset.row);

				rowsIdsArray.push(thisRowId);

				// Disable typing for datepickers
				[ row.querySelector('.start-date'), row.querySelector('.end-date')].forEach(input => {
					input.addEventListener('keypress', event => {
						event.preventDefault();
					})
				});

				// Edit button
				row.querySelector(".row-edit").addEventListener("click", () => parent.editRow(thisRowId));

				// Delete button
				row.querySelector(".row-delete").addEventListener("click", () => parent.deleteRow(thisRowId));

			});

			parent.rowsIds = rowsIdsArray;
		}
	}



	/**
	 * Edit single row
	 *
	 * @param int id - row id
	 * @param bool is_new Is the row rew?
	 */
	editRow(id, is_new = false)
	{

		let parent = this,
			editRow = this.container.querySelector(".row[data-row='" + id + "']"),
			actionButtons = editRow.querySelectorAll(".row-edit, .row-delete"),
			startInput = editRow.querySelector(".start-date"),
			endInput = editRow.querySelector(".end-date"),
			selectedYear = this.container.querySelector(".select--year__wrapper > select.select--year ").value;


		// Add class name Active
		editRow.classList.add("row--active");

		// Create button Accept
		let acceptButton = document.createElement("BUTTON");
		acceptButton.classList.add("row-save");
		acceptButton.classList.add("price-list-cancel");
		acceptButton.setAttribute("type", "button");
		acceptButton.setAttribute("style", "color:green;font-size");
		acceptButton.addEventListener("click", () => {
			closeRow();
		});
		acceptButton.appendChild(document.createTextNode(price_list.transl.accept_button));
		editRow.querySelector(".row__actions").appendChild(acceptButton);

		let startMinDate = new Date();
		let startMaxDate = parent.getDate(parent.rowsIds[ parent.rowsIds.indexOf(id) + 1], 'start');
		if(startMaxDate < startMinDate) {
            startMaxDate = startMinDate;
        }

		// Start datepicker
		let startDate = datepicker(startInput, {
			id: id,
			alwaysShow: true,
			disableYearOverlay: true,
			startDay: 1,
			showAllDates: true,
			respectDisabledReadOnly: true,
			customDays: price_list.translation.days,
			customMonths: price_list.translation.months,
			minDate: new Date(), // allow to pick start only from today
			//minDate: parent.getDate(parent.rowsIds[ parent.rowsIds.indexOf(id) - 1], 'end'),
			maxDate: startMaxDate,
			//maxDate: parent.getDate(parent.rowsIds[ parent.rowsIds.indexOf(id) + 1], 'start'),
			formatter: (input, date, instance) => {
				let month = '' + (date.getMonth() + 1),
					day = '' + date.getDate(),
					year = date.getFullYear();
				if (month.length < 2) {
					month = '0' + month;
				}
				input.value = [year, month, day].join('-');
			},
			onMonthChange: instance => {
				instance.sibling.navigate(new Date(instance.currentYear, instance.currentMonth, 1), true);
			},
			onSelect: instance => {
				//saveButton.disabled = (startInput.value.length == 0);
			}
		});

        let endMinDate = parent.getDate(parent.rowsIds[ parent.rowsIds.indexOf(id) - 1], 'end');
        let endMaxDate = startMaxDate;
        if(endMaxDate < endMinDate) {
            endMaxDate = endMinDate;
        }

		// End datepicker
		let endDate = datepicker(endInput, {
			id: id,
			alwaysShow: true,
			showAllDates: true,
			disableYearOverlay: true,
			startDay: 1,
			respectDisabledReadOnly: true,
			customDays: price_list.translation.days,
			customMonths: price_list.translation.months,
			//minDate: parent.getDate(parent.rowsIds[ parent.rowsIds.indexOf(id) - 1], 'end'),
			minDate: endMinDate,
			//maxDate: parent.getDate(parent.rowsIds[ parent.rowsIds.indexOf(id) + 1], 'start'),
			maxDate: endMaxDate,
			formatter: (input, date, instance) => {
				let month = '' + (date.getMonth() + 1),
					day = '' + date.getDate(),
					year = date.getFullYear();
				if (month.length < 2) {
					month = '0' + month;
				}
				input.value = [year, month, day].join('-');
			},
			onSelect: instance => {
				//saveButton.disabled = (endInput.value.length == 0);
			}
		});

        // Datepicker actions for new and exisiting row
        if (is_new) {
            if (startInput.value == "") {
                startDate.navigate(parent.getDate(parent.rowsIds[ parent.rowsIds.indexOf(id) - 1], 'today'), true);
            } else {
                startDate.navigate(parent.getDate(parent.rowsIds[ parent.rowsIds.indexOf(id) - 1], 'end'), true);
                endDate.navigate(parent.getDate(parent.rowsIds[ parent.rowsIds.indexOf(id) - 1], 'end'), true);
            }
        } else {
            // Set selected date by the user
            startDate.navigate(new Date(startInput.value) , true);
            endDate.navigate(new Date(endInput.value) , true);
        }

		// Eanble price input
		let priceInput = editRow.querySelector(".price");
		priceInput.style.display = "block";
		setInputFilter(priceInput, function (value) {
			//return /^-?\d*[.]?\d{0,2}$/.test(value);
			return /^\d*[.]?\d{0,2}$/.test(value); // positive numbers only
		});

		// Hide actions buttons
		actionButtons.forEach(function (button) {
			button.style.display = "none";
		});

		// Lock rows
		this.lockRows(true);



		/**
		 * Filter input values
		 *
		 * @param object textbox - input selector
		 * @param string inputFilter - filter
		 */
		function setInputFilter(textbox, inputFilter)
		{
			["input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop"].forEach(function (event) {
				textbox.addEventListener(event, function () {

					if (inputFilter(this.value)) {
						this.oldValue = this.value;
						this.oldSelectionStart = this.selectionStart;
						this.oldSelectionEnd = this.selectionEnd;
					} else if (this.hasOwnProperty("oldValue")) {
						this.value = this.oldValue;
						this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
					} else {
						this.value = "";
					}

					if (this.value.length == 0) {
						//saveButton.disabled = true;
					} else {
						//saveButton.disabled = false;
					}

				});
			});
		}



		/**
		 * Close row
		 *
		 * @param int id - row id
		 */
		function closeRow()
		{

			// Remove datepickers
			if (typeof startDate !== 'undefined' && typeof endDate !== 'undefined' ) {
				startDate.remove();
				endDate.remove();
			}

			// Sets dates
			let startInputValue = new Date(editRow.querySelector(".start-date").value);
			editRow.querySelector("span.start-date-wrapper").innerHTML = startInputValue.toLocaleDateString(price_list.locale);

			let endInputValue = new Date(editRow.querySelector(".end-date").value);
			editRow.querySelector("span.end-date-wrapper").innerHTML = endInputValue.toLocaleDateString(price_list.locale);

			// Sets price
			let priceInputValue = Number(editRow.querySelector(".price").value).toFixed(2);
			editRow.querySelector("span.price-wrapper").innerHTML = price_list.currency + ' ' + priceInputValue;

			// Remove class name Active
			editRow.classList.remove("row--active");

			// Disable price input
			editRow.querySelector(".price").style.display = "none";

			// Hide actions buttons
			actionButtons.forEach(function (button) {
				button.style.display = "block";
			});

			// Remove accept button
			acceptButton.remove();

			// Enable all buttons
			parent.lockRows(false);

		}

	}



	/**
	 * Crete new row
	 * @param row
	 */
	newRow(row = false)
	{

		let parent = this,
			id;

		// Get new row id
		if ( this.rowsIds.length > 0 ) {
			id = Number(this.rowsIds[this.rowsIds.length - 1]) + 1;
		} else {
			id = 0;
		}

		// Upadate array with rowIds
		this.rowsIds.push(id);

		// New row
		let rowContainer = document.createElement("DIV");
		rowContainer.classList.add("row", "row--single");
		rowContainer.dataset.row = id;

		// Start date container
		let startContainer = document.createElement("DIV");
		startContainer.classList.add("row__start");
		rowContainer.appendChild(startContainer);

		// Start wrapper
		let startSpan = document.createElement("SPAN");
		startSpan.classList.add("start-date-wrapper");
		if (row && row.hasOwnProperty('start')) {
			startSpan.textContent = parent.formatDate(new Date(row.start));
		}
		startContainer.appendChild(startSpan);

		// Start date input
		let startInput = document.createElement("INPUT");
		startInput.setAttribute("type", "hidden");
		startInput.classList.add("start-date");
		startInput.name = "cz-pricerow[" + id + "][start]";
		if (row && row.hasOwnProperty('start')) {
			startInput.value = new Date(row.start);
		} else {
			if (id == 0 ) {
				startInput.value = '';
			} else {
				startInput.value = this.getDate(this.rowsIds[ this.rowsIds.indexOf(id) - 1], 'start');
			}
		}
		startContainer.appendChild(startInput);

		// End date container
		let endContainer = document.createElement("DIV");
		endContainer.classList.add("row__end");
		rowContainer.appendChild(endContainer);

		// Start wrapper
		let endSpan = document.createElement("SPAN");
		endSpan.classList.add("end-date-wrapper");
		if (row && row.hasOwnProperty('end')) {
			endSpan.textContent = parent.formatDate(new Date(row.end));
		}
		endContainer.appendChild(endSpan);

		// End date input
		let endInput = document.createElement("INPUT");
		endInput.setAttribute("type", "hidden");
		endInput.classList.add("end-date");
		endInput.name = "cz-pricerow[" + id + "][end]";
		if (row && row.hasOwnProperty('end')) {
			endInput.value = new Date(row.end);
		} else {
			if (id == 0 ) {
				endInput.value = '';
			} else {
				endInput.value = this.getDate(this.rowsIds[ this.rowsIds.indexOf(id) - 1], 'start');
			}
		}
		endContainer.appendChild(endInput);

		// Price container
		let priceContainer = document.createElement("DIV");
		priceContainer.classList.add("row__price");
		rowContainer.appendChild(priceContainer);

		// Price wrapper
		let priceSpan = document.createElement("SPAN");
		priceSpan.classList.add("price-wrapper");
		if (row && row.hasOwnProperty("price")) {
			priceSpan.textContent = price_list.currency + ' ' + row.price;
		}
		priceContainer.appendChild(priceSpan);

		// Price input
		let inputPrice = document.createElement("INPUT")
		inputPrice.classList.add("price");
		inputPrice.setAttribute("type", "text");
		inputPrice.name = "cz-pricerow[" + id + "][price]";
		if (row && row.hasOwnProperty("price")) {
			inputPrice.value = row.price;
		} else {
			inputPrice.value = "0.00";
		}
		priceContainer.appendChild(inputPrice);


		// Select wrapper
		let selectSpan = document.createElement("SPAN");
		selectSpan.classList.add("startday-wrapper");
		selectSpan.setAttribute("display", "none");

		// Select day of week input
		let selectDay = document.createElement("SELECT");
		selectDay.classList.add("select--day-of-week");
		selectDay.setAttribute("autocomplete", "off");
		selectDay.name = "cz-pricerow[" + id + "][weekbegin]";

		let daysInWeek = [
			price_list.transl.Monday,
			price_list.transl.Tuesday,
			price_list.transl.Wednesday,
			price_list.transl.Thursday,
			price_list.transl.Friday,
			price_list.transl.Saturday,
			price_list.transl.Sunday
		];

		daysInWeek.forEach((day, index) => {
			let opt = document.createElement('option');
			opt.value = index+1;
			opt.textContent += day;
			if(index === 0) {
				opt.setAttribute("selected", "selected");
			}
			selectDay.appendChild(opt);
		});
		selectSpan.appendChild(selectDay);
		priceContainer.appendChild(selectSpan);



		// Duration container
		let durationContainer = document.createElement("DIV");
		durationContainer.classList.add("row__duration");
		rowContainer.appendChild(durationContainer);

		// Duration options
		if (price_list.hasOwnProperty('duration')) {
			let no = 0;
			for (const [index, period] of Object.entries(price_list.duration)) {

				let input = document.createElement("INPUT");
				input.setAttribute("type", "radio");
				input.id = "cz-pricerow-" + id + "-" + index;
				input.name = "cz-pricerow[" + id + "][duration]";
				input.value = index;

				if (row && row.hasOwnProperty("duration") && row.duration === period.value) {
					input.checked = true;
				} else {
					input.checked = (no == 0 ? true : false);
				}

				durationContainer.appendChild(input);

				let label = document.createElement("LABEL");
				label.classList.add("option");
				label.htmlFor = "cz-pricerow-" + id + "-" + index;
				durationContainer.appendChild(label);
				label.addEventListener("click", this.showDayselect);

				let title = document.createElement("SPAN");
				title.classList.add("option__name");
				title.appendChild(document.createTextNode(period.label));
				label.appendChild(title);

				if (period.hasOwnProperty('description')) {
					let description = document.createElement("SMALL");
					description.classList.add("option__description");
					description.appendChild(document.createTextNode(period.description));
					label.appendChild(description);
				}

				no++; // Increment number.
			}
		}

		// Actions container
		let actionsContainer = document.createElement("DIV");
		actionsContainer.classList.add("row__actions");
		rowContainer.appendChild(actionsContainer);

		// Create button edit
		let editButton = document.createElement("BUTTON");
		editButton.classList.add("row-edit");
		editButton.setAttribute("type", "button");
		editButton.addEventListener("click", () => {
			this.editRow(id)
		});
		editButton.appendChild(document.createTextNode(price_list.transl.edit_button));
		actionsContainer.appendChild(editButton);

		// Create button delete
		let deleteButton = document.createElement("BUTTON");
		deleteButton.classList.add("row-delete");
		deleteButton.setAttribute("type", "button");
		deleteButton.addEventListener("click", () => {
			this.deleteRow(id)
		});
		deleteButton.appendChild(document.createTextNode(price_list.transl.delete_button));
		actionsContainer.appendChild(deleteButton);

		// Append to price list
		this.container.querySelector(".rows--price_list").appendChild(rowContainer);

		// Open new row in edit mode
		if (row === false) {
			this.editRow(id, true);
		}

	}



	/**
	 * Function to show/hide week day select for new rows
	 *
	 */
	showDayselect()
	{
		let parent_node = this.parentNode;
		let parent_node_prev = parent_node.previousElementSibling;
		let day_select = parent_node_prev.querySelector('.startday-wrapper');

		if(this.getAttribute('for').indexOf('week') >= 0) {
			parent_node_prev.style.display = "block";
			parent_node_prev.querySelector('input.price').style.marginBottom =  '10px';
			day_select.style.display = "block";

		} else {
			day_select.style.display = "none";
			parent_node_prev.style.display = "flex";
		}
	}



	/**
	 * Delete single row
	 *
	 * @param int id - row id
	 */
	deleteRow(id)
	{

		let parent = this,
			removeRow = this.container.querySelector(".row[data-row='" + id + "']");
		removeRow.remove();

		let updatedRowsIds = parent.rowsIds.filter(function (value, index, arr) {
			return value != parent.rowsIds.indexOf(id);
		});

		// Upadate array with rowIds
		this.rowsIds = updatedRowsIds;
	}



	/**
	 * Add row section
	 *
	 * @param int id - row id
	 * @param param minDate/maxDate
	 */
	getDate(id, className)
	{

		let date,
			yearSelector = this.container.querySelector(".select--year");

		if (className === "start") {
			if (typeof id !== "undefined" && (this.container.querySelector(".row[data-row='" + id + "'] .start-date") != null)) {
				date = new Date(this.container.querySelector(".row[data-row='" + id + "'] .start-date").value);
			} else {
				date = new Date(yearSelector[yearSelector.selectedIndex].value, 11, 31);
			}
		} else {
			if (typeof id !== "undefined" && (this.container.querySelector(".row[data-row='" + id + "'] .end-date") != null)) {
				date = new Date(this.container.querySelector(".row[data-row='" + id + "'] .end-date").value);
			} else {
				date = new Date(yearSelector[yearSelector.selectedIndex].value, 0, 1);
			}
		}

		return date;

	}



	/**
	 * Format date
	 *
	 * @param obj date object
	 * @return string
	 */
	formatDate(date, format = 'd.m.Y')
	{
		let month = '' + (date.getMonth() + 1),
			day = '' + date.getDate(),
			year = date.getFullYear();
		if (month.length < 2) {
			month = '0' + month;
		}
		return format.replace('d',day).replace('m',month).replace('Y',year);
	}



	/**
	 * Lock
	 * Simple function to disable all edit buttons when single one is edited
	 *
	 * @param mixed id(integer)/fale(bool)(to unlock all rows)
	 */
	lockRows(mode)
	{

		let actionButtons = this.container.querySelectorAll(".row-edit, .row-delete, .row-add, .row-clear");

		actionButtons.forEach(function (button) {
			button.disabled = mode;
		});
	}


	/**
	 * Select years
	 */
	yearSelect(year)
	{
		let parent = this;

		jQuery.ajax({
			type: "post",
			dataType: "json",
			url: price_list.ajax_url,
			tryCount : 0,
			retryLimit : 3,
			data : {
				action: "get_pricelist",
				year: year,
				post_id: price_list.post_id,
				security: price_list.security,
			},
			success: function (response) {

				// Clear container
				parent.container.querySelector(".rows--price_list").innerHTML = "";

				// Load pricelist if exists
				if (response.success) {
					response.data.forEach(function (row) {
						parent.newRow(row);
					});
				}
			},
			error : function (xhr, textStatus, errorThrown ) {
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
document.addEventListener('DOMContentLoaded', (event) => {
	new ProductSettingsIlabsBooking(document.getElementById('booking_price_list'));
});
