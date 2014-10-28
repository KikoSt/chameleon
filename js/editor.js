$(document).ready(function() {
    var btn;
    var somethingChanged = false;
    var category = {};

    $('.alert').hide();

    $(window).keydown(function(e){
        if(e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });

    $('.fa-btn').on('click', function(e) {
        btn = $(this).attr('id');
        if(btn === 'flash') {
            $('#previewSwf').toggle();
        } else if(btn === 'live') {
            console.log('HEY! WE ARE LIVE!');
        }
        if(btn ==='clone' || btn ==='save' || btn === 'preview')
        {
            $("."+btn+"alert").removeClass("in").show().delay(1000).addClass("in").fadeOut(2000);
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
        var id = $(this).attr('id');
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

        var imgsrc = '<?php echo $this->gif;?>';
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
            // $('#previewalert').show();
            response = $.parseJSON(xhr.response);
            imgsrc = response.imgsrc;
            $("#previewImage img").attr('src', imgsrc + new Date().getTime());
        }

        function onloadstartHandler() {
        }

        function onprogressHandler() {
        }
    });

    function componentToHex(c) {
        var hex = Number(c).toString(16);
        return hex.length == 1 ? "0" + hex : hex;
    }

    function colorToHex(rgb) {
        var rgb = rgb.substring(4, rgb.length-1).replace(/ /g, '').split(',');
        return "#" + componentToHex(rgb[0]) + componentToHex(rgb[1]) + componentToHex(rgb[2]);
    }

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


    /* ***********************************
     * PREVIEW BUTTON
     *********************************** */
    $('#editor').on('submit', function(e){
        e.preventDefault();
        var action = btn;
        var xhr = new XMLHttpRequest();
        $("#previewImage img").unbind('mapster');

        xhr.onreadystatechange = function(e) {
            if(xhr.readyState == 4) {
                if(action === 'save') {
                    somethingChanged = false;
                }
                response = $.parseJSON(xhr.response);
                imgsrc = response.imgsrc;
                $("#previewImage img").unbind('mapster');
                var gifsrc = imgsrc + '.gif' + '?ts=' + new Date().getTime();
                var swfsrc = imgsrc + '.swf' + '?ts=' + new Date().getTime();
                $("#previewImage img").attr('src', gifsrc);
                $("[name='movie']").attr('value', swfsrc);
                $("[name='movie']").prop('value', swfsrc);
                $("#previewSwf object").prop('data', swfsrc);
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
        'showCaption': true,
    });

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
            //    $(this).attr('disabled', false).addClass('picker');
            //    $(this).attr('disabled', false);
            }
            else
            {
                $(this).attr("checked", false);
            //    $("#" + id + "_shadowColor").attr('disabled', true).removeClass('picker');
            //    $("#" + id + "_shadowDist").attr('disabled', true);
            }
        } else if($(this).attr('id').indexOf('shadowCheckBox')) {

            if($(this).is(":checked"))
            {
                $(this).attr("checked", true);
            //    $("#" + id + "_strokeColor").attr('disabled', false).addClass('picker');
            //    $("#" + id + "_strokeWidth").attr('disabled', false);
            }
            else
            {
                $(this).attr("checked", false);
            //    $("#" + id + "_strokeColor").attr('disabled', true).removeClass('picker');
            //    $("#" + id + "_strokeWidth").attr('disabled', true);
            }
        }

        // TODO: recalculate groups

        if($(this).parents('[id^=grouppanel]').length) {
            // if group is edited:
            groupId = $(this).parents('[id^=grouppanel]').attr('id').replace('grouppanel_', '');
            if(groupId > 0) {
                // NOTE: the value attribute specifies the initial value of an input,
                // but the value property specifies the current value - this comes in
                // VERY handy here :)
                // console.log($(this).prop('value'));
                // console.log($(this).attr('value'));
                //
                // find the name of the edited attribute
                attributeName = $(this).attr('name').replace(/.*\#/i, '');

                updateField = $(document).find('[name="' + groupId + '#' + attributeName + '"]');

                var oldValue = $(this).attr('value');
                var newValue = $(this).prop('value');

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
        data.templateId   = '<?php echo $this->templateId; ?>';
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
            $('#categoryContainer').load('ajax/categoriesSelection.inc.php?templateId=<?php echo $this->templateId; ?>');
            var node = '<input type="text" disabled="disabled" id="subscription_' + categoryId + '" value="' + categoryName + '">';
            $('#global_categories').append(node);
        });
    });

    $('body').on('click', '#categoryContainer .removeCategory', function(e) {
        var data = {};
        data.templateId   = '<?php echo $this->templateId; ?>';
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

    $('.preset').on('click', function(){
        var identifier = $(this).attr('id').split('--');

        switch(identifier[1])
        {
            case "primary":
            {
                var primaryColor = $('#primary-color').val();
                $('#panel_'+identifier[0]+' #fill').val(primaryColor);
                $('[name="'+identifier[0]+'#fill"]').colorpicker('setValue', primaryColor);
                break;
            }
            case "secondary":
            {
                var secondaryColor = $('#secondary-color').val();
                $('#panel_'+identifier[0]+' #fill').val(secondaryColor);
                $('[name="'+identifier[0]+'#fill"]').colorpicker('setValue', secondaryColor);
                break;
            }
            case "presetFont":
            {
                var id = identifier[0] + "#fontfamily";

                $('select option').filter(function() {
                    return $(this).text() == $('#presetFontFamily option:selected').text();
                }).prop('selected', true);
                break;
            }
        }
        $('#editor').trigger('submit');
    });

    // TODO: add a "initial" element to ALL templates?!
    $('area#head_large').trigger('click');
});
