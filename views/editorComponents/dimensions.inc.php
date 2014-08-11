<div class="col-md-2">
    <label>Width:</label>
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#width"
           value="<?php echo $element->getWidth();?>"
           placeholder="<?php echo $element->getWidth();?>"
        />
</div>
<div class="col-md-2">
    <label>Height:</label>
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#height"
           value="<?php echo $element->getHeight();?>"
           placeholder="<?php echo $element->getHeight();?>"
        />
</div>