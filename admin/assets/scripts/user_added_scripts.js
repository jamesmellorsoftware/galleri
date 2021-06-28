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