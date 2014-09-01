<div class="col-md-3">
    <label class="col-md-4 control-label">Width:</label>
    <div class="col-md-8">
        <input type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#width"
               value="<?php echo $element->getWidth();?>"
               placeholder="<?php echo $element->getWidth();?>"
            />
    </div>
</div>
<div class="col-md-3">
    <label class="col-md-4 control-label">Height:</label>
    <div class="col-md-8">
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#height"
           value="<?php echo $element->getHeight();?>"
           placeholder="<?php echo $element->getHeight();?>"
        />
    </div>
</div>