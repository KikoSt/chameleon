<div class="row">
    <label for="strokeCheckBox" class="col-md-4">Shadow:</label>
    <input id="<?php echo $element->getId();?>_shadow"
           class="myCheckbox"
           type="checkbox"
           name="<?php echo $element->getId();?>#shadow"
           value="<?php echo $element->getId();?>"
        <?php echo ($element->hasShadow()) ? 'checked' : '';?>

        >
</div>
