<div class="thumbnail col-md-4">
    <div class="overviewTitle">Select your category or categories:</div>
    <div>
        <div class="row">
            <div class="col-md-5">
                <select multiple size="23">
                    <?php foreach($preview->categorySubscription as $category):?>
                        <option value="<?php echo $category->idCategory;?>"><?php echo $category->categoryName;?></option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-md-2">
                <button><i class="fa fa-angle-right"></i></button>
                <button><i class="fa fa-angle-left"></i></button>
            </div>
            <div class="col-md-5">
                <select multiple size="23">
                    <?php foreach($preview->templateSubscription as $category): var_dump($category);?>
                        <option value="<?php echo $category->idCategory;?>"><?php echo $category->categoryName;?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
    </div>
</div>