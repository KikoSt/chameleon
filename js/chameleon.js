/**
 * Created by thomas on 14.08.14.
 */

$(document).ready(function() {
    var btn;

    $('.btn').on('click', function(){
        btn = $(this).attr('id');
    });

    $('#previewalert').hide();

    $(window).keydown(function(e){
        if(e.keyCode == 13) {
            e.preventDefault();
            return false;
        }
    });

    $('.checkbox #shadowCheckBox').change(function() {
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
            $("#" + id + "_shadowColor").attr('disabled', true).removeClass('picker').val('');
            $("#" + id + "_shadowDist").attr('disabled', true).val('');
        }
    });

    $('.checkbox #strokeCheckBox').change(function() {
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
            $("#" + id + "_shadowColor").attr('disabled', true).removeClass('picker').val('');
            $("#" + id + "_shadowDist").attr('disabled', true).val('');
        }
    });

    $('.kv-fileinput-upload').on('click', function(e){
        e.preventDefault();
        var nodeList = document.querySelectorAll('.file');
        var formData = new FormData();

        for (var i = 0; i < nodeList.length; i++)
        {
            var myId = nodeList[i].getAttribute('id');
            var fileSelect = document.querySelector("#" + myId + " #" + myId );
            var files = fileSelect.files;
            var j=0;

            while ( j < files.length)
            {
                var file = files[j];
                formData.append(myId, file, file.name);
                j++;
            }
        }

        formData.append("templateId",  document.getElementById('templateId').getAttribute('value'));
        formData.append("advertiserId",  document.getElementById('advertiserId').getAttribute('value'));
        formData.append("companyId",  document.getElementById('companyId').getAttribute('value'));

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/changeSvg.php', true);
        xhr.send(formData);

        location.reload();
    });

    $('#editor').on('submit', function(e){
        e.preventDefault();

        var imgsrc = '<?php echo $this->gif;?>';

        $.ajax({
            url: '/chameleon/ajax/changeSvg.php',
            data: $('#editor').serialize() + "&action=" + btn,
            type: 'POST',
            success: function(){
                $('#previewalert').show();

                $("#previewImage img").load(function() {
                    $(this).hide();
                    $(this).fadeIn('slow');
                }).attr('src', imgsrc);

                $('#globalsBody').load(function(){
                    $(this).hide();
                    $(this).fadeIn('slow');
                });
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
            }
        });
    });

    $('.picker').colorpicker();

    $("#fileUpload").fileinput();
});
