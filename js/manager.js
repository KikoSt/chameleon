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
            $('.filter').last().append(newNode);
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

        var filterdata = {'filter' : 'lalalala'};

        $.ajax({
            type: "POST",
            data: filterdata,
            dateType: "json",
            url: "/chameleon/ajax/getFilteredCollection.php"
        }).done(function(response) {
            console.log('done');
        }).fail(function(response) {
            console.log('fail');
        });
    });
});
