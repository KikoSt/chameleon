$(document).ready(function()
{
    var templateId = 5;
    var metaData = getMetaData(templateId);
    var output = '';
    getTemplatePreview(output, metaData);

    $('#filter_value_select_advertiserId').show();

    $('#filter_property_select').change(function() {
        var prop = $("#filter_property_select option:selected").text();
        $('.filter_value_select').hide();
        $('#filter_value_select_' + prop).show();
    });

    $('#btn_add_filter').click(function() {
        var prop  = $("#filter_property_select option:selected").text();
        var value = $("#filter_value_select_" + prop + " option:selected").text();

        // if there are currently no values, we have to add another container
        if($('#' + prop + '_filter_values').length == 0) {
            var newNode = '<div class="active_filter_label">' + prop + '</div>';
            newNode += '<div class="active_filter_values" id="' + prop + '_filter_values">';
            newNode += '</div>';
            newNode += '<br />';
            $('#filterlist').append(newNode);
        }

        if($('.filter[name=' + prop + ']').length == 0) {
            newNode = '<input type="hidden" class="filter" name="' + prop + '" value="" />';
            $('#filterlist').append(newNode);
        }

        var curFilterValue = $('.filter[name=' + prop + ']').val();
        var curFilterValues = [];

        if(curFilterValue !== '') {
            var curFilterValues = curFilterValue.split(';');
        }

        if($.inArray(value, curFilterValues) === -1) {
            curFilterValues.push(value);
        }
        $('.filter[name=' + prop + ']').val(curFilterValues.join(';'));

        $('#' + prop + '_filter_values').html(curFilterValues.join('<br />'));

        // gather current filters

        var filterdata = {};
        filterdata['filters'] = {};
        $('.filter').each(function(index) {
            console.log($(this).attr('name') + ': ' + $(this).val());
            filterdata['filters'][$(this).attr('name')] = $(this).val();
        });
        filterdata['companyId'] = 170;
        filterdata['advertiserId'] = 122;

        $.ajax({
            type: "POST",
            data: filterdata,
            dateType: "json",
            url: "/chameleon/ajax/getFilteredCollection.php"
        }).done(function(response) {
            var response = JSON.parse(response);
            $('#templates_container').html('');
            for(var i=0; i<response.length; i++) {
                var element = response[i];
                var newNode = '<div class="manager_element_body">';
                newNode += '<div class="manager_element_name">' + element.name + '</div>';
                newNode += '<div class="manager_template_menu">';
                newNode += '<span id="" class="fa fa-file-image-o fa-lg" style="margin-top: 3px"></span>';
                newNode += '<a href="?page=editor&templateId=' + element.id + '">';
                newNode += '<span id="" class="fa fa-edit fa-lg" style="margin-top: 3px"></span>';
                newNode += '</a>';

                newNode += '</div>';
                newNode += '<div class="manager_element_preview">';
                newNode += '<img src="' + element.imgpath + '" width="' + element.displayWidth + '" />';
                newNode += '</div>';

                newNode += '<div class="template_info_box">';
                newNode += '<strong>Categories:</strong><br />';
                for(var j=0; j<element.categoryIds.length; j++) {
                    newNode += element.categoryIds[j] + '; ';
                }
                newNode += '</div>';

                newNode += '</div>';


                $('#templates_container').append(newNode);
            }
        }).fail(function(response) {
            console.log('fail');
        });
    });
});
