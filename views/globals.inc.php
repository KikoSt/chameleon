<div class="panel panel-default globalBox" style="min-height: 350px;">
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
                <label class="col-md-4">File size:</label>
                <div>
                    <input type="text" disabled="disabled" value="<?php echo $this->fileSize;?> kB">
                </div>
            </div>
            <?php
                include('editorComponents/globalColor.inc.php');
                include('editorComponents/globalFont.inc.php');
            ?>
            <div id="global_categories" class="row">
                <label class="col-md-4" style="height: <?php echo count($this->activeCategories) * 22?>px;">
                    Categories:
                    <button id="editCategoriesEditor" type="button" class="btn btn-xs" data-toggle="modal" data-target="#categorySelect"
                            style="background-color: #7f7f7f; color: #FFFFFF;">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                </label>
                <div>
                    <?php
                        foreach($this->activeCategories as $activeCategory):
                    ?>
                    <input type="text" disabled="disabled" id="subscription_<?php echo $activeCategory['id']; ?>" value="<?php echo $activeCategory['name'];?>">
                    <?php
                        endforeach;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
