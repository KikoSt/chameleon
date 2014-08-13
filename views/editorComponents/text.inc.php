<div class="row">
    <div class="col-md-7">
        <label>Text:</label>
        <input type="text"
               class="form-control"
               name="<?php echo $element->getId();?>#text"
               value="<?php echo $element->getText();?>"
               placeholder="<?php echo $element->getText();?>"
            />
    </div>
    <div class="col-md-3">
        <label>Font family:</label>
        <select class="form-control"
                name="<?php echo $element->getId();?>#fontFamily">
            <?php
            foreach($this->fontlist as $key => $font):
                $selected = ($key === $element->getFontFamily()) ? "selected" : '';
                ?>
                <option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $font; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>