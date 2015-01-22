/**
 * Provide the meta data
 *
 * @returns object List of meta data values
 */
function getMetaData(templateId, categoryId){
    categoryId = (typeof categoryId === "undefined") ? null : categoryId;

    var metaData = {};

    metaData.templateId   = templateId;
    metaData.templateName = $('#name-'+templateId).attr('title');
    metaData.categoryId   = categoryId;
    metaData.advertiserId = parseInt($('#advertiserId').attr('value'));
    metaData.companyId    = parseInt($('#companyId').attr('value'));

    return metaData;
};


/**
 * getTemplatePreview
 *
 * render generic preview for a given template; the ajax script called here will do this
 * when called without passing a productId
 *
 * @param output $output
 * @param metaData $metaData
 * @access public
 * @return void
 */
function getTemplatePreview(output, metaData) {
    $.ajax({
        type: "POST",
        data: metaData,
        dataType: "json",
        url: "/chameleon/ajax/renderPreview.php"
    }).done(function (file){
        console.log('Done!');
    });
}

/**
 * Get the rendered examples
 *
 * @param output
 * @param metaData
 */
function getRenderedGif(output, metaData){
    var count = 1;

    metaData.productId = output.pop();

    $.ajax({
        type: "POST",
        data: metaData,
        dataType: "json",
        url: "/chameleon/ajax/renderPreview.php"
    }).done(function (file){
        $('.active').removeClass('active');
        var previewNode = '<div id="' + metaData.templateId + '_' + count + '" class="item active">';
        previewNode    += '<img src="'  + window.location.origin + '/chameleon/' + file + '" alt="..."';
        previewNode    += 'style="max-height: 320px;">';
        previewNode    += '</div>';
        $(previewNode).appendTo('#previewcarousel-' + metaData.templateId);

        count++;

        $('#'+metaData.templateId+'_1').addClass("active");
        $("#creativesCarousel-"+metaData.templateId).carousel("pause").removeData();
        $("#creativesCarousel-"+metaData.templateId).carousel(0);

        if(output.length > 0) getRenderedGif(output, metaData);
    });
}

/**
 * Get the selected categories
 *
 * @param metaData
 * @param section String available, assigned
 */
function addSelectedCategories(metaData, section) {
    var category = [];

    $('#'+section+'Category-'+metaData.templateId).find('option:selected').each(function(i,selected){
        var subscription = {};
        subscription.id = $(selected).val();
        subscription.name = $.trim($(selected).text());
        category.push(subscription);
    });

    metaData.categoryId = category;

    return metaData;
}


