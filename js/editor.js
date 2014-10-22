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

    $('.btn').on('click', function(e) {
        btn = $(this).attr('id');
        if(btn ==='clone' || btn ==='save' || btn === 'preview')
        {
            $("."+btn+"alert").removeClass("in").show().delay(1000).addClass("in").fadeOut(2000);
        }
    });

    $('.subnav').on('click', function(e) {
        var id = $(this).attr('id');
        $('.component').hide();
        $('#panel_' + id).show();
        $('#grouppanel_' + $('#panel_' + id).attr('data-groupid')).show();
        $('#' + id + '--primary').css("background-color", $('#primary-color').val());
        $('#' + id + '--secondary').css("background-color", $('#secondary-color').val());
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


    $("#previewImage img").mapster({
        fillColor: 'ff005',
        fillOpacity: 0.1,
        strokeWidth: 3,
        stroke: true,
        strokeColor: 'ff0000',
        singleSelect: true,
        clickNavigate: false
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
                $("#previewImage img").attr('src', imgsrc + '?ts=' + new Date().getTime());
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

            var files = fileSelect.prop("files");

            if(files.length > 0)
            {
                var file = files[0];
                formData.append(targetId, file);
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


    $("#fileUpload").fileinput();

    $('#awesomeEditor input, #awesomeEditor select').change(function() {
        var groupId = 0;
        var groupEdit = false;
        var attributeName = '';

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
                // console.log(this);
                attributeName = $(this).attr('name').replace(/.*\#/i, '');

                // console.log(attributeName);

                updateField = $(document).find('[name="' + groupId + '#' + attributeName + '"]');

                // console.log('UpdateField: %o', updateField);

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

                        console.log(newValue + ' - ' + oldValue + ' = ' + delta);

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
                            // console.log(coords);

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
                            console.log(coords);
                        });
                        // TODO: calculate new image map coordinates
                        //
                        break;
                    case 'text':
                        console.log('text');
                        elements.each(function (i, member) {
                            inElem = $(member).find('[name$="' + attributeName + '"]');
                            // console.log('s% ==? %s', inElem.attr('value'), Number(inElem.attr('value')));
                            $(inElem).prop('value', newValue);
                            $(inElem).attr('value', newValue);
                        });
                        break;
                    case 'fontFamily':
                        console.log('fontFamily');
                        elements.each(function (i, member) {
                            inElem = $(member).find('[name$="' + attributeName + '"]');
                            if($(inElem).find('option[value="' + newValue + '"]').length) {
                                $(inElem).find('option[value="' + newValue + '"]').prop('selected', true);
                            }
                        });
                        break;
                    case 'cmeoLink':
                        console.log('cmeoLink');
                        elements.each(function (i, member) {
                            inElem = $(member).find('[name$="' + attributeName + '"]');
                            if($(inElem).find('option[value="' + newValue + '"]').length) {
                                $(inElem).find('option[value="' + newValue + '"]').prop('selected', true);
                            }
                        });
                        break;
                    case 'fgcolor':
                        console.log('fgcolor');
                        elements.each(function (i, member) {
                            if($(member).attr('data-type') == "text") {
                                inElem = $(member).find('[name$="fill"]');
                                $(inElem).prop('value', newValue);
                                $(inElem).attr('value', newValue);
                            }
                        });
                        break;
                    case 'bgcolor':
                        console.log('bgcolor');
                        elements.each(function (i, member) {
                            if($(member).attr('data-type') == "rectangle") {
                                inElem = $(member).find('[name$="fill"]');
                                $(inElem).prop('value', newValue);
                                $(inElem).attr('value', newValue);
                            }
                        });
                        break;
                    default:
                        console.log('Attribute %s not known ... ', attributeName);
                        break;
                }
            }
        } else {
            var elementName = $(this).attr('name').replace(/\#.*/i, '');
            console.log(elementName);

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
        $('#preview').trigger('click');
        console.log('click triggered');
        somethingChanged = true;
    });

    function updateGroupFromElements(groupId) {
    }

    function updateElementsFromGroup(groupId) {
    }

    function getElementsByGroupId(groupId) {
        var elements = $(document).find("[data-groupid=" + groupId + "]");
        console.log(elements);
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

    $('.preset').click(function(){
        var identifier = $(this).attr('id').split('#');

        switch(identifier[1])
        {
            case "primary":
            {
                $('#panel_'+identifier[0]+' #fill').val($('#primary-color').val());
                break;
            }
            case "secondary":
            {
                $('#panel_'+identifier[0]+' #fill').val($('#secondary-color').val());
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
    });

    //handles the enabling/disabling of the shadow form elements
    $('#shadowCheckBox.myCheckbox').click(function(e) {
        var id = $(this).attr('value');

        if($(this).is(":checked"))
        {
            $(this).attr("checked", true);
            $("#" + id + "_shadowColor").attr('disabled', false).addClass('picker');
            $("#" + id + "_shadowDist").attr('disabled', false);
        }
        else
        {
            $(this).attr("checked", false);
            $("#" + id + "_shadowColor").attr('disabled', true).removeClass('picker');
            $("#" + id + "_shadowDist").attr('disabled', true);
        }
    });

    //handles the enabling/disabling of the stroke form elements
    $('#strokeCheckBox.myCheckBox').click(function(e) {
        var id = $(this).attr('value');

        if($(this).is(":checked"))
        {
            $(this).attr("checked", true);
            $("#" + id + "_strokeColor").attr('disabled', false).addClass('picker');
            $("#" + id + "_strokeWidth").attr('disabled', false);
        }
        else
        {
            $(this).attr("checked", false);
            $("#" + id + "_strokeColor").attr('disabled', true).removeClass('picker');
            $("#" + id + "_strokeWidth").attr('disabled', true);
        }
    });

    // TODO: add a "initial" element to ALL templates?!
    $('area#head_large').trigger('click');
});
