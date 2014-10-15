<div class="row">
    <label for="strokeCheckBox" class="col-md-3">Stroke:</label>
    <input id="strokeCheckBox"
           class="myCheckbox"
           type="checkbox"
           name="<?php echo $element->getId();?>#stroke"
           value="<?php echo $element->getId();?>"
           <?php echo (!empty($element->getStroke())) ? 'checked' : '';?>
        >
</div>
<?php if($this->premiumUser):?>
<div class="row">
    <label class="col-md-3 text-center" for="<?php echo $element->getId();?>_strokeColor">Color:</label>
    <input id="<?php echo $element->getId();?>_strokeColor"
           type="text"
           class="form-control picker"
           name="<?php echo $element->getId();?>#stroke"
           value="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getColor()->getHex() : '';?>"
           placeholder="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getColor()->getHex() : '';?>"
           <?php echo (empty($element->getStroke())) ? 'disabled' : '';?>
        />
</div>
<div class="row">
    <label class="col-md-3 text-center" for="<?php echo $element->getId();?>_strokeColor">Width:</label>
    <input id="<?php echo $element->getId();?>_strokeWidth"
           type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#strokeWidth"
           value="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getWidth() : '';?>"
           placeholder="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getWidth() : '';?>"
           <?php echo (empty($element->getStroke())) ? 'disabled' : '';?>
    />
</div>
<?php endif;?>
