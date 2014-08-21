<div class="col-md-12">
    <label class="col-md-1 control-label">Color:</label>
    <div class="col-md-11">
        <input id="fill"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#fill"
               value="<?php echo $element->getFill()->getHex();?>"
               placeholder="color"
            />
    </div>
</div>
