<div class="panel panel-default globalBox" style="min-height: 150px;">
    <div class="panel-heading globalsTitle">
        <h3 class="panel-title">
                info and settings for this template:
        </h3>
    </div>
    <div id="globalsBody" class="panel-body">
        <div class="container-fluid">
                <?php
                    include('editorComponents/globalName.inc.php');
                    include('editorComponents/globalDimensions.inc.php');
                ?>
            <div class="row">
                <label class="col-md-4 filesize-label">GIF file size:</label>
                <div<?php if($this->gifFileSizeWarning) echo ' class="filesize-warning"'; ?>>
                    <input id="filesize-gif" <?php if($this->gifFileSizeWarning) echo 'class="filesize-warning"'; ?> type="text" disabled="disabled" value="<?php echo $this->gifFileSize;?> kB">
                </div>
            </div>
            <div class="row">
                <label class="col-md-4 filesize-label">SWF file size:</label>
                <div<?php if($this->swfFileSizeWarning) echo ' class="filesize-warning"'; ?>>
                    <input id="filesize-swf" <?php if($this->swfFileSizeWarning) echo 'class="filesize-warning"'; ?> type="text" disabled="disabled" value="<?php echo $this->swfFileSize;?> kB">
                </div>
            </div>
            <?php
                include('editorComponents/globalColor.inc.php');
                include('editorComponents/globalFont.inc.php');
            ?>
            <div id="categoryContainerOverview-<?php echo $this->templateId; ?> class="row">
                <?php // TODO: this is a dirty, dirty hack! ?>
                <label class="col-md-4" style="height: <?php echo count($this->activeCategories) * 19?>px;">
                    Categories:
                    <span id="editAssignedCategory-<?php echo $this->templateId; ?>"
                          title="Edit the assigned categories"
                          class="fa fa-pencil-square-o cursor-pointer"
                          data-toggle="modal"
                          data-target="#categorySelect-<?php echo $this->templateId; ?>">
                    </span>
                </label>
                <div>
                    <?php
                        foreach($this->activeCategories as $activeCategory):
                    ?>
                            <div id="assigned-<?php echo $activeCategory['id'];?>-<?php echo $this->templateId; ?>" class="row">
                                <p class="text-left categoryItem">
                                    <a id="cat-<?php echo $this->templateId; ?>-<?php echo $activeCategory['id'];?>" class="fa
                            fa-trash categoryItem cursor-pointer removeCategoryShortcut" title="Remove category"></a>
                                    <?php echo $activeCategory['name'];?>
                                </p>
                            </div>
                    <?php
                        endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
