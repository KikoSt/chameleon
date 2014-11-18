<div class="row">
    <label class="col-md-4">Font:</label>
    <div>
        <select class="form-control-select font-select"
                id="<?php echo $element->getId();?>_fontFamily"
                name="<?php echo $element->getId();?>#fontFamily">
            <?php
                foreach($this->fontlist as $key => $font):
                $selected = ($key === $element->getFontFamily()) ? "selected" : '';
            ?>
                <option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $font; ?></option>
            <?php endforeach; ?>
        </select>
        <button id="<?php echo $element->getId();?>#presetFont" type="button" class="btn btn-xs btn-info preset presetfont"
                style="margin-left:3px;">CD font</button>
    </div>
</div>
