<div class="col-md-2">
    <label>Color:</label>
    <input id="fill"
           type="text"
           class="form-control picker"
           name="<?php echo $element->getId();?>#fill"
           value="<?php echo $element->getFill()->getHex();?>"
           placeholder="<?php echo $element->getFill()->getHex();?>"
        />
</div>