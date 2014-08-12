<div class="col-md-6">
    <label>Image URL:</label>
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#imageUrl"
           value="<?php echo str_replace(ROOT_DIR, '', $element->getImageUrl());?>"
           placeholder="<?php echo $element->getImageUrl();?>"
        />
</div>
<div class="col-md-6">
    <label>Link URL:</label>
    <input type="text"
           class="form-control"
           name="<?php echo $element->getId();?>#imageUrl"
           value="<?php echo str_replace(ROOT_DIR, '', $element->getImageUrl());?>"
           placeholder="<?php echo $element->getImageUrl();?>"
        />
</div>
<div class="col-md-12">
    <label>Upload file:</label>
    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
    <input id="<?php echo $element->getId();?>" type="file" class="file" data-show-upload="false" data-show-preview="false" multiple>
</div>