<div class="col-md-3">
    <label class="col-md-4 control-label">Width:</label>
    <div class="col-md-8">
        <input type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#width"
               value="<?php echo $element->getCanvasWidth();?>"
               placeholder="<?php echo $element->getCanvasWidth();?>"
            />
    </div>
</div>
<div class="col-md-3">
    <label class="col-md-4 control-label">Height:</label>
    <div class="col-md-8">
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#height"
           value="<?php echo $element->getCanvasHeight();?>"
           placeholder="<?php echo $element->getCanvasHeight();?>"
        />
    </div>
</div>
