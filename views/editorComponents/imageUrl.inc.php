<div class="row">
    <label class="col-md-4">Image:</label>
    <input id="<?php echo $element->getId();?>_input"
           type="file"
           class="file"
           name="<?php echo $element->getId();?>#imageUrl"
        />
    <!-- initialize the plugin here for individual default name entries -->
    <script>
    $('#<?php echo $element->getId(); ?>_input').fileinput({
        'showUpload': false,
        'showPreview': false,
        'showCaption': true,
        'initialCaption': '<?php $arr = explode('/', $element->getImageUrl()); echo(array_pop($arr)); ?>'
    });
    </script>
</div>
