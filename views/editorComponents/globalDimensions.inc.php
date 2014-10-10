<div class="row">
    <label class="col-md-4">Width:</label>
    <div>
        <input type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#width"
               value="<?php echo $element->getCanvasWidth();?>"
               placeholder="<?php echo $element->getCanvasWidth();?>"
            />
    </div>
</div>
<div class="row">
    <label class="col-md-4">Height:</label>
    <div>
        <input type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#height"
               value="<?php echo $element->getCanvasHeight();?>"
               placeholder="<?php echo $element->getCanvasHeight();?>"
            />
    </div>
</div>
