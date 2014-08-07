<div class="col-md-2">
    <label>X:</label>
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#x"
           value="<?php echo $element->getX();?>"
           placeholder="<?php echo $element->getX();?>"
        />
</div>
<div class="col-md-2">
    <label>Y:</label>
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#y"
           value="<?php echo $element->getY();?>"
           placeholder="<?php echo $element->getY();?>"
        />
</div>