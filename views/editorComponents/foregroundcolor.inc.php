<div class="row">
    <label class="col-md-4">Fg color:</label>
    <div>
        <input id="fgcolor"
               type="text"
               class="form-control picker small cursor-pointer"
               name="<?php echo $element->getId();?>#fgcolor"
               value="<?php echo (!empty($element->getForegroundcolor()) ? $element->getForegroundcolor()->getHex() : '#000000');?>"
               placeholder="color"
               style="float:left;"
            />
        <div id="<?php echo $element->getId();?>--preview" class="presetcolor" style="display: inline; float: left; background-color: <?php echo $element->getForegroundColor()->getHex();?>;"></div>
        <button id="<?php echo $element->getId();?>--fgprimary" style="margin-left: 3px;" type="button" class="btn btn-xs preset presetcolor primary"></button>
        <button id="<?php echo $element->getId();?>--fgsecondary" type="button" class="btn btn-xs preset presetcolor secondary"></button>
    </div>
</div>
