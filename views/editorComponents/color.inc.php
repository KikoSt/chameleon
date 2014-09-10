<div class="col-md-12">
    <label class="col-md-1 control-label">Color:</label>
    <div class="col-md-11">
        <input id="fill"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#fill"
               value="<?php echo $element->getFill()->getHex();?>"
               placeholder="color"
               style="width:100px;"
            />
            <!-- colorpicker('setValue', value) here -->
            <input type="button" value="primary" />
            <input type="button" value="secondary" />
    </div>
</div>
