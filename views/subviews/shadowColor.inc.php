<div class="col-md-2">
    <label>Shadow:</label>
    <input id="stroke"
           type="text"
           class="form-control picker"
           name="<?php echo $element->getId();?>#stroke"
           value="<?php echo $element->getShadowColor()->getHex();?>"
           placeholder="<?php echo $element->getShadowColor()->getHex();?>"
        />
</div>