<div class="row">
    <label class="col-md-4">CD Font:</label>
    <div>
        <select id="presetFontFamily"
                class="form-control"
                name="<?php echo $element->getId();?>#fontFamily">
            <?php
                foreach($this->fontlist as $key => $font):
                    $selected = (strcmp($element->getFontFamily(), $key)===0) ? "selected" : '';
            ?>
                <option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $font; ?></option>
            <?php
                endforeach;
            ?>
        </select>
    </div>
</div>
