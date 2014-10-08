<div class="panel panel-default" style="min-height: 350px;">
    <div class="panel-heading globalsTitle">
        <h3 class="panel-title">
                Global settings for template:
        </h3>
    </div>
    <div id="globalsBody" class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <label class="col-md-4">Name:</label>
                <div class=" col-md-8">
                    <?php echo $this->name;?>
                </div>
            </div>
            <div class="row">
                <?php include('editorComponents/globalDimensions.inc.php'); ?>
            </div>
            <div class="row">
                <label class="col-md-4">Approx. size:</label>
                <div class=" col-md-8">
                    <?php echo $this->fileSize;?> kB
                </div>
            </div>
            <div class="row">
                <?php include('editorComponents/globalColor.inc.php'); ?>
            </div>
            <div class="row">
                <?php include('editorComponents/globalFont.inc.php'); ?>
            </div>
            <div class="row">
                <label class="col-md-4">Categories:</label>
                <div class=" col-md-8">
                    <?php
                        foreach($this->combinedCategories as $combinedCategories):
                            if($combinedCategories['status'] === "ACTIVE"):
                    ?>
                    <div class="row"><?php echo $combinedCategories['name'];?></div>
                    <?php
                            endif;
                        endforeach;
                    ?>
                    <button id="editCategoriesEditor" type="button" class="btn btn-xs" data-toggle="modal" data-target="#categorySelect"
                            style="background-color: #333333;">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
