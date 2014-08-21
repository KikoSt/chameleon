<div class="col-md-3">
    <label class="col-md-4 control-label">X:</label>
    <div class="col-md-8">
        <input type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#x"
               value="<?php echo $element->getX();?>"
               placeholder="<?php echo $element->getX();?>"
            />
    </div>
</div>
<div class="col-md-3">
    <label class="col-md-4 control-label">Y:</label>
    <div class="col-md-8">
        <input type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#y"
               value="<?php echo $element->getY();?>"
               placeholder="<?php echo $element->getY();?>"
            />
    </div>
</div>