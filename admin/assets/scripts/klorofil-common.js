$(document).ready(function() {
	// TOP NAVIGATION AND LAYOUT
	$('.btn-toggle-fullwidth').on('click', function() {
		if(!$('body').hasClass('layout-fullwidth')) {
			$('body').addClass('layout-fullwidth');

		} else {
			$('body').removeClass('layout-fullwidth');
			$('body').removeClass('layout-default'); // also remove default behaviour if set
		}

		$(this).find('.lnr').toggleClass('lnr-arrow-left-circle lnr-arrow-right-circle');

		if($(window).innerWidth() < 1025) {
			if(!$('body').hasClass('offcanvas-active')) {
				$('body').addClass('offcanvas-active');
			} else {
				$('body').removeClass('offcanvas-active');
			}
		}
	});

	$(window).on('load', function() {
		if($(window).innerWidth() < 1025) {
			$('.btn-toggle-fullwidth').find('.icon-arrows')
			.removeClass('icon-arrows-move-left')
			.addClass('icon-arrows-move-right');
		}

		// adjust right sidebar top position
		$('.right-sidebar').css('top', $('.navbar').innerHeight());

		// if page has content-menu, set top padding of main-content
		if($('.has-content-menu').length > 0) {
			$('.navbar + .main-content').css('padding-top', $('.navbar').innerHeight());
		}

		// for shorter main content
		if($('.main').height() < $('#sidebar-nav').height()) {
			$('.main').css('min-height', $('#sidebar-nav').height());
		}
	});

	// SIDEBAR NAVIGATION
	$('.sidebar a[data-toggle="collapse"]').on('click', function() {
		if($(this).hasClass('collapsed')) {
			$(this).addClass('active');
		} else {
			$(this).removeClass('active');
		}
	});

	if( $('.sidebar-scroll').length > 0 ) {
		$('.sidebar-scroll').slimScroll({
			height: '95%',
			wheelStep: 2,
		});
	}
});

// TOGGLE FUNCTION
$.fn.clickToggle = function( f1, f2 ) {
	return this.each( function() {
		var clicked = false;
		$(this).bind('click', function() {
			if(clicked) {
				clicked = false;
				return f2.apply(this, arguments);
			}

			clicked = true;
			return f1.apply(this, arguments);
		});
	});
}

// USER ADDED SCRIPTS
function check_all_selectCheckbox(select_all) {
    $('.selectCheckbox').each(function(){ this.checked = (select_all.hasClass("select")); });
}

// Bulk options select all checkboxes
$('.select_all_checkboxes').on("click", function(){
    check_all_selectCheckbox($(this));
    $(".select_all_checkboxes").toggleClass("select");
    $(".select_all_checkboxes").toggleClass("deselect");
});

// Stop double-firing checkbox click when a .selectCheckbox is clicked directly instead
// of surrounding .clickable_td
$(document).on("click", ".selectCheckbox", function(event){
    event.stopPropagation();
});

// When clicking td with a bulk options select checkbox in it, toggle the checkbox
$(document).on("click", ".clickable_td", function(){
    let nearest_checkbox = $(this).find(".selectCheckbox");
    if (nearest_checkbox.length > 0) {
        let nearest_checkbox_checked = nearest_checkbox.is(":checked");
        (nearest_checkbox_checked) ? nearest_checkbox.prop("checked", false) : nearest_checkbox.prop("checked", true)
    }
});