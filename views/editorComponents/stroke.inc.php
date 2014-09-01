<div class="col-md-2">
    <label for="strokeCheckBox" class="col-md-11">Enable stroke:</label>
    <div class="checkbox col-md-1">
        <input id="strokeCheckBox"
               class="myCheckbox"
               type="checkbox"
               value="<?php echo $element->getId();?>"
               <?php echo (!empty($element->getStroke())) ? 'checked' : '';?>>
    </div>
</div>
<div class="col-md-5">
    <label class="control-label col-md-3" for="<?php echo $element->getId();?>_strokeColor">Color:</label>
    <div class="col-md-9">
        <input id="<?php echo $element->getId();?>_strokeColor"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#stroke"
               value="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getColor()->getHex() : '';?>"
               placeholder="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getColor()->getHex() : '';?>"
               <?php echo (empty($element->getStroke())) ? 'disabled' : '';?>
            />
    </div>
</div>
<div class="col-md-5">
    <label class="control-label col-md-3" for="<?php echo $element->getId();?>_strokeColor">Width:</label>
    <div class="col-md-9">
        <input id="<?php echo $element->getId();?>_strokeWidth"
               type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#strokeWidth"
               value="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getWidth() : '';?>"
               placeholder="<?php echo (null !== $element->getStroke()) ? $element->getStroke()->getWidth() : '';?>"
               <?php echo (empty($element->getStroke())) ? 'disabled' : '';?>
        />
    </div>
</div>

