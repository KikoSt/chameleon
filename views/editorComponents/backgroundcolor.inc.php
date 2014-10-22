<div class="row">
    <label class="col-md-3">Bg color:</label>
    <div>
        <input id="bgcolor"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#bgcolor"
               value="<?php echo (!empty($element->getBackgroundcolor()) ? $element->getBackgroundcolor()->getHex() : '#000000');?>"
               placeholder="color"
               style="float:left;"
            />
        <div id="<?php echo $element->getId();?>--preview" class="presetcolor" style="display: inline; float: left; background-color: <?php echo (!empty($element->getBackgroundcolor()) ? $element->getBackgroundColor()->getHex() : '#000000');?>;"></div>
        <button id="<?php echo $element->getId();?>--bgprimary" style="margin-left: 3px;" type="button" class="btn btn-xs preset presetcolor primary"></button>
        <button id="<?php echo $element->getId();?>--bgsecondary" type="button" class="btn btn-xs preset presetcolor secondary"></button>
    </div>
</div>
