<div class="row">
    <input id="<?php echo $element->getId();?>_strokeCheckBox"
           class="myCheckbox"
           type="checkbox"
           name="<?php echo $element->getId();?>#stroke"
           value="<?php echo $element->getId();?>"
           <?php echo (!empty($element->getStroke())) ? 'checked' : '';?>
        >
    <label for="<?php echo $element->getId();?>_strokeCheckBox" class="col-md-3">Stroke:</label>
</div>
<?php if($this->premiumUser):?>
<div class="row">
    <input id="<?php echo $element->getId();?>_strokeColor"
           type="text"
           class="form-control picker cursor-pointer"
           name="<?php echo $element->getId();?>#strokeColor"
           value="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getColor()->getHex() : '#000000';?>"
           placeholder="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getColor()->getHex() : '#000000';?>"
           <?php echo (empty($element->getStroke())) ? 'disabled' : '#000000';?>
        />
    <label class="col-md-3 text-center" for="<?php echo $element->getId();?>_strokeColor">Color:</label>
</div>
<div class="row">
    <input id="<?php echo $element->getId();?>_strokeWidth"
           type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#strokeWidth"
           value="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getWidth() : '1';?>"
           placeholder="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getWidth() : '1';?>"
           <?php echo (empty($element->getStroke())) ? 'disabled' : '';?>
    />
    <label class="col-md-3 text-center" for="<?php echo $element->getId();?>_strokeWidth">Width:</label>
</div>
<?php endif;?>
