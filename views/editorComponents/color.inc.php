<div class="row">
    <label class="col-md-4">Color:</label>
    <div>
        <input id="fill"
               type="text"
               class="form-control picker small"
               name="<?php echo $element->getId();?>#fill"
               value="<?php echo (!empty($element->getFill()) ? $element->getFill()->getHex() : '#000000');?>"
               placeholder="color"
               style="float:left;"
            />
        <div id="<?php echo $element->getId();?>--preview" class="presetcolor" style="background-color: <?php echo $element->getFill()->getHex();?>;"></div>
        <button id="<?php echo $element->getId();?>--primary" type="button" class="btn btn-xs preset presetcolor primary"></button>
        <button id="<?php echo $element->getId();?>--secondary" type="button" class="btn btn-xs preset presetcolor secondary"></button>
    </div>
</div>
