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
        project_id: 'required',
        tag: 'required',
        equipment_category: 'required',
        tags: 'required',
        assigned: 'required',
        inspection_members: 'required',
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

function validateTahunPembuatan(){

  var z = document.getElementById("tahun_pembuatan").value;

  if(!/^[0-9]+$/.test(z)){
    alert("Please only enter numeric characters only for tahun pembuatan! (Allowed input:0-9)")
  }

}

function validateNumber(){

  var z = document.forms["myForm"]["num"].value;

  if(!/^[0-9]+$/.test(z)){
    alert("Please only enter numeric characters only for your Age! (Allowed input:0-9)")
  }

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


$("body").on('change', '.f_project_id #project_id', function () {
    var val = $(this).val();
    var scheduleAjax = $('select[name="schedule_id"]');
    var clonedschedulesAjaxSearchSelect = scheduleAjax.html('').clone();
    var schedulesWrapper = $('.schedules-wrapper');
    scheduleAjax.selectpicker('destroy').remove();
    scheduleAjax = clonedschedulesAjaxSearchSelect;
    $('#schedule_ajax_search_wrapper').append(clonedschedulesAjaxSearchSelect);
    init_ajax_schedule_search_by_project_id();
});

// Ajax schedule search but only for specific project
function init_ajax_schedule_search_by_project_id(selector) {
    selector = typeof (selector) == 'undefined' ? '#schedule_id.ajax-search' : selector;
    init_schedule_search('schedule', selector, {
        project_id: function () {
            return $('#project_id').val();
        }
    });
}


function init_schedule_search(type, selector, server_data, url) {
    var ajaxSelector = $('body').find(selector);
    var e = document.getElementById("project_id");

      //var as = document.forms[0].project_id.value;
      var pid = e.options[e.selectedIndex].value;
      //console.log(pid);

    if (ajaxSelector.length) {
        var options = {
            ajax: {
                url: (typeof (url) == 'undefined' ? admin_url + 'schedules/schedules_by_project_id/'+pid : url),
                data: function () {
                    var data = {};
                    data.type = type;
                    data.rel_id = '';
                    data.q = '{{{q}}}';
                    if (typeof (server_data) != 'undefined') {
                        jQuery.extend(data, server_data);
                    }
                    return data;
                }
            },
            locale: {
                emptyTitle: app.lang.search_ajax_empty,
                statusInitialized: app.lang.search_ajax_initialized,
                statusSearching: app.lang.search_ajax_searching,
                statusNoResults: app.lang.not_results_found,
                searchPlaceholder: app.lang.search_ajax_placeholder,
                currentlySelected: app.lang.currently_selected
            },
            requestDelay: 500,
            cache: false,
            preprocessData: function (processData) {
                var bs_data = [];
                var len = processData.length;
                for (var i = 0; i < len; i++) {
                    var tmp_data = {
                        'value': processData[i].id,
                        'text': processData[i].name,
                    };
                    if (processData[i].subtext) {
                        tmp_data.data = {
                            subtext: processData[i].subtext
                        };
                    }
                    bs_data.push(tmp_data);
                }
                return bs_data;
            },
            preserveSelectedPosition: 'after',
            preserveSelected: true
        };
        if (ajaxSelector.data('empty-title')) {
            options.locale.emptyTitle = ajaxSelector.data('empty-title');
        }
        ajaxSelector.selectpicker().ajaxSelectPicker(options);
    }
}


function inspection_add_inspection_item(inspection_id, project_id, task_id) {
    var data = {};
    data.inspection_id = inspection_id;
    data.project_id = project_id;
    data.task_id = task_id;
    $.post(admin_url + 'inspections/add_inspection_item', data).done(function (response) {
        reload_inspections_tables();
    });
}


// From inspection table mark as
function inspection_remove_inspection_item(inspection_id, task_id) {
    var data = {};
    data.inspection_id = inspection_id;
    data.task_id = task_id;
    $.post(admin_url + 'inspections/remove_inspection_item', data).done(function (response) {
        reload_inspections_tables();
    });
}


function reload_inspections_tables() {
    var av_inspections_tables = ['.table-inspections', '.table-rel-inspections', '.table-inspection-items', '.table-inspection-items-submitted','.table-inspection-related'];
    //var av_inspections_tables = ['.inspection-items-proposed'];
    $.each(av_inspections_tables, function (i, selector) {
        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().ajax.reload(null, false);
        }
    });
}


