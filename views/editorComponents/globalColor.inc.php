<div class="row">
    <label class="col-md-4">1st color:</label>
    <div>
        <input id="primary-color"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#primary-color"
               value="<?php echo $element->getPrimaryColor()->getHex();?>"
               placeholder="color"
            />
    </div>
</div>
<div class="row">
    <label class="col-md-4">2nd color:</label>
    <div>
        <input id="secondary-color"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#secondary-color"
               value="<?php echo $element->getSecondaryColor()->getHex(); ?>"
               placeholder="color"
        />
    </div>
</div>

