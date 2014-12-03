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
 * Get the rendered examples
 *
 * @param output
 * @param metaData
 */
function getRenderedGif(output, metaData){
    var count = 1;

    $.each(output, function (key,value)
    {
        metaData.productId = value;

        $.ajax({
            type: "POST",
            data: metaData,
            dataType: "json",
            url: "/chameleon/ajax/renderExampleForProductId.php"
        }).done(function (file){
            $('<div id="'+ metaData.templateId+'_'+count+'" class="item">'+
            '<img src="' + window.location.origin + '/chameleon/' + file + '" alt="..."' +
            'style="max-height: 320px;">' +
            '</div>').appendTo('#previewcarousel-' + metaData.templateId);

            count++;

            $('#'+metaData.templateId+'_1').addClass("active");
            $("#creativesCarousel-"+metaData.templateId).carousel("pause").removeData();
            $("#creativesCarousel-"+metaData.templateId).carousel(0);
        });
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


