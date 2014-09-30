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
                <input class="form-control" type="text" placeholder="select page category">
                <button style="color:#000000">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
            <div class="row">
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
                <button id="addCategory" style="color:#000000;">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
            <div class="row">
                <select class="form-control">
                    <option>select subcategory</option>
                    <?php
                        foreach($this->products as $product):
                    ?>
                            <option></option>
                    <?php
                        endforeach;
                    ?>
                </select>
                <button style="color:#000000">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </div>
            <?php
            foreach($this->storedCategories as $storedCategory):
                ?>
                <div id="<?php echo $storedCategory; ?>" class="row">
                    <?php echo $storedCategory; ?>
                    <button style="color:#000000">
                        <span class="glyphicon glyphicon-minus"></span>
                    </button>
                </div>
            <?php
            endforeach;
            ?>
        </div>
    </div>
</div>
