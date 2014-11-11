<div class="row">
    <input id="<?php echo $element->getId();?>_shadowCheckBox"
           class="myCheckbox"
           type="checkbox"
           name="<?php echo $element->getId();?>#shadow"
           value="<?php echo $element->getId();?>"
        <?php echo ($element->hasShadow()) ? 'checked' : '';?>

        >
    <label for="<?php echo $element->getId();?>_shadowCheckBox" class="col-md-4">Shadow:</label>
</div>
<?php if($this->premiumUser):?>
<div class="row">
    <label class="col-md-4 text-center" for="<?php echo $element->getId();?>_shadowColor">Color:</label>
    <input id="<?php echo $element->getId();?>_shadowColor"
           type="text"
           class="form-control picker cursor-pointer"
           name="<?php echo $element->getId();?>#shadowColor"
           value="<?php echo (null !== $element->getShadow()) ? $element->getShadow()->getColor()->getHex() : '#000000';?>"
           placeholder="<?php echo (null !== $element->getShadow()) ? $element->getShadow()->getColor()->getHex() : '#000000';?>"
           <?php echo (empty($element->getShadow())) ? 'disabled' : '#000000';?>
        />
</div>
<div class="row">
    <label class="col-md-4 text-center" for="<?php echo $element->getId();?>_shadowDist">Dist:</label>
    <input id="<?php echo $element->getId();?>_shadowDist"
           type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#shadowDist"
           value="<?php echo(null !== $element->getShadow() ?  $element->getShadow()->getDist() : '');?>"
           placeholder="<?php echo(null !== $element->getShadow() ?  $element->getShadow()->getDist() : '');?>"
           <?php echo (empty($element->getShadow())) ? 'disabled' : '';?>
        />
</div>
<?php endif;?>
