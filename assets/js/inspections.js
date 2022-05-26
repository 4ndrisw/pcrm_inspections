// Init single inspection
function init_inspection(id) {
    load_small_table_item(id, '#inspection', 'inspectionid', 'inspections/get_inspection_data_ajax', '.table-inspections');
}


// Validates inspection add/edit form
function validate_inspection_form(selector) {

    selector = typeof (selector) == 'undefined' ? '#inspection-form' : selector;

    appValidateForm($(selector), {
        clientid: {
            required: {
                depends: function () {
                    var customerRemoved = $('select#clientid').hasClass('customer-removed');
                    return !customerRemoved;
                }
            }
        },
        date: 'required',
        tag: 'required',
        equipment_category: 'required',
        tags: 'required',
        number: {
            required: true
        }
    });

    $("body").find('input[name="number"]').rules('add', {
        remote: {
            url: admin_url + "inspections/validate_inspection_number",
            type: 'post',
            data: {
                number: function () {
                    return $('input[name="number"]').val();
                },
                isedit: function () {
                    return $('input[name="number"]').data('isedit');
                },
                original_number: function () {
                    return $('input[name="number"]').data('original-number');
                },
                date: function () {
                    return $('body').find('.inspection input[name="date"]').val();
                },
            }
        },
        messages: {
            remote: app.lang.inspection_number_exists,
        }
    });

}


// Get the preview main values
function get_inspection_item_preview_values() {
    var response = {};
    response.description = $('.main textarea[name="description"]').val();
    response.long_description = $('.main textarea[name="long_description"]').val();
    response.qty = $('.main input[name="quantity"]').val();
    return response;
}


// From inspection table mark as
function inspection_mark_as(status_id, inspection_id) {
    var data = {};
    data.status = status_id;
    data.inspectionid = inspection_id;
    $.post(admin_url + 'inspections/update_inspection_status', data).done(function (response) {
        table_inspections.DataTable().ajax.reload(null, false);
    });
}
