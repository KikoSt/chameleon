<div class="row">
    <label class="col-md-4">Animation:</label>
    <div>
        <input id="<?php echo $element->getId();?>#animation"
               type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#animation"
               value="<?php echo $element->serializeAnimations();?>"
               placeholder="<?php echo $element->serializeAnimations();?>"
               style="float:left;"
            />
    </div>

</div>

