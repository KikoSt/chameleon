/**
 * Created by thomas on 01.12.14.
 */

var Cmeo = function(name){
    this.name = name;
};

/**
 * Create a error notification
 *
 * @param {String}  [title]
 * @param {String}  [content]
 * @param {Integer} [width]     optional
 * @param {Integer} [height]    optional
 */
Cmeo.prototype.createErrorNotification = function(title, content, width, height){

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
 * Add one or more categories to the template via the modal view
 *
 * @param {Integer} [templateId]
 */
Cmeo.prototype.addCategoryByModalView = function(templateId){
    $(".modal-body form").block({
        message: '<h1>Assigning categories...</h1>',
        css: { border: '3px solid #a00' }
    });

    var metaData = getMetaData(templateId);
    metaData = addSelectedCategories(metaData, 'available');

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
        Cmeo.prototype.createErrorNotification('Alert', response);
    });
};

/**
 * Remove one or more categories from the template via the modal view
 *
 * @param {Integer} [templateId]
 */
Cmeo.prototype.removeCategoryByModalView = function(templateId) {
    $(".modal-body form").block({
        message: '<h1>Removing categories</h1>',
        css: { border: '3px solid #a00' }
    });

    var metaData = getMetaData(templateId);
    metaData = addSelectedCategories(metaData, 'assigned');

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
        Cmeo.prototype.createErrorNotification('An error occurred', response);
    });
};

/**
 * Remove a category via the shortcut at the overview (trash can icon)
 *
 * @param {Integer} [templateId]
 */
Cmeo.prototype.removeCategoryByShortcut = function(templateId, categoryId){
    var metaData = getMetaData(templateId,categoryId);
    $.ajax({
        type: 'POST',
        data: metaData,
        dataType: "json",
        url: '/chameleon/ajax/removeCategory.php'
    }).done(function(){
        $('#assigned-' + metaData.categoryId + '-' +metaData.templateId).empty().remove();
        $("#assignedCategory-"+metaData.templateId).find("option[value='"+metaData.categoryId+"']").remove();
    }).fail(function(response){
        Cmeo.prototype.createErrorNotification('An error occurred...', response);
    });
};

/**
 * Delete a template
 *
 * @param {Integer} [templateId]
 */
Cmeo.prototype.deleteTemplate = function(templateId){
    var metaData = getMetaData(templateId);

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
 *
 * @param {Integer} [templateId]
 */
Cmeo.prototype.cloneTemplate = function(templateId){
    var confirmBox = new jBox('Confirm', {
        title: 'Delete template',
        confirmButton: 'Clone',
        cancelButton: 'Cancel',
        closeOnClick: false,
        confirm: function() {
            var metaData = getMetaData(templateId);
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
Cmeo.prototype.createExamples = function(templateId){
    var metaData = getMetaData(templateId);

    //todo till we get this via user defined value or something like that
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

/**
 * Move a category to the set target (just UI )
 *
 * @param target
 */
Cmeo.prototype.moveCategoryModal = function(target){
    var selectedOpts = $('#availableCategory option:selected');
    if (selectedOpts.length == 0) {
        overview.createErrorNotification('Alert', 'Nothing to move.');
        e.preventDefault();
    }

    $('#'+target+'Category').append($(selectedOpts).clone());
    $(selectedOpts).remove();
    e.preventDefault();
};

