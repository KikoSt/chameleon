<div class="row">
    <label class="col-md-4">Font:</label>
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
