<div class="row">
    <label for="strokeCheckBox" class="col-md-3">Shadow:</label>
    <input id="shadowCheckBox"
           class="myCheckbox"
           type="checkbox"
           name="<?php echo $element->getId();?>#shadow"
           value="<?php echo $element->getId();?>"
        <?php echo ($element->hasShadow()) ? 'checked' : '';?>

        >
</div>
<?php if($this->premiumUser):?>
<div class="row">
    <label class="col-md-3 text-center" for="<?php echo $element->getId();?>_shadowColor">Color:</label>
    <input id="<?php echo $element->getId();?>_shadowColor"
           type="text"
           class="form-control picker"
           name="<?php echo $element->getId();?>#shadowColor"
           value="<?php echo (null !== $element->getShadowColor()) ? $element->getShadowColor()->getHex() : '';?>"
           placeholder="<?php echo (null !== $element->getShadowColor()) ? $element->getShadowColor()->getHex() : '';?>"
           <?php echo (empty($element->getShadowColor())) ? 'disabled' : '';?>
        />
</div>
<div class="row">
    <label class="col-md-3 text-center" for="<?php echo $element->getId();?>_shadowDist">Dist:</label>
    <input id="<?php echo $element->getId();?>_shadowDist"
           type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#shadowDist"
           value="<?php echo $element->getShadowDist();?>"
           placeholder="<?php echo $element->getShadowDist();?>"
           <?php echo (empty($element->getShadowColor())) ? 'disabled' : '';?>
        />
</div>
<?php endif;?>
