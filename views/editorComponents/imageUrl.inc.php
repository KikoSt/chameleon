
<div class="col-md-12">
    <label>Image:</label>
    <input id="<?php echo $element->getId();?>"
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
