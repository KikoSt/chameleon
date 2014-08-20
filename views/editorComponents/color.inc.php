<div class="col-md-3">
    <label class="col-md-4 control-label">Color:</label>
    <div class="col-md-8">
        <input id="fill"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#fill"
               value="<?php echo $element->getFill()->getHex();?>"
               placeholder="color"
            />
    </div>
</div>