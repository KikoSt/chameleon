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
        <div id="<?php echo $element->getId();?>--preview" class="presetcolor" style="display: inline; float: left; background-color: <?php echo $element->getFill()->getHex();?>;"></div>
        <button id="<?php echo $element->getId();?>--primary" style="margin-left: 3px;" type="button" class="btn btn-xs preset presetcolor
        primary"></button>
        <button id="<?php echo $element->getId();?>--secondary" type="button" class="btn btn-xs preset presetcolor secondary"></button>
    </div>
</div>
