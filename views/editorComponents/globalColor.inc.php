<div class="col-md-22">
    <div class="row">
    <label class="col-md-1 control-label">Primary color:</label>
        <input id="primary-color"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#primary-color"
               value="<?php echo $element->getPrimaryColor()->getHex();?>"
               placeholder="color"
               style="width:100px"
            />
    </div>
    <div class="row">
    <label class="col-md-1 control-label">Secondary color:</label>
        <input id="secondary-color"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#secondary-color"
               value="<?php echo $element->getSecondaryColor()->getHex(); ?>"
               placeholder="color"
               style="width:100px;"
            />
    </div>
</div>
