$(document).ready(function() {

    $.showDuration = 0;

    var renderXHR; // store currently running xhr request rendering the preview to allow aborting
    $.xhrPool = [];

    registerXHR = function(jqXHR) {
        showLoadification();
        $.xhrPool.push(jqXHR);
    }

    unregisterXHR = function(jqXHR) {
        var i = $.inArray(jqXHR, $.xhrPool);
        if(i > -1) $.xhrPool.splice(i, 1);
        if($.xhrPool.length < 1) {
            hideLoadification();
        }
    }

    $.ajaxSetup({
        beforeSend: registerXHR,
        complete:  unregisterXHR
    });

    $.ajax({
        url: 'ajax/getSizeLimits.php'
    }).done(function(data) {
        window.sizeLimits = $.parseJSON(data);
        window.swfSizeLimits = window.sizeLimits.swf;
        window.gifSizeLimits = window.sizeLimits.gif;
    });

    var somethingChanged = false;
    var category = {};

    // TODO: check: why isn't it hidden by css in the first place?
    $('#alert-custom').hide();

    // refresh preview image for this template upon loading the page
    var action = 'update';
    updateTemplateData(action);
    mapsterInit();

    /**
     *  configure and activate the fileinput
     *  plugin
     **/
    $('[id$="_input"]').fileinput({
        'showUpload': false,
        'showPreview': false,
        'showCaption': true
    });

    /**
     * image map clicked
     */
    $('.maparea').on('click', function(e) {
        var id = $(this).attr('id');
        // store the currently clicked element's id to reactivate it after refocusing this area after reloading!
        $.activeIMapElementId = id;
        if(id.substr(0, 5) != 'group') {
            // individual element selected
            $('.component').hide(0);
            $('#panel_' + id).show($.showDuration);
            $('#grouppanel_' + $('#panel_' + id).attr('data-groupid')).show($.showDuration);
            // initially set the color previews/buttons
            $('#' + id + '--primary').css("background-color", $('#primary-color').val());
            $('#' + id + '--secondary').css("background-color", $('#secondary-color').val());
            $('#' + $('#panel_' + id).attr('data-groupid') + '--fgprimary').css("background-color", $('#primary-color').val());
            $('#' + $('#panel_' + id).attr('data-groupid') + '--fgsecondary').css("background-color", $('#secondary-color').val());
            $('#' + $('#panel_' + id).attr('data-groupid') + '--bgprimary').css("background-color", $('#primary-color').val());
            $('#' + $('#panel_' + id).attr('data-groupid') + '--bgsecondary').css("background-color", $('#secondary-color').val());
        } else {
            // group element selected
            $('.component').hide(0);
            id = id.substr(6, 10);
            $('#grouppanel_' + id).show($.showDuration);
            // initially set the color previews/buttons
            $('#' + id + '--primary').css("background-color", $('#primary-color').val());
            $('#' + id + '--secondary').css("background-color", $('#secondary-color').val());
            $('#' + id + '--fgprimary').css("background-color", $('#primary-color').val());
            $('#' + id + '--fgsecondary').css("background-color", $('#secondary-color').val());
            $('#' + id + '--bgprimary').css("background-color", $('#primary-color').val());
            $('#' + id + '--bgsecondary').css("background-color", $('#secondary-color').val());
        }
    });

    // activate the "head_large" element as "default" selected element
    // TODO: add an "initial" element to ALL templates?!
    $('area#head_large').trigger('click');


    /***************************************
     *
     *  HANDLERS!
     *
     ***************************************/


    /**
     *   Toolbar buttons
     **/
     $('#play').on('click', function(e) {
        var gifsrc = $('#previewImage').attr('src');
        gifsrc = gifsrc.replace(/\?ts=.*/, '?ts=' + new Date().getTime());
        $("#previewImage").unbind('mapster');
        $("#previewImage").attr('src', gifsrc);
        mapsterInit();
     });

     $('#flash').on('click', function(e) {
         $('#previewSwf').toggle($.showDuration);
     });

     $('#cancel').on('click', function(e) {
         e.preventDefault();
         window.location.reload();
     });

     $('#live').on('click', function(e) {
        //generate preview images
        // hiding flash preview when opening live preview
        // since flash will hide at least some portions of
        // the live preview banners
        $('#previewSwf').hide();
        overlayOn();
        $('#preparepreviewalert').show();

        var formData = new FormData();
        var nodeList = $(document).find($('[type="file"]'));

        formData.append('action', 'upload');

        var xhr =  new XMLHttpRequest();
        xhr.onload = function() {
            unregisterXHR(xhr);
            if(xhr.status === 200) {
            // done
                response = $.parseJSON(xhr.response);
                var newNode = '';
                for(var preview in response) {
                    newNode += '<li><a data-imagelightbox="preview" href="' + response[preview] + '?ts=' + new Date().getTime()  + '"></a></li>\n';
                }
                $('#imagelightbox-list > li').remove();
                $(newNode).appendTo('#imagelightbox-list');
                $('a[data-imagelightbox="preview"]').trigger("click");
            } else if(xhr.status !== 200) {
                // failed to load preview data
            }
        };
        xhr.open('POST', '/chameleon/ajax/getLivePreview.php', true);
        registerXHR(xhr);
        xhr.send(formData);
     });

     $('#save').on('click', function(e) {
        $(".savealert").html('Saving data, please wait');
        $(".savealert").removeClass("in").show();
        updateTemplateData('save');
    });


    /**
     *  disable default "submit" action
     */
    $('#editor').on('submit', false);

    /**
     *  ONCHANGE handler
     *
     * most input and select fields, so largest part of the editor
     */
    $('#awesomeEditor input, #awesomeEditor select').on('change', function(e) {
        var inputType = $(this).attr('type');
        var action = 'update';

        // all the same right now ...
        if(inputType == 'file') {
            updateTemplateData(action);
        } else {
            updateTemplateData(action);
        }

        // handle custom checkboxes (shadow/stroke currently)
        toggleCheckboxes(this);

        if($(this).parents('[id^=grouppanel]').length) {
            updateGroup(this);
        } else {
            var elementName = $(this).attr('name').replace(/\#.*/i, '');
        }
        somethingChanged = true;
        deletePreviewImages('removeLivePreview');
    });


    /**
     *  'CD' (corporade design) properties click handler
     * CD color 1
     * CD color 2
     * CD font
     **/
    $('.preset').on('click', function(){
        somethingChanged = true;
        deletePreviewImages('removeLivePreview');

        var identifier = $(this).attr('id').split('--');
        var groupId = identifier[0];
        var action = 'update';
        switch(identifier[1])
        {
            case "primary":
            case "secondary":
            {
                var color = $('#' + identifier[1] + '-color').val();
                var groupId = $(this).closest('.panel').attr('id').replace('grouppanel_', '');
                $('#panel_'+identifier[0]+' #fill').val(color);
                $('[name="'+identifier[0]+'#fill"]').colorpicker('setValue', color);

                break;
            }
            case "fgprimary":
            case "fgsecondary":
            case "bgprimary":
            case "bgsecondary":
                var id = identifier[1];
                var layer = id.substr(0,2);
                var number = id.substr(2);
                var color = $('#' + number + '-color').val();
                var groupId = $(this).closest('.panel').attr('id').replace('grouppanel_', '');
                updateColorPanels(color, layer, groupId);
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
        updateTemplateData(action);
    });

    /**
     *  'overview' backlink click handler
     */
    $('#overview').on('click', function() {
        if(somethingChanged === true) {
            var leaveConfirm = confirm('Unsaved changes detected! Continue?');
            if(true === leaveConfirm){
                // TODO: delete preview!
                // The preview pic will be outdated because it is based on
                // unsaved changes, thus we'll have to delete it to have it
                // being regenerated by the overview page controller

                deletePreviewImages('removeEditorPreview');

                window.location.href = "index.php?page=overview";
            }
        }
        else
        {
            window.location.href = "index.php?page=overview";
        }
        return false;
    });


    $("#category").on('change', function() {
        $( "#category option:selected" ).each(function() {

            var key = $(this).attr('value');
            var value = $(this).attr('title');
            category[key] = value;
        });
    })
    .trigger( "change" );

    /***************************************
     *
     *  Handles the category alignment (start)
     *
     ***************************************/

    var editor = new Cmeo("editor");

    /**
     * Add one or more categories to the "Assigned" list and remove the same from the "Available" list
     */
    $('#addCategory').on('click', function(e) {
        e.preventDefault();
        editor.moveCategoryModal('assigned');
    });

    /**
     * Remove one or more categories from the "Assigned" list and add the same to the "Assigned" list
     */
    $('#removeCategory').on('click', function(e) {
        e.preventDefault();
        editor.moveCategoryModal('available');
    });

    /**
     * Add categories to the template via the "Select categories" pop-up (AJAX)
     */
    $('.addCategoryOverview').on('click', function(e) {
        e.preventDefault();
        var id = $(this).attr('id').split('-');
        var templateId = parseInt(id[1]);
        editor.addCategoryByModalView(templateId);
    });

    /**
     * Remove categories from the template via the "Select categories" pop-up (AJAX)
     *
     * The category will not be deleted but set on "DELETED"
     */
    $('.removeCategoryOverview').on('click', function(e) {
        e.preventDefault();
        var id = $(this).attr('id').split('-');
        var templateId = parseInt(id[1]);
        editor.removeCategoryByModalView(templateId);
    });

    /***************************************
     *
     *  Handles the category alignment (end)
     *
     ***************************************/


    /**
     *  Update individual preselect color fields with global color values (bgcolor!)
     *  when global - i.e. cd - colors are changed
     **/
    $('.globalColor').on('focusout', function() {
        var id = $(this).attr('id');
        var color = $('#'+id).val();
        var idsplit = id.split('-');
        $('.' + idsplit[0]).css("background-color", color);
        $('#' + id + '--preview').css("background-color", color);
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







    /**
     *  COLORPICKER FUNCTIONS AND HANDLERS
     **/
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







    /**
     *  IMAGELIGHTBOX FUNCTIONS AND HANDLERS
     **/


    $('a[data-imagelightbox="preview"]').imageLightbox({
        onLoadStart:    function() {  },
        onStart:        function() { $('#preparepreviewalert').hide() },
        onEnd:          function() { overlayOff(); }
    });



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





    /**
     * GENERIC FUNCTIONS
     **/


    /**
     * deletePreviewImages
     *
     * delete either THE preview image from the editor also used in the overview
     *            or ALL preview images from the "live" preview.
     *
     * The single preview image should always be removed when unsaved changes are
     * discarded - in this case, the preview image will not be up-to-date in the
     * overview, and if it no longer exists, it will be regenerated (no animation right now)
     *
     * All live preview images should be deleted when any change is applied to the
     * template - the live preview will no longer be up-to-date in this case
     *
     * @param mode $mode string: 'removeEditorPreview' or 'removeLivePreview'
     * @access public
     * @return void
     */
    function deletePreviewImages(mode) {
        if(mode !== 'removeEditorPreview' && mode !== 'removeLivePreview') {
            return false;
        }
        var data = {};
        data.templateId   = $('#templateId').attr('value');
        data.advertiserId = $('#advertiserId').attr('value');
        data.companyId    = $('#companyId').attr('value');
        data.mode         = mode;

        $.ajax({
            type: "POST",
            data: data,
            dataType: "json",
            url: "ajax/deletePreviewImages.php"
        }).done(function(){
            // nothing to do right now
        }).fail(function(){
            // nothing to do right now
        });
    }





    /**
     *  updateColorPanels
     *
     * apply changes made using group color fields (fgprimary, fgsecondary, bgprimary, bgsecondary)
     * to the individual editor element panels
     **/
    function updateColorPanels(color, layer, groupId) {
        var datatype;

        if(layer === 'bg') {
            datatype = 'rectangle';
        } else if(layer === 'fg') {
            datatype = 'text';
        } else {
            return false;
        }

        $('#' + groupId + '--' + layer + 'preview').css('background-color', color);
        $('#grouppanel_' + groupId).find('input#' + layer + 'color').val(color);
        $('[data-groupid="' + groupId + '"][data-type="' + datatype + '"]').find('input[id$="--preview"]').val(color);
        $('[data-groupid="' + groupId + '"][data-type="' + datatype + '"]').find('input[id$="_fill"]').val(color);
        $('[data-groupid="' + groupId + '"][data-type="' + datatype + '"]').find('div[id$="--preview"]').css('background-color', color);
        $('[name="' + groupId + '#' + layer + 'color"]').colorpicker('setValue', color);
    }




    function updateTemplateData(action) {
        refreshGif(action, 'static');
    }

    function showLoadification()
    {
        if($('#loadification').length == 0) {
            $('#loadification').remove();
            var loader = '<div id="loadification"><img src="img/loading.gif" alt="loading"/></div>';
            $('body').append(loader);
        }
    }

    function hideLoadification()
    {
       $('#loadification').remove();
    }


    /**
     *  updateTemplateData
     *
     * render new media files based on changed template data;
     *
     * if action !== 'save', the data will NOT be save to the db!
     *
     **/
    function refreshGif(action, mode) {
        if(renderXHR !== undefined) {
            unregisterXHR(renderXHR);
            renderXHR.abort();
        }
        renderXHR = new XMLHttpRequest();
        var formData = new FormData();
        var data = $('#editor').serializeArray();
        var nodeList = $(document).find($('[type="file"]'));

        if(undefined === mode) mode = 'static';

        $.each(data, function(key, inputfield) {
            formData.append(inputfield.name, inputfield.value);
        });

        if(undefined === action) {
            action = 'update';
        }

        formData.append('action', action);
        formData.append('mode', mode);
        // TODO!
        formData.append('auditUserId', 14);

        // process file input fields (images)
        for (var i = 0; i < nodeList.length; i++) {
            var myId = nodeList[i].getAttribute('id');
            var targetId = myId.replace('_input', '');
            var fileSelect = $("#" + myId);

            if("defined" !== typeof fileSelect.prop("files")) {
                var files = fileSelect.prop("files");
                if("undefined" !== typeof files && files.length > 0)
                {
                    var file = files[0];
                    formData.append(targetId, file);
                }
            }
        }

        renderXHR.onload = function() {
            if(renderXHR.status === 200) {
                $('area#' + $.activeIMapElementId).trigger('click');
                unregisterXHR(renderXHR);
                // done
                updateEditorMediaMarkup(renderXHR.response);
                // if a static gif had been rendered, render an animated version now
                // and replace the static version asap
                if(mode === 'static')
                {
                    refreshGif(action, 'animated');
                }
                if(action === 'save') {
                    $(".savealert").html('Template changes successfully saved');
                    $(".savealert").removeClass("in").delay(1000).addClass("in").fadeOut(2000);
                }
            } else if(renderXHR.status !== 200) {
                // fail
            }
        }

        showLoadification();
        renderXHR.open('POST', '/chameleon/ajax/changeSvg.php', true);
        registerXHR(renderXHR);
        renderXHR.send(formData);
    }


    /**
     *  toggleCheckboxes
     *
     * align the custom checkboxes for shadow/stroke
     *
     **/
    function toggleCheckboxes(target) {
        if($(target).attr('id') && $(target).attr('id').indexOf('shadowCheckBox')) {
            if($(target).is(":checked"))
            {
                $(target).attr("checked", true);
            }
            else
            {
                $(target).attr("checked", false);
            }
        } else if($(target).attr('id') && $(target).attr('id').indexOf('shadowCheckBox')) {
            if($(target).is(":checked"))
            {
                $(target).attr("checked", true);
            }
            else
            {
                $(target).attr("checked", false);
            }
        }
    }



    /**
     * updateEditorMediaMarkup
     *
     * - update both gif and swf media paths in order to display new preview media
     * - update both gif and swf filesize output fields and - if required - filesize warnings
     *
     * @param data $data
     * @access public
     * @return void
     */
    function updateEditorMediaMarkup(data) {
        // required! The image will NOT update otherwise!
        $("#previewImage").unbind('mapster');
        data = $.parseJSON(data);

        // prepare required information
        var gifsrc = data.imgsrc + '.gif?ts=' + new Date().getTime();
        var swfsrc = data.imgsrc + '.swf?ts=' + new Date().getTime();

        var dimensions    = $('[name$=globalDimensions] option:selected').text().replace(/ \(.*\)/, '');
        var gifFilesize   = data.gifFilesize;
        var swfFilesize   = data.swfFilesize;
        var gifFilesizeKB = (Math.round(data.gifFilesize / 1024).toFixed(2));
        var swfFilesizeKB = (Math.round(data.swfFilesize / 1024).toFixed(2));

        $("#previewImage").attr('src', gifsrc);
        $("[name='movie']").attr('value', swfsrc);
        $("[name='movie']").prop('value', swfsrc);
        $("#previewSwf object").prop('data', swfsrc);

        mapsterInit();

        if(gifFilesizeKB > window.gifSizeLimits[dimensions]) {
            $('#filesize-gif').parent().addClass('filesize-warning');
            $('#filesize-gif').addClass('filesize-warning');
        } else {
            $('#filesize-gif').parent().removeClass('filesize-warning');
            $('#filesize-gif').removeClass('filesize-warning');
        }

        if(swfFilesizeKB > window.swfSizeLimits[dimensions]) {
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

        $('#filesize-gif').attr('value', gifFilesizeKB) + ' kB';
        $('#filesize-gif').attr('value', gifFilesizeKB) + ' kB';
        $('#filesize-swf').prop('value', swfFilesizeKB) + ' kB';
        $('#filesize-swf').prop('value', swfFilesizeKB) + ' kB';
    }



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

            $(baseElement).attr('value', newValue)

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

    // initialize imageMap plugin
    function mapsterInit()
    {
        areas = getImageMapAreas();
        // start image map plugin
        $("#previewImage").mapster({
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
    }

    function getImageMapAreas() {
        // prepare image map
        var highlightColor = {};
        if($('.textTitle').length > 0) {
            highlightColor['text'] = colorToHex($('.textTitle').css('background-color')).replace('#', '');
        }
        if($('.imageTitle').length > 0) {
            highlightColor['image'] = colorToHex($('.imageTitle').css('background-color')).replace('#', '');
        }
        if($('.rectangleTitle').length > 0) {
            highlightColor['rectangle'] = colorToHex($('.rectangleTitle').css('background-color')).replace('#', '');
        }
        if($('.groupTitle').length > 0) {
            highlightColor['group'] = colorToHex($('.groupTitle').css('background-color')).replace('#', '');
        }

        var areas = [];
        var areaList = $('[name="template_selection"]').find('area');
        $.each(areaList, function(index, value) {
            value = $(value).attr("data-key").replace('area#', '');
            var type = value.split('_').pop();
            areas.push({ key: value, strokeColor: highlightColor[type]});
        });
        return areas;
    }


    function componentToHex(c) {
        var hex = Number(c).toString(16);
        return hex.length == 1 ? "0" + hex : hex;
    }

    function colorToHex(rgb) {
        var rgb = rgb.substring(4, rgb.length-1).replace(/ /g, '').split(',');
        return "#" + componentToHex(rgb[0]) + componentToHex(rgb[1]) + componentToHex(rgb[2]);
    }

    function getElementsByGroupId(groupId) {
        var elements = $(document).find("[data-groupid=" + groupId + "]");
        return elements;
    }

});
