$(document).ready(function()
{
    $('.carousel').carousel({
        interval: 4000
    });

    $('#addCategory').click(function(e) {
        var selectedOpts = $('#availableCategory option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }

        $('#assignedCategory').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });

    $('#removeCategory').click(function(e) {
        var selectedOpts = $('#assignedCategory option:selected');
        if (selectedOpts.length == 0) {
            alert("Nothing to move.");
            e.preventDefault();
        }

        $('#availableCategory').append($(selectedOpts).clone());
        $(selectedOpts).remove();
        e.preventDefault();
    });

    $('.addCategoryOverview').click(function(e) {
        $(".modal-body form").block({
            message: '<h1>Assigning categories...</h1>',
            css: { border: '3px solid #a00' }
        });
        e.preventDefault();
        var metaData = getMetaData($(this));

        metaData.type = 'available';

        setSelectedCategories(metaData);

        if (!$("#availableVategory-"+metaData.templateId).length) {
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
        }).fail(function(){

        });
    });

    $('.removeCategoryShortcut').click(function(){
        createNotification("test", "toller text");
        var id = $(this).closest('div').attr('id').split('-');
        var categoryId = id[1];
        var templateId = id[2];
        var data = {};

        data.categoryId   = categoryId;
        data.advertiserId = $('#advertiserId').attr('value');
        data.templateId   = templateId;
        data.companyId    = $('#companyId').attr('value');

        $.ajax({
            type: 'POST',
            data: data,
            dataType: "json",
            url: '/chameleon/ajax/removeCategory.php'
        }).fail(function ()
        {
            $('#assigned-' + categoryId + '-' + templateId).empty().remove();
            $("#assignedCategory-"+templateId).find("option[value='"+categoryId+"']").remove();
        });
    });

    $('.removeCategoryOverview').click(function(e) {
        $(".modal-body form").block({
            message: '<h1>Removing categories</h1>',
            css: { border: '3px solid #a00' }
        });
        e.preventDefault();
        var metaData = getMetaData($(this));

        metaData.type = 'assigned';

        setSelectedCategories(metaData);

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

        }).fail(function(){
            console.log('fail');
        });
    });

    /**
     *
     */
    $(".cloneTemplate").click(function(){
        var metaData = getMetaData($(this));

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
                    var url = window.location.origin + '/chameleon/index.php?page=editor&templateId=' + cloneId +
                            '&companyId=' + metaData.companyId +
                            '&advertiserId=' + metaData.advertiserId;
                    window.location.replace(url);
                }).fail(function(response){
                    confirmBox.destroy();
                    createErrorNotification('Alert', response);
                });
            },
            cancel: function() {},
            content: '<b>Warning!</b> Are you sure that you want to clone this template?'
        });
        confirmBox.open();
    });

    /**
     *
     */
    $(".deleteTemplate").click(function(){
        var metaData = getMetaData($(this));

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
                        createErrorNotification('<i class="fa fa-exclamation-triangle"></i> An error occurred', response);
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
    });

    /**
     *
     */
    $('.ajaxPreview').each(function(){
        createExamples($(this));
    });

    /**
     *
     * @param templateId
     */
    function createExamples(jQueryObject){
        var metaData = getMetaData(jQueryObject);

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
                renderGif(output, metaData);
            }
            else
            {
                $('<div id="emptyItem-'+metaData.templateId+'" class="item">No categories selected. Please select at least one category to render examples...</div>').appendTo('#previewcarousel-' + metaData.templateId);
                $('#emptyItem-'+metaData.templateId).addClass("active");
            }
        }).fail(function(){});
    }

    /**
     *
     * @param output
     * @param data
     */
    function renderGif(output, data){
        var count = 1;
        $.each(output, function (key,value)
        {
            data.productId = value;

            $.ajax({
                type: "POST",
                data: data,
                dataType: "json",
                url: "/chameleon/ajax/renderExampleForProductId.php"
            }).done(function (file){
                $('<div id="'+data.templateId+'_'+count+'" class="item">'+
                '<img src="' + window.location.origin + '/chameleon/' + file + '" alt="..."' +
                'style="max-height: 320px;">' +
                '</div>').appendTo('#previewcarousel-' + data.templateId);

                count++;

                $('#'+data.templateId+'_1').addClass("active");
                $("#creativesCarousel-"+data.templateId).carousel("pause").removeData();
                $("#creativesCarousel-"+data.templateId).carousel(0);
            });
        });
    }

    /**
     *
     * @param title
     * @param content
     * @param width
     * @param height
     */
    function createErrorNotification(title, content, width, height){

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
    }

    /**
     *
     * @param object
     * @returns {{}}
     */
    function getMetaData(jQueryObject){
        var metaData = {};
        var id = jQueryObject.attr('id').split('-');

        metaData.templateId = parseInt(id[1]);
        metaData.templateName = $('#name-'+id[1]).attr('title');
        metaData.advertiserId = parseInt($('#advertiserId').attr('value'));
        metaData.companyId = parseInt($('#companyId').attr('value'));

        return metaData;
    }

    function setSelectedCategories(metaData)
    {
        var category = [];

        $('#'+metaData.type+'Category-'+metaData.templateId).find('option:selected').each(function(i,selected){
            var subscription = {};
            subscription.id = $(selected).val();
            subscription.name = $.trim($(selected).text());
            category.push(subscription);
        });

        metaData.categoryId = category;
    }
});