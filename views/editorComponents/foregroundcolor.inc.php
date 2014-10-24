<div class="row">
    <label class="col-md-3">Fg color:</label>
    <div>
        <input id="fgcolor"
               type="text"
               class="form-control picker cursor-pointer"
               name="<?php echo $element->getId();?>#fgcolor"
               value="<?php echo (!empty($element->getForegroundcolor()) ? $element->getForegroundcolor()->getHex() : '#000000');?>"
               placeholder="color"
               style="float:left;"
            />
        <button id="<?php echo $element->getId();?>#primary" type="button" class="btn btn-xs btn-info preset presetcolor"
                style="margin-left: 3px;
        ">1st</button>
        <button id="<?php echo $element->getId();?>#secondary" type="button" class="btn btn-xs btn-success preset presetcolor">2nd</button>
    </div>
</div>
