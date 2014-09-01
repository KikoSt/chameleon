<div class="col-md-12">
    <label class="col-md-1 control-label">Image:</label>
    <div class="col-md-11">
        <input id="<?php echo $element->getId();?>_input"
               type="file"
               class="file"
               name="<?php echo $element->getId();?>#imageUrl"
               value="test.try"
               data-show-upload="true"
               data-show-preview="false"
               placeholder="<?php echo $element->getImageUrl();?>"
            />
        <input type="hidden"
               class="form-control"
               name="<?php echo $element->getId();?>#imageUrl"
               value="<?php echo str_replace(ROOT_DIR, '', $element->getImageUrl());?>"
               placeholder="<?php echo $element->getImageUrl();?>"
            />
    </div>
</div>
