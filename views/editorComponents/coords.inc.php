<div class="row">
    <label class="col-md-4">X:</label>
    <div>
        <input type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#x"
               value="<?php echo $element->getX();?>"
               placeholder="<?php echo $element->getX();?>"
            />
    </div>
</div>
<div class="row">
    <label class="col-md-4">Y:</label>
    <div>
        <input type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#y"
               value="<?php echo $element->getY();?>"
               placeholder="<?php echo $element->getY();?>"
            />
    </div>
</div>