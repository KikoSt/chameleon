$(document).ready(function()
{
    var output = '';

    $('#filter_property_select').on('change', handleFilterSelect);
    $('.btn_add_filter').on('click', handleAddFilter )
    // event handling delegated to container element!
    $('#filterlist').on('click', handleFilterlistClick);

    handleFilterSelect();


    /**
     * handleFilterlistClick
     *
     * Click handler function for the list of currently active filters;
     * mainly used to disable filters that are currently active
     *
     * @param e $e
     * @access public
     * @return void
     */
    function handleFilterlistClick(e) {
        var params = $(e.target).parent().attr('id').split('_');
        var property = params[0];
        var value = params[1];
        filterValues = removeFilterValue(property, value);
    }



    /**
     * removeFilterValue
     *
     * @param property $property
     * @param value $value
     * @access public
     * @return void
     */
    function removeFilterValue(property, value) {
        var filterValues = getFilterValues(property);

        filterValues = $.grep(filterValues, function(curValue) {
            return curValue != value;
        });

        if(filterValues.length < 1) {
            removeFilterProperty(property);
        }

        // delete ALL content for this property
        $('#' + property + '_filter_values').html('');

        // and recreate it with the new values
        filterlist = '';
        $.each(filterValues, function(index, value) {

            // add the container for label AND remove button
            filterlist += '<div class="filter_entry" id="' + property + '_' + value + '">';

            // add the label
            filterlist += '<span>' + value + '</span>';

            // add the remove button
            filterlist += ' <span id="' + property + '_' + value + '" class="btn_remove_filter fa fa-minus-square-o fa-sm" style="margin-top: 3px"></span>';

            // close the container div
            filterlist += '</div>';
        });
        $('#' + property + '_filter_values').html(filterlist);
        $('.filter[name=' + property + ']').val(filterValues.join(';'));

        if(filterValues.length < 1) {
            $('.active_filter_label[name=' + property + ']').remove();
        }

        var filterdata = getFilterdata();
        reloadFilteredCollection(filterdata);

        return filterValues;
    }


    /**
     * removeFilterProperty
     *
     * @param property $property
     * @access public
     * @return void
     */
    function removeFilterProperty(property) {
        $('.filter[name=' + property + ']').remove();
    }


    /**
     * handleFilterSelect
     *
     * @access public
     * @return void
     */
    function handleFilterSelect() {
        var property = $("#filter_property_select option:selected").text();
        // hide all
        $('.filter_value_select').hide();
        // show appropertyriate
        $('#filter_value_select_' + property).show();
    }
    /**
     * getFilterValues
     *
     * extracts the filter values for a given property from the DOM
     * tree, returning either an empty array or an array containing
     * all filter values for a given property
     *
     * @param property $property
     * @access public
     * @return void
     */
    function getFilterValues(property) {
        // now the required nodes exist, empty or not
        var curFilterValue = $('.filter[name=' + property + ']').val();
        var curFilterValues;

        if(curFilterValue !== '') {
            curFilterValues = curFilterValue.split(';');
        } else {
            curFilterValues = [];
        }

        return curFilterValues;
    }

    /**
     * addFilterValue
     *
     * add the given value to the current filter list;
     * if the property isn't in the list for filtering,
     * it is added as an array key with value as value,
     * if the property already exists, the value is added
     *
     * returns an array with all filter values for the
     * given property
     * @param property $property
     * @param value $value
     * @access public
     * @return void
     */
    function addFilterValue(property, value) {
        var curFilterValues = getFilterValues(property);

        // only add value if it currently isn't in the array, preventing duplicates
        if($.inArray(value, curFilterValues) === -1) {
            curFilterValues.push(value);
        }
        return curFilterValues;
    }

    /**
     * handleAddFilter
     *
     * @access public
     * @return void
     */
    function handleAddFilter() {
        var property  = $("#filter_property_select option:selected").text();
        var value     = $("#filter_value_select_" + property + " option:selected").text();

        /**
         *
         * (1)       <div class="active_filter_label" name="[property_name]">
         *  |           [property_name]
         *  |        </div>
         * (1)       <div class="active_filter_values" id="[property_name]_filter_values">
         *  |           // for each value
         *  |   (2)     <div class="filter_entry" id="[property_name]_[value]">
         *  |    |          <span>[value]</span>
         *  |    |          <span class="btn_remove_filter [FONT_AWESOME MINUS]></span>
         *  |   (2)     </div>
         * (1)       </div>
         *
         * (3)       <input type="hidden" class="filter" name="property" value="[value;value]" />
         *
         **/

        // (1) if the property has no ACTIVE FILTERs, add a container for it
        // if there are currently no VALUES, we have to add another container
        if($('#' + property + '_filter_values').length == 0) {
            $('#filterlist').append(createNewFilterDomNode(property, value));
        }

        // (3) if there is currently no (hidden) value container for this property, create one
        if($('.filter[name=' + property + ']').length == 0) {
            newNode = '<input type="hidden" class="filter" name="' + property + '" value="' + value + '" />';
            $('#filterlist').append(newNode);
        }

        // sort array ascending
        curFilterValues = addFilterValue(property, value).sort();
        // $('.filter[name=' + property + ']').val(curFilterValues.join(';'));

        // $('#' + property + '_filter_values').html(curFilterValues.join('<br />'));

        var filterdata = getFilterdata();
        reloadFilteredCollection(filterdata);
    }

    function getFilterdata() {
        // gather current filters
        var filterdata = {};
        filterdata['filters'] = {};

        $('.filter').each(function(index) {
            filterdata['filters'][$(this).attr('name')] = $(this).val();
        });

        filterdata['companyId']    = $('#companyId').attr('value');
        filterdata['advertiserId'] = $('#advertiserId').attr('value');

        return filterdata;
    }

    function reloadFilteredCollection(filterdata) {
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
            delete(response);
        }).fail(function(response) {
        });
    }

    /**
     * createNewFilterDomNode
     *
     * @param property $property
     * @param value $value
     * @access public
     * @return void
     */
    function createNewFilterDomNode(property, value) {
        var newNode = '<div class="active_filter_label" name="' + property + '">' + property + '</div>';
        newNode += '<div class="active_filter_values" id="' + property + '_filter_values">';
        newNode += '<div class="filter_entry" id="' + property + '_' + value + '">';
        newNode += '<span>' + value + '</span>';
        newNode += ' <span class="btn_remove_filter fa fa-minus-square-o fa-sm" style="margin-top: 3px"></span>';
        newNode += '</div>';
        newNode += '</div>';

        return newNode;
    }


    /**
     * createTemplatePreviewBoxNode
     *
     * @param element $element
     * @access public
     * @return void
     */
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
});
