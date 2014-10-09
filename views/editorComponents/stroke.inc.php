<div class="row">
    <label for="strokeCheckBox" class="col-md-4">Stroke:</label>
    <input id="<?php echo $element->getId();?>_stroke"
           class="myCheckbox"
           type="checkbox"
           name="<?php echo $element->getId();?>#stroke"
           value="<?php echo $element->getId();?>"
           <?php echo (!empty($element->getStroke())) ? 'checked' : '';?>
        >
</div>
