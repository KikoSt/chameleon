<div class="row">
    <label class="col-md-4">1st color:</label>
    <input id="primary-color"
           type="text"
           class="form-control picker globalColor cursor-pointer"
           name="<?php echo $element->getId();?>#primary-color"
           value="<?php echo $element->getPrimaryColor()->getHex();?>"
           placeholder="color"
           style="display: inline; float:left;"
        />
    <div id="primary-color--preview" class="presetcolor"
         style="display:inline; float:left; background-color: <?php echo $element->getPrimaryColor()->getHex();?>;">
    </div>
</div>
<div class="row">
    <label class="col-md-4">2nd color:</label>
    <input id="secondary-color"
           type="text"
           class="form-control picker globalColor cursor-pointer"
           name="<?php echo $element->getId();?>#secondary-color"
           value="<?php echo $element->getSecondaryColor()->getHex(); ?>"
           placeholder="color"
           style="display: inline; float:left;"
    />
    <div id="secondary-color--preview" class="presetcolor"
         style="display: inline; float:left; background-color: <?php echo $element->getSecondaryColor()->getHex();?>;">
    </div>
</div>