// From inspection table mark as
function inspection_update_inspection_data(values) {
    var data = {};
    data.rel_id = values.rel_id;
    data.jenis_pesawat = values.jenis_pesawat;
    data.task_id = values.task_id;
    data.field = values.field;
    data.text = values.text;
    $.post(admin_url + 'inspections/update_inspection_data', data).done(function (response) {
        reload_inspections_tables();
    });
}


// From inspection table mark as
function inspection_item_pengujian_operasional(pengujian, inspection_id, task_id, ) {
    var data = {};
    data.inspection_id = inspection_id;
    data.task_id = task_id;
    data.pengujian = pengujian;
    var pengujian_data = $("input[name='pengujian_data']").val();
    data.pengujian_data = pengujian_data;
    var check = ($(this).is(':checked')) ? '1' : '0';


    data.check = check;
      //console.log(data);
    $.post(admin_url + 'inspections/change_inspection_status', data).done(function (response) {
        reload_inspections_tables();
    });
}


// Bootstrap switch active or inactive global function
/*
$("body").on('change', '.slider input', function (event, state, pengujian) {
    var switch_url = $(this).data('switch-url');
    var pengujian = $(this).data('pengujian');
    if (!switch_url) {
        return;
    }
    switch_field(this);
});
*/
/*
$(".pengujian_operasional").on('change', function(pengujian){
    var data = {};

    //data.inspection_id = inspection_id;
    //data.task_id = task_id;
    data.pengujian = $(this).data('pengujian');

      console.log(data);

    $.post(admin_url + 'inspections/inspection_item_pengujian_operasional', data).done(function (response) {
        reload_inspections_tables();
    });

});
*/

/*

// From inspection table mark as
function inspection_item_pengujian_operasional(inspection_id, task_id, pengujian) {
    var data = {};
    data.inspection_id = inspection_id;
    data.task_id = task_id;
    data.pengujian = pengujian;
    var pengujian_data = $("input[name='pengujian_data']").val();
    data.pengujian_data = pengujian_data;
    var check = ($(this).is(':checked')) ? '1' : '0';


    data.check = check;
    $.post(admin_url + 'inspections/change_inspection_status', data).done(function (response) {
        reload_inspections_tables();
    });
}

*/


function inspection_item_pengujian_data(param, jenis_pesawat, pengujian, rel_id, task_id) {
  var data={}
  data.value = param.value;
  data.jenis_pesawat = jenis_pesawat;
  data.pengujian = pengujian;
  data.rel_id = rel_id;
  data.task_id = task_id;
  //console.log(data);

  $.post(admin_url + 'inspections/item_pengujian_data', data).done(function (response) {
      reload_inspections_tables();
  });
}

function reloadInspectionsAttachments()
{
    $("#inspection-documentations").load(location.href+" #inspection-documentations>*","");
}

function reload_inspections_attachments() {
    var inspection_documentations = ['#inspection-documentations'];
    //var av_inspections_tables = ['.inspection-items-proposed'];
    $.each(inspection_documentations, function (i, selector) {

            $(selector).reload(null, false);

    });
}


// Removes inspection single attachment
function remove_inspection_attachment(link, id, task_id) {
    if (confirm_delete()) {
        requestGetJSON('inspections/remove_inspection_attachment/' + id +task_id).done(function (response) {
            if (response.success === true || response.success == 'true') {
                $('[data-inspection-attachment-id="' + id + '"]').remove();
            }
            _inspection_attachments_more_and_less_checks();
            if (response.comment_removed) {
                $('#comment_' + response.comment_removed).remove();
            }
            reloadInspectionsAttachments();
        });
    }
}
