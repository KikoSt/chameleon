<div class="col-md-2">
    <label>Fill:</label>
    <input id="fill"
           type="text"
           class="form-control picker"
           name="<?php echo $element->getId();?>#fill"
           value="<?php echo $element->getFill()->getHex();?>"
           placeholder="<?php echo $element->getFill()->getHex();?>"
        />
</div>
<?php if(null !== $element->getStroke()): ?>
<div class="col-md-2">
    <label>Stroke:</label>
    <input id="stroke"
           type="text"
           class="form-control picker"
           name="<?php echo $element->getId();?>#stroke"
           value="<?php echo $element->getStroke()->getColor()->getHex();?>"
           placeholder="<?php echo $element->getStroke()->getColor()->getHex();?>"
        />
</div>
<?php endif; ?>