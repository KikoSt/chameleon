<div class="row">
    <label class="col-md-3">Color:</label>
    <div>
        <input id="fill"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#fill"
               value="<?php echo $element->getFill()->getHex();?>"
               placeholder="color"
               style="float:left;"
            />
        <button id="<?php echo $element->getId();?>--primary" type="button" class="btn btn-xs preset presetcolor primary"
                style="margin-left: 3px;
        "></button>
        <button id="<?php echo $element->getId();?>--secondary" type="button" class="btn btn-xs preset presetcolor secondary"></button>
    </div>
</div>
