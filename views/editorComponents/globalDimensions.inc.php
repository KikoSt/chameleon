<div class="row">
    <label class="col-md-4">Dimensions:</label>
    <div>
        <select class="form-control-select"
                name="<?php echo $element->getId();?>#globalDimensions">
            <?php
                $selected = false;
                foreach($this->allowedDimensions as $dimension):
                    $selected = ($dimension->height === $template->getDimY() && $dimension->width === $template->getDimX() ?
                        "selected" : "");
                    if($dimension->width > 0 && $dimension->height > 0):
            ?>
                <option value="<?php echo $dimension->width; ?>x<?php echo $dimension->height; ?>" <?php echo $selected;?>><?php echo $dimension->width; ?>x<?php echo $dimension->height; ?> (<?php echo $dimension->name; ?>)</option>
            <?php
                    endif;
                endforeach;
            ?>
        </select>
    </div>
</div>
