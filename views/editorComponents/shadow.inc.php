<div class="col-md-2">
    <label for="strokeCheckBox" class="col-md-11">Enable shadow:</label>
    <div class="checkbox col-md-1">
        <input id="shadowCheckBox"
               class="myCheckbox"
               type="checkbox"
               value="<?php echo $element->getId();?>"
               <?php echo (!empty($element->getShadowColor())) ? 'checked' : '';?>>
    </div>
</div>
<div class="col-md-5">
    <label class="control-label col-md-3" for="<?php echo $element->getId();?>_shadowColor">Color:</label>
    <div class="col-md-9">
        <input id="<?php echo $element->getId();?>_shadowColor"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#shadowColor"
               value="<?php echo (null !== $element->getShadowColor()) ? $element->getShadowColor()->getHex() : '';?>"
               placeholder="<?php echo (null !== $element->getShadowColor()) ? $element->getShadowColor()->getHex() : '';?>"
               <?php echo (empty($element->getShadowColor())) ? 'disabled' : '';?>
            />
    </div>
</div>
<div class="col-md-5">
    <label class="control-label col-md-3" for="<?php echo $element->getId();?>_shadowDist">Dist:</label>
    <div class="col-md-9">
    <input id="<?php echo $element->getId();?>_shadowDist"
           type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#shadowDist"
           value="<?php echo $element->getShadowDist();?>"
           placeholder="<?php echo $element->getShadowDist();?>"
           <?php echo (empty($element->getShadowColor())) ? 'disabled' : '';?>
        />
    </div>
</div>



