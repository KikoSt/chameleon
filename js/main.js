/**
 * Created by thomas on 01.12.14.
 */

var Constructor = function(name){
    this.name = name;
};

Constructor.prototype.createErrorNotification = function(title, content, width, height){

    width = (typeof width === "undefined") ? 500 : width;
    height = (typeof height === "undefined") ? 250 : height;

    content += '<p class="jBox-custom-padding-line-2x">If you need further assistence, contact us via</p>';
    content += '<p class="jBox-custom-padding-text"><a>helpdesk@mediadecision.com</a>.</p>';
    content += '<p class="jBox-custom-padding-line-1x">Press [ESC] to close this window or click anywhere.</p>';

    new jBox('Modal',{width: width,
        height: height,
        closeOnClick: true,
        animation: 'tada',
        title: title,
        content: content
    }).open()
};

/**
 *
 * @param metaData
 * @param section String available, assigned
 */
Constructor.prototype.setSelectedCategories = function(metaData, section) {
    var category = [];

    $('#'+section+'Category-'+metaData.templateId).find('option:selected').each(function(i,selected){
        var subscription = {};
        subscription.id = $(selected).val();
        subscription.name = $.trim($(selected).text());
        category.push(subscription);
    });

    metaData.categoryId = category;
};

Constructor.prototype.addCategoryModal = function(){
    var metaData = this.getMetaData();

    $(".modal-body form").block({
        message: '<h1>Assigning categories...</h1>',
        css: { border: '3px solid #a00' }
    });

    this.setSelectedCategories(metaData, 'available');

    if (!$("#availableCategory-"+metaData.templateId).length) {
        $('#addCategory-'+metaData.templateId+'-'+metaData.advertiserId).prop('disabled', true);
    }

    $.ajax({
        type: "POST",
        data: metaData,
        dataType: "json",
        url: "/chameleon/ajax/addCategory.php"
    }).done(function(){

        metaData.categoryId.forEach(function(singleCategory){
            //create item for right select
            var item = '<div id="'+singleCategory.id+'" class="row"><p class="text-left overviewTitle categoryItem">'+
                    '<a class="fa fa-trash categoryItem cursor-pointer" title="Remove category"></a>' +
                    singleCategory.name + '</p></div>';
            //append item
            $('#categoryContainerOverview-'+templateId).append(item);
            //create node for overview
            var node = '<option value="' + singleCategory.id + '">' + singleCategory.name + '</option>';
            //append node
            $('#assignedCategory-'+templateId).append(node);
            //remove item from left select
            $('#availableCategory-'+templateId).find("option[value='"+singleCategory.id+"']").remove();
        });
        $(".modal-body form").unblock();
    }).fail(function(response){
        this.createErrorNotification('Alert', response);
    });
};

Constructor.prototype.removeCategoryModal = function() {
    var metaData = this.getMetaData();

    $(".modal-body form").block({
        message: '<h1>Removing categories</h1>',
        css: { border: '3px solid #a00' }
    });

    this.setSelectedCategories(metaData, 'assigned');

    $.ajax({
        type: 'POST',
        data: metaData,
        dataType: "json",
        url: '/chameleon/ajax/removeCategory.php'
    }).done(function(){
        metaData.categoryId.forEach(function(singleCategory){
            //remove category from right select
            $("#assignedCategory-"+metaData.templateId).find("option[value='"+singleCategory.id+"']").remove();

            //create node for left select
            var node = '<option value="' + singleCategory.id + '">'+singleCategory.name+'</option>';

            //append node at left select
            $('#availableCategory-'+metaData.templateId).append(node);

            //remove category from overview
            $('#assigned-'+singleCategory.id+'-'+metaData.templateId).empty().remove();
            $('#categoryContainerOverview-'+metaData.templateId+' #'+singleCategory.id).empty().remove();
        });
        $(".modal-body form").unblock();
    }).fail(function(response){
        this.createErrorNotification('An error occurred', response);
    });
};

/**
 * Remove a category via the shortcut at the overview (trash can icon)
 */
Constructor.prototype.removeCategoryShortcut = function(){
    var metaData = this.getMetaData();

    $.ajax({
        type: 'POST',
        data: metaData,
        dataType: "json",
        url: '/chameleon/ajax/removeCategory.php'
    }).done(function(){
        $('#assigned-' + metaData.categoryId + '-' +metaData.templateId).empty().remove();
        $("#assignedCategory-"+metaData.templateId).find("option[value='"+metaData.categoryId+"']").remove();
    }).fail(function(response){
        Constructor.prototype.createErrorNotification('An error occurred...', response);
    });
};

