<div class="col-md-2">
    <label>Shadow:</label>
    <input type="text"
           class="form-control picker"
           name="<?php echo $element->getId();?>#shadowColor"
           value="<?php echo $element->getShadowColor()->getHex();?>"
           placeholder="<?php echo $element->getShadowColor()->getHex();?>"
        />
</div>
<div class="col-md-2">
    <label>Shadow dist:</label>
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#shadowDist"
           value="<?php echo $element->getShadowDist();?>"
           placeholder="<?php echo $element->getShadowDist();?>"
        />
</div>

