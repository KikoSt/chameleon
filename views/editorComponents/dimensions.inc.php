<div class="row">
    <label class="col-md-3">Width:</label>
    <div>
        <input type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#width"
               value="<?php echo $element->getWidth();?>"
               placeholder="<?php echo $element->getWidth();?>"
            />
    </div>
</div>
<div class="row">
    <label class="col-md-3">Height:</label>
    <div>
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#height"
           value="<?php echo $element->getHeight();?>"
           placeholder="<?php echo $element->getHeight();?>"
        />
    </div>
</div>
