<div class="container-fluid">
    <div class="row">
        <div class="col-md-10">
            <input class="form-control" type="text" placeholder="select page category">
        </div>
        <div>
            <button class="modalBtn">
                <span class="glyphicon glyphicon-plus"></span>
            </button>
        </div>
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
            foreach($this->activeCategories as $activeCategory):
        ?>
            <div id="row_<?php echo $activeCategory['id']; ?>" class="row">
                <div class="col-md-10">
                    <input type="text" disabled="disabled" value="<?php echo $activeCategory['name']; ?>"/>
                </div>
                <button id="<?php echo $activeCategory['id']; ?>" class="removeCategory" type="button" style="color:#000000">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </div>
        <?php
            endforeach;
        ?>
    </div>
</div>



