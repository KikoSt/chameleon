<div class="container-fluid">
    <div class="row">
        <div class="col-md-10">
            <select id="category" multiple="multiple" class="color-black">
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
        <button id="addCategory" type="button" class="btn btn-success addCategoryEditor color-black">
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
                <button id="<?php echo $activeCategory['id']; ?>" class="btn btn-danger removeCategoryEditor color-black" type="button">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
            </div>
        <?php
            endforeach;
        ?>
    </div>
</div>