/**
 * Delete a template
 */
Constructor.prototype.deleteTemplate = function(){
    var metaData = this.getMetaData();

    var confirmBox = new jBox('Confirm', {
        title: 'Delete template',
        confirmButton: 'Delete',
        cancelButton: 'Cancel',
        closeOnClick: false,
        confirm: function() {
            $.ajax({
                type: 'POST',
                data: metaData,
                dataType: "json",
                url:  '/chameleon/ajax/deleteTemplate.php'
            }).done(function(response){

                //todo change if API exception handling is changed
                if(response.length > 0)
                {
                    confirmBox.destroy();
                    this.createErrorNotification('An error occurred', response);
                }
                else
                {
                    $('#template_' + metaData.templateId).fadeOut("slow", function ()
                    {
                        $(this).empty();
                    });
                }
            }).fail(function(response){});
        },
        cancel: function() {},
        content: '<p>Are you sure that you want to delete template</p><p class="jBox-custom-padding-text">"'+metaData.templateName+'"?</p>'
    });
    confirmBox.open();
};

/**
 * clone a template and redirect to the editor
 */
Constructor.prototype.cloneTemplate = function(){
    var metaData = this.getMetaData();

    var confirmBox = new jBox('Confirm', {
        title: 'Delete template',
        confirmButton: 'Clone',
        cancelButton: 'Cancel',
        closeOnClick: false,
        confirm: function() {
            $.ajax({
                type: 'POST',
                data: metaData,
                dataType: "json",
                url: '/chameleon/ajax/cloneTemplate.php'
            }).done(function(cloneId){
                if(cloneId !== "undefined" && cloneId !== 0){
                    var url = window.location.origin + '/chameleon/index.php?page=editor&templateId=' + cloneId +
                            '&companyId=' + metaData.companyId +
                            '&advertiserId=' + metaData.advertiserId;
                    window.location.replace(url);
                }
            }).fail(function(response){
                confirmBox.destroy();
                this.createErrorNotification('An error occurred', response);
            });
        },
        cancel: function() {},
        content: '<b>Warning!</b> Are you sure that you want to clone this template?'
    });
    confirmBox.open();
};

/**
 * Create examples for each template
 */
Constructor.prototype.createExamples = function(){
    var metaData = this.getMetaData();

    metaData.numPreviewPics = 10;
    metaData.auditUserId    = 1;

    $.ajax({
        type: "POST",
        data: metaData,
        dataType: "json",
        url: "/chameleon/ajax/getProductIdByTemplateId.php"
    }).done(function (output)
    {
        if(output.length > 0)
        {
            getRenderedGif(output, metaData);
        }
        else
        {
            $('<div id="emptyItem-'+metaData.templateId+'" class="item">No categories selected. ' +
            'Please select at least one category to render examples...</div>').appendTo('#previewcarousel-' + metaData.templateId);
            $('#emptyItem-'+metaData.templateId).addClass("active");
        }
    }).fail(function(response){

    });
};

Constructor.prototype.moveCategoryModal = function(target){
    var selectedOpts = $('#availableCategory option:selected');
    if (selectedOpts.length == 0) {
        overview.createErrorNotification('Alert', 'Nothing to move.');
        e.preventDefault();
    }

    $('#'+target+'Category').append($(selectedOpts).clone());
    $(selectedOpts).remove();
    e.preventDefault();
};

/**
 * Provide the meta data
 *
 * @returns object List of meta data values
 */
Constructor.prototype.getMetaData = function(){
    var metaData = {};
    var clickTarget = this.clickTarget;
    var id = clickTarget.attr('id').split('-');

    if(typeof id[2] !== 'undefined')
    {
        metaData.categoryId = parseInt(id[2]);
    }

    metaData.templateId = parseInt(id[1]);
    metaData.templateName = $('#name-'+id[1]).attr('title');
    metaData.advertiserId = parseInt($('#advertiserId').attr('value'));
    metaData.companyId = parseInt($('#companyId').attr('value'));

    return metaData;
};

/**
 * Set the click target
 *
 * @param clickTarget
 */
Constructor.prototype.setClickTarget = function(clickTarget){
    this.clickTarget = clickTarget;
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


