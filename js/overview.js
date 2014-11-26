$(document).ready(function()
{
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
        var id = $(this).attr('id').split('-');
        var templateId = id[1];
        var data = {};
        var category = [];

        if (!$("#availableVategory-"+templateId).length) {
            $('#addCategory-'+templateId+'-'+advertiserId).prop('disabled', true);
        }

        $('#availableCategory-'+templateId).find('option:selected').each(function(i,selected){
            var subscription = {};
            subscription.id = $(selected).val();
            subscription.name = $.trim($(selected).text());
            category.push(subscription);
        });

        data.category = category;
        data.advertiserId = getAdvertiserId();
        data.templateId   = templateId;
        data.companyId    = getCompanyId();

        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: "/chameleon/ajax/addCategory.php"
        }).done(function(){
        }).fail(function(){
            category.forEach(function(singleCategory){
                var item = '<div id="'+singleCategory.id+'" class="row"><p class="text-left overviewTitle categoryItem">'+
                            '<a class="fa fa-trash categoryItem cursor-pointer" title="Remove category"></a>' +
                            singleCategory.name + '</p></div>';
                $('#categoryContainerOverview-'+templateId).append(item);

                var node = '<option value="' + singleCategory.id + '">' + singleCategory.name + '</option>';
                $('#assignedCategory-'+templateId).append(node);
                $('#availableCategory-'+templateId).find("option[value='"+singleCategory.id+"']").remove();
            });
            $(".modal-body form").unblock();
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
        var id = $(this).attr('id').split('-');
        var templateId = id[1];
        var data = {};
        var category = [];

        $('#assignedCategory-'+templateId).find('option:selected').each(function(i,selected){
            var subscription = {};
            subscription.id = $(selected).val();
            subscription.name = $.trim($(selected).text());
            category.push(subscription);
        });

        data.category = category;
        data.advertiserId = getAdvertiserId();
        data.templateId   = templateId;
        data.companyId    = getCompanyId();
        $.ajax({
            type: 'POST',
            data: data,
            dataType: "json",
            url: '/chameleon/ajax/removeCategory.php'
        }).done(function(){
        }).fail(function(){
            category.forEach(function(singleCategory){
                var item = $('#'+singleCategory.id);
                $("#assignedCategory-"+templateId).find("option[value='"+singleCategory.id+"']").remove();
                var node = '<option value="' + singleCategory.id + '">'+singleCategory.name+'</option>';
                $('#availableCategory-'+templateId).append(node);
                $('#assigned-'+singleCategory.id).empty().remove();
                $('#categoryContainerOverview-'+templateId+' #'+singleCategory.id).empty().remove();
            });
            $(".modal-body form").unblock();
        });
    });

    $(".cloneTemplate").click(function(){
        var id = $(this).attr('id').split('-');
        var templateId = id[1];

        $('.cloneTemplate').jBox('Confirm', {
            title: 'Delete template',
            confirmButton: 'Clone',
            cancelButton: 'Cancel',
            attach: $('#cloneTemplate-'+templateId),
            confirm: function() {
                var data = {};

                data.advertiserId = getAdvertiserId();
                data.templateId = templateId;
                data.companyId = getCompanyId();

                $.ajax({
                    type: 'POST',
                    data: data,
                    dataType: "json",
                    url: '/chameleon/ajax/cloneTemplate.php'
                }).done(function(response){
                    var url = window.location.origin + '/chameleon/index.php?page=editor&templateId=' + response +
                            '&companyId=' + companyId +
                            '&advertiserId=' + advertiserId;
                    window.location.replace(url);
                }).fail(function(response){
                //todo add exception handling after API change
                });
            },
            cancel: function() {},
            content: '<b>Warning!</b> Are you sure that you want to clone this template'
        }).open();
    });

    $(".deleteTemplate").click(function(){

        var id = $(this).attr('id').split('-');
        var templateId = id[1];

        $('.deleteTemplate').jBox('Confirm', {
            title: 'Delete template',
            confirmButton: 'Delete',
            cancelButton: 'Cancel',
            attach: $('#deleteTemplate-'+templateId),
            confirm: function() {
                var data = {};

                data.advertiserId = getAdvertiserId();
                data.templateId   = templateId;

                $.ajax({
                    type: 'POST',
                    data: data,
                    dataType: "html",
                    url:  '/chameleon/ajax/deleteTemplate.php'
                }).done(function(response)
                {
                    //todo optimize this after API change
                    if (response.length > 0)
                    {
                        var content = '<p>Oops, something went wrong...' +
                                '</br>This template was not deleted!</p><p></p>' +
                                '<p>Press [ESC] to close this window</p>';

                        createNotice('Alert', content, $(this));
                    }
                    else
                    {
                        $('#template_' + templateId).fadeOut("slow", function ()
                        {
                            $(this).empty();
                        });
                    }
                }).fail(function(response){
                //todo add exception handling after API change
                });
            },
            cancel: function() {},
            content: '<b>Warning!</b> Are you sure that you want to delete this template'
        }).open();
    });

    $('.ajaxPreview').each(function(e){
        var id = $(this).attr('id').split('-');
        var data = {};
        var templateId = id[1];

        $("#creativesCarousel-"+templateId).block({
            message: '<h1><img src="'+window.location.origin+'/chameleon/img/loading.gif"/> Rendering example banners...</h1>',
            css: { border: '3px solid #a00', width: '50%'  }
        });

        data.advertiserId   = getAdvertiserId();
        data.templateId     = templateId;
        data.companyId      = getCompanyId();
        data.numPreviewPics = 10;
        data.auditUserId    = 1;

        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: "/chameleon/ajax/getProductIdByTemplateId.php"
        }).done(function (output)
        {
            if(output.length > 0)
            {
                var count = 1;
                $.each(output, function (key,value)
                {
                    data.productId = value;
                    $.ajax({
                        type: "POST",
                        data: data,
                        dataType: "json",
                        url: "/chameleon/ajax/renderExampleForProductId.php"
                    }).done(function (file)
                    {
                        $('<div id="'+templateId+'_'+count+'" class="item">'+
                            '<img src="' + window.location.origin + '/chameleon/' + file + '" alt="..."' +
                            'style="max-height: 320px;>' +
                            '</div>').appendTo('#previewcarousel-' + templateId);
                        count++;
                        $('#'+templateId+'_1').addClass("active");
                        $("#creativesCarousel-"+templateId).carousel("pause").removeData();
                        $("#creativesCarousel-"+templateId).carousel(0);
                    });
                });
            }
            else
            {
                $('<div class="item">No categories selected. Please select at least one category to render examples...</div>').appendTo('#previewcarousel-' + templateId);
            }
        }).fail(function(){
            $('<div class="item">An error occured during the render process...</div>').appendTo('#previewcarousel-' + templateId);
        });
        $("#creativesCarousel-"+templateId).unblock();
    });

    $('.carousel').carousel({
        interval: 4000
    });

    function createNotice(title, content, attachTo){
        new jBox('Modal',{
            width: 300,
            height: 200,
            attach: attachTo,
            title: title,
            content: content
        }).open()
    }

    function getAdvertiserId(){
        return $('#advertiserId').attr('value');
    }

    function getCompanyId(){
        return $('#companyId').attr('value');
    }
});

