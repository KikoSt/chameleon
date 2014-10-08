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
            $temp = $this->combinedCategories;

            foreach($this->combinedCategories as $combinedCategories):
                if($combinedCategories['status'] === "ACTIVE"):
                    ?>
                    <div id="row_<?php echo $combinedCategories['id']; ?>" class="row">
                        <div class="col-md-10">
                            <?php echo $combinedCategories['name']; ?>
                        </div>
                        <button id="<?php echo $combinedCategories['id']; ?>" class="removeCategory" type="button" style="color:#000000">
                            <span class="glyphicon glyphicon-minus"></span>
                        </button>
                    </div>
                <?php
                endif;
            endforeach;
        ?>
    </div>
</div>



