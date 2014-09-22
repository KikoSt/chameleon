<div class="row">
    <label class="col-md-4">Primary color:</label>
    <div class=" col-md-8">
        <input id="primary-color"
               type="text"
               class="form-control picker col-md-8"
               name="<?php echo $element->getId();?>#primary-color"
               value="<?php echo $element->getPrimaryColor()->getHex();?>"
               placeholder="color"
               style="width:100px;"
            />
    </div>
</div>
<div class="row">
    <label class="col-md-4">Secondary color:</label>
    <div class=" col-md-8">
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

