<div class="col-md-12">
    <label for="<?php echo $element->getId();?>#text" class="col-md-1 control-label">Text:</label>
    <div class="col-md-10">
        <input id="<?php echo $element->getId();?>#text"
               type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#text"
               value="<?php echo $element->getText();?>"
               placeholder="<?php echo $element->getText();?>"
            />
    </div>
</div>





