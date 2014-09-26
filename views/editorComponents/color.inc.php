<div class="row">
    <label class="col-md-4">Color:</label>
    <div>
        <input id="fill"
               type="text"
               class="form-control picker"
               name="<?php echo $element->getId();?>#fill"
               value="<?php echo $element->getFill()->getHex();?>"
               placeholder="color"
               style="float:left;"
            />
        <div class="text-right">
            <button class="btn-info" value="primary">1st</button>
            <button class="btn-success" value="secondary">2nd</button>
        </div>
    </div>

</div>
