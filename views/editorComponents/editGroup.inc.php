<div class="row">
    <label class="col-md-4">Group:</label>
    <div>
        <input id="<?php echo $element->getId();?>#editGroup"
               type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#editGroup"
               value="<?php echo $element->getEditGroup();?>"
               placeholder="<?php echo (int) $element->getEditGroup();?>"
               style="float:left;"
            />
    </div>

</div>


