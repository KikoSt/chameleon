<div class="row">
    <label class="col-md-3">Color:</label>
    <div>
        <input id="fill"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#fill"
               value="<?php echo (!empty($element->getFill()) ? $element->getFill()->getHex() : '#000000');?>"
               placeholder="color"
               style="float:left;"
            />
        <button id="<?php echo $element->getId();?>#primary" type="button" class="btn btn-xs btn-info preset presetcolor"
                style="margin-left: 3px;
        ">1st</button>
        <button id="<?php echo $element->getId();?>#secondary" type="button" class="btn btn-xs btn-success preset presetcolor">2nd</button>
    </div>
</div>
