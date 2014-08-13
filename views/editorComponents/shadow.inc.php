<div class="col-md-2">
    <label>Activate ?</label>
    <div class="checkbox">
        <input id="shadowCheckBox"
               class="myCheckbox"
               type="checkbox"
               value="<?php echo $element->getId();?>"
               <?php echo (!empty($element->getShadowColor())) ? 'checked' : '';?>>
    </div>
</div>
<div class="col-md-2">
    <label>Shadow:</label>
    <input id="<?php echo $element->getId();?>_shadowColor"
           type="text"
           class="form-control picker"
           name="<?php echo $element->getId();?>#shadowColor"
           value="<?php echo (null !== $element->getShadowColor()) ? $element->getShadowColor()->getHex() : '';?>"
           placeholder="<?php echo (null !== $element->getShadowColor()) ? $element->getShadowColor()->getHex() : '';?>"
           <?php echo (empty($element->getShadowColor())) ? 'disabled' : '';?>
        />
</div>
<div class="col-md-2">
    <label>Shadow dist:</label>
    <input id="<?php echo $element->getId();?>_shadowDist"
           type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#shadowDist"
           value="<?php echo $element->getShadowDist();?>"
           placeholder="<?php echo $element->getShadowDist();?>"
           <?php echo (empty($element->getShadowColor())) ? 'disabled' : '';?>
        />
</div>



