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
    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
        <div class="form-control" data-trigger="fileinput">
            <i class="glyphicon glyphicon-file fileinput-exists"></i>
            <span class="fileinput-filename"></span>
        </div>
        <span class="input-group-addon btn btn-default btn-file">
            <span class="fileinput-new">Select file</span>
            <span class="fileinput-exists">Change</span>
            <input type="file" name="...">
        </span>
        <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
    </div>
</div>