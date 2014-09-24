<label class="col-md-6">Font:</label>
<div class="col-md-6">
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
