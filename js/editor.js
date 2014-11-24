$(document).ready(function() {

    $.ajax({
        url: 'ajax/getSizeLimits.php'
    }).done(function(data) {
        window.sizeLimits = $.parseJSON(data);
        window.swfSizeLimits = window.sizeLimits.swf;
        window.gifSizeLimits = window.sizeLimits.gif;
    });

    var btn;
    var somethingChanged = false;
    var category = {};

    $('#alert-custom').hide();

    $(window).keydown(function(e){
        if(e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });

    $('.subnav').on('click', function(e) {
        var id = $(this).attr('id');
        if(id.substr(0, 5) != 'group') {
            $('.component').hide();
            $('#panel_' + id).show();
            $('#grouppanel_' + $('#panel_' + id).attr('data-groupid')).show();
            $('#' + id + '--primary').css("background-color", $('#primary-color').val());
            $('#' + id + '--secondary').css("background-color", $('#secondary-color').val());
            $('#' + $('#panel_' + id).attr('data-groupid') + '--fgprimary').css("background-color", $('#primary-color').val());
            $('#' + $('#panel_' + id).attr('data-groupid') + '--fgsecondary').css("background-color", $('#secondary-color').val());
            $('#' + $('#panel_' + id).attr('data-groupid') + '--bgprimary').css("background-color", $('#primary-color').val());
            $('#' + $('#panel_' + id).attr('data-groupid') + '--bgsecondary').css("background-color", $('#secondary-color').val());
        } else {
            $('.component').hide();
            id = id.substr(6, 10);
            $('#grouppanel_' + id).show();
            $('#' + id + '--primary').css("background-color", $('#primary-color').val());
            $('#' + id + '--secondary').css("background-color", $('#secondary-color').val());
            $('#' + id + '--fgprimary').css("background-color", $('#primary-color').val());
            $('#' + id + '--fgsecondary').css("background-color", $('#secondary-color').val());
            $('#' + id + '--bgprimary').css("background-color", $('#primary-color').val());
            $('#' + id + '--bgsecondary').css("background-color", $('#secondary-color').val());
        }
    });

    $('.glyphicon-remove-circle').on('click', function(e) {
        var id = $(this).attr('id').replace('_close', '');
        $('#panel_' + id).hide();
    });

    $('#editCategoriesEditor').on('click', function(e) {
        $('#panel_globalsCategory').show();
    });

    $('.glyphicon-resize-small').on('click', function(e) {
        $(this).hide();
    });


    function updateEditGroup(groupId, param, value) {
        console.log('Updating group ' + groupId + ', param ' + param + ' = ' + value);
    }


    /* ***********************************
     * FILE UPLOAD
     * *********************************** */
    $('.kv-fileinput-upload').on('click', function(e){
        e.preventDefault();

        var formData = new FormData();
        var data = {};
        var nodeList = $(document).find($('[type="file"]'));

        formData.append('templateId', $('#templateId').attr('value'));
        formData.append('advertiserId', $('#advertiserId').attr('value'));
        formData.append('companyId', $('#companyId').attr('value'));
        formData.append('action', 'upload');

        for (var i = 0; i < nodeList.length; i++)
        {
            var myId = nodeList[i].getAttribute('id');
            var targetId = myId.replace('_input', '');
            var fileSelect = $("#" + myId);

            var files = fileSelect.prop("files");

            if(files.length > 0)
            {
                var file = files[0];
                formData.append(targetId, file);
            }
        }

        var xhr =  new XMLHttpRequest();
        xhr.upload.addEventListener('load', onloadHandler, false);
        xhr.onreadystatechange = function(e) {
            if(xhr.readyState == 4) {
                response = $.parseJSON(xhr.response);
                imgsrc = response.imgsrc;

                $("#previewImage img").attr('src', imgsrc + '?' + new Date().getTime());
            }
        };
        xhr.open('POST', '/chameleon/ajax/changeSvg.php', true);
        xhr.send(formData);
        return false;

        function onloadHandler(e, args) {
            response = $.parseJSON(xhr.response);
            imgsrc = response.imgsrc;
            $("#previewImage img").attr('src', imgsrc + new Date().getTime());
        }

        function onloadstartHandler() {
        }

        function onprogressHandler() {
        }
    });

    // prepare image map
    var highlightColor = {};
    if($('.textTitle').length > 0) {
        highlightColor['text']      = colorToHex($('.textTitle').css('background-color')).replace('#', '');
    }
    if($('.imageTitle').length > 0) {
        highlightColor['image']     = colorToHex($('.imageTitle').css('background-color')).replace('#', '');
    }
    if($('.rectangleTitle').length > 0) {
        highlightColor['rectangle'] = colorToHex($('.rectangleTitle').css('background-color')).replace('#', '');
    }
    if($('.groupTitle').length > 0) {
        highlightColor['group']     = colorToHex($('.groupTitle').css('background-color')).replace('#', '');
    }

    var areas = [];
    var areaList = $('[name="template_selection"]').find('area');
    $.each(areaList, function(index, value) {
        value = $(value).attr("data-key").replace('area#', '');
        var type = value.split('_').pop();
        areas.push({ key: value, strokeColor: highlightColor[type]});
    });

    $("#previewImage img").mapster({
        fillColor: 'ff005',
        fillOpacity: 0,
        strokeWidth: 2,
        stroke: true,
        strokeColor: 'ff0000',
        singleSelect: true,
        clickNavigate: false,
        mapKey: 'data-key',
        areas: areas
    });



    /**
     *   Toolbar buttons
     *
     **/
    $('.fa-btn').on('click', function(e) {
        btn = $(this).attr('id');
        if(btn === 'flash') {
            $('#previewSwf').toggle();
        } else if(btn === 'live') {
            //generate preview images
            // hiding flash preview when opening live preview since flash will hide at least some portions of the live preview banners
            $('#previewSwf').hide();
            var formData = new FormData();
            var data = {};
            var nodeList = $(document).find($('[type="file"]'));

            overlayOn();
            $('#preparepreviewalert').show();

            formData.append('templateId', $('#templateId').attr('value'));
            formData.append('advertiserId', $('#advertiserId').attr('value'));
            formData.append('companyId', $('#companyId').attr('value'));
            formData.append('action', 'upload');

            var xhr =  new XMLHttpRequest();
            xhr.onreadystatechange = function(e) {
                if(xhr.readyState == 4) {
                    response = $.parseJSON(xhr.response);
                    var newNode = '';
                    for(var preview in response) {
                        newNode += '<li><a data-imagelightbox="preview" href="' + response[preview] + '?ts=' + new Date().getTime()  + '"></a></li>\n';
                    }
                    $('#imagelightbox-list > li').remove();
                    $(newNode).appendTo('#imagelightbox-list');
                    $('a[data-imagelightbox="preview"]').trigger("click");
                }
            };
            xhr.open('POST', '/chameleon/ajax/getLivePreview.php', true);
            xhr.send(formData);
            return false;

        }
        else if(btn ==='save')
        {
            // We are only about to send the data, it has NOT been stored so far,
            // there is no guarantee that the data will be saved
            // The message will be removed when the ajax call returned something
            $(".savealert").html('Saving data, please wait');
            $(".savealert").removeClass("in").show();
        }
        else if(btn === 'cancel')
        {
            e.preventDefault();
            window.location.reload();
        }
    });


    $('#editor').on('submit', function(e){
        e.preventDefault();
        var action = btn;
        var xhr = new XMLHttpRequest();

        $("#previewImage img").unbind('mapster');

        $(".savealert").html('Saving data, please wait');

        xhr.onreadystatechange = function(e) {
            if(xhr.readyState == 4) {
                if(action === 'save') {
                    somethingChanged = false;
                }

                $(".savealert").html('Template changes successfully saved');
                response = $.parseJSON(xhr.response);
                imgsrc = response.imgsrc;
                $("#previewImage img").unbind('mapster');

                var gifsrc = imgsrc + '.gif' + '?ts=' + new Date().getTime();
                var swfsrc = imgsrc + '.swf' + '?ts=' + new Date().getTime();

                // get current file dimensions and check filesize limit
                var dimensions = $('[name$=globalDimensions] option:selected').text().replace(/ \(.*\)/, '');
                var gifFilesize = (Math.round(response.gifFilesize / 1024).toFixed(2)) + ' kB';
                var swfFilesize = (Math.round(response.swfFilesize / 1024).toFixed(2)) + ' kB';
                if(response.gifFilesize / 1024 > window.gifSizeLimits[dimensions]) {
                    // ALERT
                    $('#filesize-gif').parent().addClass('filesize-warning');
                    $('#filesize-gif').addClass('filesize-warning');
                } else {
                    $('#filesize-gif').parent().removeClass('filesize-warning');
                    $('#filesize-gif').removeClass('filesize-warning');
                }

                if(response.swfFilesize / 1024 > window.swfSizeLimits[dimensions]) {
                    // ALERT
                    $('#filesize-swf').parent().addClass('filesize-warning');
                    $('#filesize-swf').addClass('filesize-warning');
                } else {
                    $('#filesize-swf').parent().removeClass('filesize-warning');
                    $('#filesize-swf').removeClass('filesize-warning');
                }

                $("#previewImage img").attr('src', gifsrc);
                $("[name='movie']").attr('value', swfsrc);
                $("[name='movie']").prop('value', swfsrc);
                $("#previewSwf object").prop('data', swfsrc);

                $('#filesize-gif').attr('value', gifFilesize);
                $('#filesize-gif').attr('value', gifFilesize);
                $('#filesize-swf').prop('value', swfFilesize);
                $('#filesize-swf').prop('value', swfFilesize);

                $(".savealert").removeClass("in").delay(1000).addClass("in").fadeOut(2000);
            }
        }

        var formData = new FormData();

        /* ******************************************************************** */
        var nodeList = $(document).find($('[type="file"]'));

        for (var i = 0; i < nodeList.length; i++)
        {
            var myId = nodeList[i].getAttribute('id');
            var targetId = myId.replace('_input', '');
            var fileSelect = $("#" + myId);

            if("undefined" !== typeof fileSelect.prop("files")) {
                var files = fileSelect.prop("files");
                if("undefined" !== typeof files && files.length > 0)
                {
                    var file = files[0];
                    formData.append(targetId, file);
                }
            }
        }
        /* ******************************************************************** */

        var data = $('#editor').serializeArray(); // + "&action=" + btn;

        formData.append('action', action);

        $.each(data, function(key, inputfield) {
            formData.append(inputfield.name, inputfield.value);
        });

        xhr.open('POST', '/chameleon/ajax/changeSvg.php', true);
        xhr.send(formData);
    });

    $('.picker').colorpicker();

    $('.picker').colorpicker().on('changeColor', function(e) {
        $(this).attr('value', e.color.toHex());
        var color = e.color.toHex();
        var id = $(this).attr('name').split('#');
        $(this).val(color);

        if(id[1] === 'primary-color' || id[1] === 'secondary-color')
        {
            $('#' + id[1] + '--preview').css("background-color", color);
        }
        else
        {
            $('#' + id[0] + '--preview').css("background-color", color);
        }
    });

    $('.picker').colorpicker().on('show', function(e) {
        // enable preview update for this color picker again
        $(this).data('disable_update', false);
    });

    $('.picker').colorpicker().on('hide', function(e) {
        // for some strange unknown reason, after the color picker had been selected once
        // it will always receive a hide event when the page is clicked somewhere.
        // We disable permanent updates here and enable them again when the picker is opened
        if($(this).data('disable_update') != true) {
            $(this).trigger('change');
        }
        $(this).data('disable_update', true);
    });

    $('.globalColor').focusout(function(){
        var id = $(this).attr('id');
        var color = $('#'+id).val();
        var idsplit = id.split('-');
        $('.'+idsplit[0]).css("background-color", color);
        $('#' + id + '--preview').css("background-color", color);
    });

    $('[id$="_input"]').fileinput({
        'showUpload': false,
        'showPreview': false,
        'showCaption': true
    });

    $('[id$="_source"]').change(function() {
        console.log('source changed!');
    });

    $('[id$="_link"]').change(function() {
        console.log('link changed!');
    });



    function updateGroup(baseElement) {
        // if group is edited:
        groupId = $(baseElement).parents('[id^=grouppanel]').attr('id').replace('grouppanel_', '');
        if(groupId > 0) {
            // NOTE: the value attribute specifies the initial value of an input,
            // but the value property specifies the current value - baseElement comes in
            // VERY handy here :)

            // find the name of the edited attribute
            attributeName = $(baseElement).attr('name').replace(/.*\#/i, '');

            updateField = $(document).find('[name="' + groupId + '#' + attributeName + '"]');

            var oldValue = $(baseElement).attr('value');
            var newValue = $(baseElement).prop('value');

            var elements = $("[data-groupid=" + groupId + "]");

            // TODO: Update imageMap
            //       implement colorpicker updates

            // Note: even if the single component editor panes aren't displayed
            // we need to update them in order to have the data up to date for generating
            // previews and saving!

            switch(attributeName) {
                case 'x':
                case 'y':
                    var delta = newValue - oldValue;

                    elements.each(function (i, member) {
                        var inElem = $(member).find('[name$="#' + attributeName + '"]');
                        elementName = inElem.attr('name').replace(/\#.*/i, '')
                        $(inElem).prop('value', (Number(inElem.prop('value')) + Number(delta)));
                        $(inElem).attr('value', (Number(inElem.attr('value')) + Number(delta)));

                        // find image map area element

                        $("#previewImage img").unbind('mapster');

                        var imapElement = $('area#' + elementName);
                        var coords = $(imapElement).attr('coords').split(',');

                        if(attributeName === 'x') {
                            coords[0] = Number(coords[0]) + Number(delta);
                            coords[1] = Number(coords[1]);
                            coords[2] = Number(coords[2]) + Number(delta);
                            coords[3] = Number(coords[3]);
                        } else if(attributeName === 'y') {
                            coords[0] = Number(coords[0]);
                            coords[1] = Number(coords[1]) + Number(delta);
                            coords[2] = Number(coords[2]);
                            coords[3] = Number(coords[3]) + Number(delta);
                        }

                        coords = coords.join(',');

                        $(imapElement).attr('coords', coords);
                        $(imapElement).prop('coords', coords);

                        $("#previewImage img").mapster({
                            fillColor: 'ff005',
                            fillOpacity: 0.1,
                            strokeWidth: 3,
                            stroke: true,
                            strokeColor: 'ff0000',
                            singleSelect: true,
                            clickNavigate: false
                        });

                        var coords = $(imapElement).attr('coords').split(',');
                    });
                    // TODO: calculate new image map coordinates
                    //
                    break;
                case 'text':
                    elements.each(function (i, member) {
                        inElem = $(member).find('[name$="' + attributeName + '"]');
                        $(inElem).prop('value', newValue);
                        $(inElem).attr('value', newValue);
                    });
                    break;
                case 'fontFamily':
                    elements.each(function (i, member) {
                        inElem = $(member).find('[name$="' + attributeName + '"]');
                        if($(inElem).find('option[value="' + newValue + '"]').length) {
                            $(inElem).find('option[value="' + newValue + '"]').prop('selected', true);
                        }
                    });
                    break;
                case 'cmeoLink':
                    elements.each(function (i, member) {
                        inElem = $(member).find('[name$="' + attributeName + '"]');
                        if($(inElem).find('option[value="' + newValue + '"]').length) {
                            $(inElem).find('option[value="' + newValue + '"]').prop('selected', true);
                            $(inElem).find('option[value="' + newValue + '"]').attr('selected', true);
                        }
                    });
                    break;
                case 'fgcolor':
                    elements.each(function (i, member) {
                        if($(member).attr('data-type') == "text") {
                            inElem = $(member).find('[name$="fill"]');
                            $(inElem).prop('value', newValue);
                            $(inElem).attr('value', newValue);
                        }
                    });
                    break;
                case 'bgcolor':
                    elements.each(function (i, member) {
                        if($(member).attr('data-type') == "rectangle") {
                            inElem = $(member).find('[name$="fill"]');
                            $(inElem).prop('value', newValue);
                            $(inElem).attr('value', newValue);
                        }
                    });
                    break;
                default:
                    break;
            }
        }
    }



    $('#awesomeEditor input, #awesomeEditor select').change(function() {
        var groupId = 0;
        var groupEdit = false;
        var attributeName = '';

        // shadow / stroke

        if($(this).attr('id').indexOf('shadowCheckBox'))
        {
            if($(this).is(":checked"))
            {
                $(this).attr("checked", true);
            }
            else
            {
                $(this).attr("checked", false);
            }
        } else if($(this).attr('id').indexOf('shadowCheckBox')) {

            if($(this).is(":checked"))
            {
                $(this).attr("checked", true);
            }
            else
            {
                $(this).attr("checked", false);
            }
        }

        // TODO: recalculate groups

        if($(this).parents('[id^=grouppanel]').length) {
            updateGroup(this);
        } else {
            var elementName = $(this).attr('name').replace(/\#.*/i, '');

            // if individual element is edited:
//            groupId = Number($(this).parents('[data-groupid]').attr('data-groupid'));
//            console.log('group');
//            console.log($(this));
//            groupEdit = true;
//            if(groupId > 0) {
//                attributeName = $(this).attr('name').replace(/.*\#/i, '');
//
//                updateField = $(document).find('[name="' + groupId + '#' + attributeName + '"]');
//
//                if(attributeName === 'x' || attributeName === 'y') {
//                    var oldValue = $(this).attr('value');
//                    var newValue = $(this).prop('value');
//                    var delta = newValue - oldValue;
//                    updateField.attr('value', (Number(updateField.attr('value')) + Number(delta)));
//                }
//            }
        }
        somethingChanged = true;
        $('#editor').trigger('submit');
    });

    function updateGroupFromElements(groupId) {
    }

    function updateElementsFromGroup(groupId) {
    }

    function getElementsByGroupId(groupId) {
        var elements = $(document).find("[data-groupid=" + groupId + "]");
        return elements;
    }

    $('#overview').click(function() {
        if(somethingChanged === true) {
            var leaveConfirm = confirm('Unsaved changes detected! Continue?');
            if(true === leaveConfirm){
                // TODO: delete preview!
                // The preview pic will be outdated because it is based on
                // unsaved changes, thus we'll have to delete it to have it
                // being regenerated by the overview page controller
                window.location.href = "index.php?page=overview";
            }
        }
        else
        {
            window.location.href = "index.php?page=overview";
        }
        return false;
    });

    $("#category").change(function() {
        $( "#category option:selected" ).each(function() {

            var key = $(this).attr('value');
            var value = $(this).attr('title');
            category[key] = value;
        });
    })
    .trigger( "change" );


    $('body').on('click', '#addCategory', function(e) {
        var data = {};
        var categoryId    = $('#category').find(':selected').val()
        var categoryName  = $.trim($('#category').find(':selected').text());
        data.templateId   = $('#templateId').attr('value');
        data.categoryId   = categoryId;
        data.categoryName = categoryName;
        data.advertiserId = $('#advertiserId').attr('value');
        data.companyId    = $('#companyId').attr('value');
        var pstdata = JSON.stringify(data);
        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: "/chameleon/ajax/addCategory.php"
        }).done(function(){
        }).fail(function(){
            var templateId   = $('#templateId').attr('value');
            $('#categoryContainer').load('ajax/categoriesSelection.inc.php?templateId=' + templateId);
            var node = '<input type="text" disabled="disabled" id="subscription_' + categoryId + '" value="' + categoryName + '">';
            $('#global_categories').append(node);
        });
    });

    $('body').on('click', '#categoryContainer .removeCategory', function(e) {
        var data = {};
        data.templateId   = $('#templateId').attr('value');
        var categoryId    = $(this).attr('id');
        data.categoryId   = categoryId;
        data.categoryName = $.trim($('#category').find(':selected').text());
        data.advertiserId = $('#advertiserId').attr('value');
        data.companyId    = $('#companyId').attr('value');
        $.ajax({
            type: 'POST',
            data: data,
            dataType: "json",
            url:  '/chameleon/ajax/removeCategory.php'
        }).done(function(){
        }).fail(function(){
            $('#row_' + categoryId).remove();
            $('#subscription_' + categoryId).remove();
        });
    });


    // handle the "CD" (corporade design) properties:
    // CD color 1
    // CD color 2
    // CD fontk
    $('.preset').on('click', function(){
        console.log($(this).attr('id'));
        var identifier = $(this).attr('id').split('--');
        var groupId = identifier[0];
        switch(identifier[1])
        {
            case "primary":
            {
                var color = $('#primary-color').val();
                var id = identifier[0] + "";
                var groupId = $(this).closest('.panel').attr('id').replace('grouppanel_', '');
                $('#panel_'+identifier[0]+' #fill').val(color);
                $('[name="'+identifier[0]+'#fill"]').colorpicker('setValue', color);

                break;
            }
            case "secondary":
            {
                var secondaryColor = $('#secondary-color').val();
                $('#panel_'+identifier[0]+' #fill').val(secondaryColor);
                $('[name="'+identifier[0]+'#fill"]').colorpicker('setValue', secondaryColor);
                break;
            }
            case "fgprimary":
                // console.log('fgprimary');
                var color = $('#primary-color').val();
                var groupId = $(this).closest('.panel').attr('id').replace('grouppanel_', '');
                $('#' + groupId + '--fgpreview').css('background-color', color);
                $('#grouppanel_' + groupId).find('input#fgcolor').val(color);
                $('[data-groupid="' + groupId + '"][data-type="text"]').find('input[id$="--preview"]').val(color);
                $('[data-groupid="' + groupId + '"][data-type="text"]').find('input[id$="_fill"]').val(color);
                break;
            case "fgsecondary":
                // console.log('fgsecondary');
                var color = $('#secondary-color').val();
                var groupId = $(this).closest('.panel').attr('id').replace('grouppanel_', '');
                $('#' + groupId + '--fgpreview').css('background-color', color);
                $('#grouppanel_' + groupId).find('input#fgcolor').val(color);
                $('[data-groupid="' + groupId + '"][data-type="text"]').find('input[id$="--preview"]').val(color);
                $('[data-groupid="' + groupId + '"][data-type="text"]').find('input[id$="_fill"]').val(color);
                break;
            case "bgprimary":
                // console.log('bgprimary');
                var color = $('#primary-color').val();
                var groupId = $(this).closest('.panel').attr('id').replace('grouppanel_', '');
                $('#' + groupId + '--bgpreview').css('background-color', color);
                $('#grouppanel_' + groupId).find('input#bgcolor').val(color);
                $('[data-groupid="' + groupId + '"][data-type="rectangle"]').find('input[id$="--preview"]').val(color);
                $('[data-groupid="' + groupId + '"][data-type="rectangle"]').find('input[id$="_fill"]').val(color);
                break;
            case "bgsecondary":
                // console.log('bgsecondary');
                var color = $('#secondary-color').val();
                var groupId = $(this).closest('.panel').attr('id').replace('grouppanel_', '');
                $('#' + groupId + '--bgpreview').css('background-color', color);
                $('#grouppanel_' + groupId).find('input#bgcolor').val(color);
                $('[data-groupid="' + groupId + '"][data-type="rectangle"]').find('input[id$="--preview"]').val(color);
                $('[data-groupid="' + groupId + '"][data-type="rectangle"]').find('input[id$="_fill"]').val(color);
                break;
            case "presetFont":
            {
                // the CD font has been selected, either in an "individual" editor component
                // or in a group panel - in this case, the selected font has to be applied to
                // each font select of this group!
                var id = identifier[0] + "_fontFamily";
                var groupId = $(this).closest('.panel').attr('id').replace('grouppanel_', '');

                // apply font to current (clicked) field
                $('select#' + id + ' option').filter(function() {
                    return $(this).text() == $('#presetFontFamily option:selected').text();
                }).prop('selected', true);

                // update text field editor components of this group
                $('[data-groupid="' + groupId + '"]').find('.font-select option').filter(function() {
                    return $(this).text() == $('#presetFontFamily option:selected').text();
                }).prop('selected', true);

                break;
            }
        }
        $('#editor').trigger('submit');
    });

    // TODO: add an "initial" element to ALL templates?!
    $('area#head_large').trigger('click');

























// ACTIVITY INDICATOR

        var activityIndicatorOn = function()
            {
                $( '<div id="imagelightbox-loading"><div></div></div>' ).appendTo( 'body' );
            },
            activityIndicatorOff = function()
            {
                $( '#imagelightbox-loading' ).remove();
            },


            // OVERLAY

            overlayOn = function()
            {
                $( '<div id="imagelightbox-overlay"></div>' ).appendTo( 'body' );
            },
            overlayOff = function()
            {
                $( '#imagelightbox-overlay' ).remove();
            },


            // CLOSE BUTTON

            closeButtonOn = function( instance )
            {
                $( '<button type="button" id="imagelightbox-close" title="Close"></button>' ).appendTo( 'body' ).on( 'click touchend', function(){ $( this ).remove(); instance.quitImageLightbox(); return false; });
            },
            closeButtonOff = function()
            {
                $( '#imagelightbox-close' ).remove();
            },


            // CAPTION

            captionOn = function()
            {
                var description = $( 'a[href="' + $( '#imagelightbox' ).attr( 'src' ) + '"] img' ).attr( 'alt' );
                if( description.length > 0 )
                    $( '<div id="imagelightbox-caption">' + description + '</div>' ).appendTo( 'body' );
            },
            captionOff = function()
            {
                $( '#imagelightbox-caption' ).remove();
            },


            // NAVIGATION

            navigationOn = function( instance, selector )
            {
                var images = $( selector );
                if( images.length )
                {
                    var nav = $( '<div id="imagelightbox-nav"></div>' );
                    for( var i = 0; i < images.length; i++ )
                        nav.append( '<button type="button"></button>' );

                    nav.appendTo( 'body' );
                    nav.on( 'click touchend', function(){ return false; });

                    var navItems = nav.find( 'button' );
                    navItems.on( 'click touchend', function()
                    {
                        var $this = $( this );
                        if( images.eq( $this.index() ).attr( 'href' ) != $( '#imagelightbox' ).attr( 'src' ) )
                            instance.switchImageLightbox( $this.index() );

                        navItems.removeClass( 'active' );
                        navItems.eq( $this.index() ).addClass( 'active' );

                        return false;
                    })
                    .on( 'touchend', function(){ return false; });
                }
            },
            navigationUpdate = function( selector )
            {
                var items = $( '#imagelightbox-nav button' );
                items.removeClass( 'active' );
                items.eq( $( selector ).filter( '[href="' + $( '#imagelightbox' ).attr( 'src' ) + '"]' ).index( selector ) ).addClass( 'active' );
            },
            navigationOff = function()
            {
                $( '#imagelightbox-nav' ).remove();
            },


            // ARROWS

            arrowsOn = function( instance, selector )
            {
                var $arrows = $( '<button type="button" class="imagelightbox-arrow imagelightbox-arrow-left"></button><button type="button" class="imagelightbox-arrow imagelightbox-arrow-right"></button>' );

                $arrows.appendTo( 'body' );

                $arrows.on( 'click touchend', function( e )
                {
                    e.preventDefault();

                    var $this   = $( this ),
                        $target = $( selector + '[href="' + $( '#imagelightbox' ).attr( 'src' ) + '"]' ),
                        index   = $target.index( selector );

                    if( $this.hasClass( 'imagelightbox-arrow-left' ) )
                    {
                        index = index - 1;
                        if( !$( selector ).eq( index ).length )
                            index = $( selector ).length;
                    }
                    else
                    {
                        index = index + 1;
                        if( !$( selector ).eq( index ).length )
                            index = 0;
                    }

                    instance.switchImageLightbox( index );
                    return false;
                });
            },
            arrowsOff = function()
            {
                $( '.imagelightbox-arrow' ).remove();
            };


        //  WITH ACTIVITY INDICATION

        $( 'a[data-imagelightbox="a"]' ).imageLightbox(
        {
            onLoadStart:    function() { activityIndicatorOn(); },
            onLoadEnd:      function() { activityIndicatorOff(); },
            onEnd:          function() { activityIndicatorOff(); }
        });


        //  WITH OVERLAY & ACTIVITY INDICATION

        $( 'a[data-imagelightbox="b"]' ).imageLightbox(
        {
            onStart:     function() { overlayOn(); },
            onEnd:       function() { overlayOff(); activityIndicatorOff(); },
            onLoadStart: function() { activityIndicatorOn(); },
            onLoadEnd:   function() { activityIndicatorOff(); }
        });


        //  WITH "CLOSE" BUTTON & ACTIVITY INDICATION

        var instanceC = $( 'a[data-imagelightbox="c"]' ).imageLightbox(
        {
            quitOnDocClick: false,
            onStart:        function() { closeButtonOn( instanceC ); },
            onEnd:          function() { closeButtonOff(); activityIndicatorOff(); },
            onLoadStart:    function() { activityIndicatorOn(); },
            onLoadEnd:      function() { activityIndicatorOff(); }
        });


        //  WITH CAPTION & ACTIVITY INDICATION

        $( 'a[data-imagelightbox="d"]' ).imageLightbox(
        {
            onLoadStart: function() { captionOff(); activityIndicatorOn(); },
            onLoadEnd:   function() { captionOn(); activityIndicatorOff(); },
            onEnd:       function() { captionOff(); activityIndicatorOff(); }
        });


        //  WITH ARROWS & ACTIVITY INDICATION

        var selectorG = 'a[data-imagelightbox="g"]';
        var instanceG = $( selectorG ).imageLightbox(
        {
            onStart:        function(){ arrowsOn( instanceG, selectorG ); },
            onEnd:          function(){ arrowsOff(); activityIndicatorOff(); },
            onLoadStart:    function(){ activityIndicatorOn(); },
            onLoadEnd:      function(){ $( '.imagelightbox-arrow' ).css( 'display', 'block' ); activityIndicatorOff(); }
        });


        //  WITH NAVIGATION & ACTIVITY INDICATION

        var selectorE = 'a[data-imagelightbox="e"]';
        var instanceE = $( selectorE ).imageLightbox(
        {
            onStart:     function() { navigationOn( instanceE, selectorE ); },
            onEnd:       function() { navigationOff(); activityIndicatorOff(); },
            onLoadStart: function() { activityIndicatorOn(); },
            onLoadEnd:   function() { navigationUpdate( selectorE ); activityIndicatorOff(); }
        });


        //  ALL COMBINED

        var selectorF = 'a[data-imagelightbox="f"]';
        var instanceF = $( selectorF ).imageLightbox(
        {
            onStart:        function() { overlayOn(); closeButtonOn( instanceF ); arrowsOn( instanceF, selectorF ); },
            onEnd:          function() { overlayOff(); captionOff(); closeButtonOff(); arrowsOff(); activityIndicatorOff(); },
            onLoadStart:    function() { captionOff(); activityIndicatorOn(); },
            onLoadEnd:      function() { captionOn(); activityIndicatorOff(); $( '.imagelightbox-arrow' ).css( 'display', 'block' ); }
        });


        $('a[data-imagelightbox="preview"]').imageLightbox({
            onLoadStart:    function() {  },
            onStart:        function() { $('#preparepreviewalert').hide() },
            onEnd:          function() { overlayOff(); }
        });

    // TODO:
    // This is kind of a hack right now ... coming from the overview, the template is rendered without animation
    // already - which is good since we display it first. Then we generate the animated version and display it as
    // soon as it's ready. As a quick way to do so, just triggering the changeSvg event here. This is also stored
    // the unchanged data once again which is not really required and should be prevented.
    $('#editor').trigger('submit');






    function componentToHex(c) {
        var hex = Number(c).toString(16);
        return hex.length == 1 ? "0" + hex : hex;
    }

    function colorToHex(rgb) {
        var rgb = rgb.substring(4, rgb.length-1).replace(/ /g, '').split(',');
        return "#" + componentToHex(rgb[0]) + componentToHex(rgb[1]) + componentToHex(rgb[2]);
    }

});
