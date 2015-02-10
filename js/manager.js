$(document).ready(function()
{
    var output = '';

    $('#filter_value_select_advertiserId').show();
    $('#filter_property_select').on('change', handleFilterSelect() );
    $('.btn_add_filter').on('click', handleAddFilter )
    //
    // event handling delegated to container element!
    $('#filterlist').on('click', handleListClick);

    function handleListClick(e) {
        console.log($(e.target).attr('id'));
    }


    function handleAddFilter() {
        var prop  = $("#filter_property_select option:selected").text();
        var value = $("#filter_value_select_" + prop + " option:selected").text();

        // if there are currently no values, we have to add another container
        if($('#' + prop + '_filter_values').length == 0) {
            $('#filterlist').append(createNewFilterDomNode(prop, value));
        }

        // if there is currently no (hidden) value container for this property, create one
        if($('.filter[name=' + prop + ']').length == 0) {
            newNode = '<input type="hidden" class="filter" name="' + prop + '" value="" />';
            $('#filterlist').append(newNode);
        }

        // now the required nodes exist, empty or not
        var curFilterValue = $('.filter[name=' + prop + ']').val();
        var curFilterValues = [];

        if(curFilterValue !== '') {
            var curFilterValues = curFilterValue.split(';');
        }

        // only add value if it currently isn't in the array, preventing duplicates
        if($.inArray(value, curFilterValues) === -1) {
            curFilterValues.push(value);
        }

        // sort array ascending
        curFilterValues.sort();
        $('.filter[name=' + prop + ']').val(curFilterValues.join(';'));

        $('#' + prop + '_filter_values').html(curFilterValues.join('<br />'));

        // gather current filters
        var filterdata = {};
        filterdata['filters'] = {};
        $('.filter').each(function(index) {
            filterdata['filters'][$(this).attr('name')] = $(this).val();
        });
        filterdata['companyId'] = $('.companyId');
        filterdata['advertiserId'] = $('.advertiserId');

        $.ajax({
            type: "POST",
            data: filterdata,
            dateType: "json",
            url: "/ajax/getFilteredCollection.php"
        }).done(function(response) {
            var response = JSON.parse(response);
            $('#templates_container').html('');
            for(var i=0; i<response.length; i++) {
                var element = response[i];
                $('#templates_container').append(createTemplatePreviewBoxNode(element));
            }
        }).fail(function(response) {
            console.log('fail');
        });
    }

    function createTemplatePreviewBoxNode(element) {
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

        return newNode;
    }


    function createNewFilterDomNode(prop, value) {
        var newNode = '<div class="active_filter_label">' + prop + '</div>';
        newNode += '<div class="active_filter_values" id="' + prop + '_filter_values">';
        newNode += '</div>';
        newNode += ' <span id="' + prop + '_' + value + '" class="btn_remove_filter fa fa-minus-square-o fa-sm" style="margin-top: 3px"></span>';
        newNode += '<br />';

        return newNode;
    }



    function handleFilterRemove() {
        console.log($(this).attr('id'));

    }

    function handleFilterSelect() {
        var prop = $("#filter_property_select option:selected").text();
        // hide all
        $('.filter_value_select').hide();
        // show appropriate
        $('#filter_value_select_' + prop).show();
    }
});
