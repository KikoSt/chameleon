<div class="col-md-2">
    <label>Shadow:</label>
    <input type="text"
           class="form-control picker"
           name="<?php echo $element->getId();?>#strokeColor"
           value="<?php echo $element->getStroke()->getColor()->getHex();?>"
           placeholder="<?php echo $element->getStroke()->getColor()->getHex();?>"
        />
</div>
<div class="col-md-2">
    <label>Shadow dist:</label>
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#strokeWidth"
           value="<?php echo $element->getStroke()->getColor()->getWidth();?>"
           placeholder="<?php echo $element->getStroke()->getColor()->getWidth();?>"
        />
</div>