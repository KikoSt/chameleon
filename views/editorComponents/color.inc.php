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
        <button id="<?php echo $element->getId();?>#primary" class="btn btn-xs btn-info preset presetcolor" style="margin-left: 3px;
        ">1st</button>
        <button id="<?php echo $element->getId();?>#secondary" class="btn btn-xs btn-success preset presetcolor">2nd</button>
    </div>
</div>
