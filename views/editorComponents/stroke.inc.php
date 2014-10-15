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
           name="<?php echo $element->getId();?>#strokeColor"
           value="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getColor()->getHex() : '#000000';?>"
           placeholder="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getColor()->getHex() : '#000000';?>"
           <?php echo (empty($element->getStroke())) ? 'disabled' : '#000000';?>
        />
</div>
<div class="row">
    <label class="col-md-3 text-center" for="<?php echo $element->getId();?>_strokeWidth">Width:</label>
    <input id="<?php echo $element->getId();?>_strokeWidth"
           type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#strokeWidth"
           value="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getWidth() : '1';?>"
           placeholder="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getWidth() : '1';?>"
           <?php echo (empty($element->getStroke())) ? 'disabled' : '';?>
    />
</div>
<?php endif;?>
