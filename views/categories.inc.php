<div id="panel_globalsCategory" class="panel panel-default" style="min-height: 350px;display:none;">
    <div class="panel-heading globalsTitle">
        <h3 class="panel-title">
            Select categories:
            <span id="globalsCategory" class="glyphicon glyphicon-remove-circle" style="float:right;cursor:hand;"></span>
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10">
                    <input class="form-control" type="text" placeholder="select page category">
                </div>
                <button style="color:#000000">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
            <div class="row">
                <div class="col-md-10">
                    <select id="category" multiple="multiple" style="color:#000000">
                        <?php
                        foreach($this->categories as $category):
                            ?>
                            <option value="<?php echo $category->id;?>" title="<?php echo $category->name;?>">
                                <?php echo $category->name;?>
                            </option>
                        <?php
                        endforeach;
                        ?>
                    </select>
                </div>
                <button id="addCategory" type="button" style="color:#000000;">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
            <div class="row">
                <div class="col-md-10">
                    Assigned categories:
                </div>
            </div>
            <div id="categoryContainer">
                <?php
                    require_once('../ajax/categoriesSelection.inc.php');
                ?>
            </div>
            <div class="row">
                <div class="col-md-10"></div>
                <button id="saveCategory" type="submit" style="color:#000000">Save</button>
            </div>
        </div>
    </div>
</div>
