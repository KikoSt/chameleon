<div class="row">
    <label class="col-md-4">Font size:</label>
    <div>
        <select class="form-control-select font-size-select"
                id="<?php echo $element->getId();?>_fontSize"
                name="<?php echo $element->getId();?>#fontSize">
            <?php
                foreach($this->fontsizeList as $size):
                $selected = ((int)$size === (int)$element->getFontsize()) ? "selected" : '';
            ?>
                <option value="<?php echo $size; ?>" <?php echo $selected;?>><?php echo $size; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

