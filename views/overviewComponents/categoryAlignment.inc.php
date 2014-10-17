<form method="post" action="#" id="<?php echo $preview->templateId; ?>">
<div class="thumbnail col-md-4">
    <div class="overviewTitle">Select your category or categories:</div>
    <div>
        <div class="row">
            <div class="col-md-5">
                <div class="overviewTitle">Available category</div>
                <select id="availableCategory" multiple size="21">
                    <?php foreach($preview->categorySubscription as $category):?>
                        <option value="<?php echo $category->idCategory;?>"
                                title="<?php echo $category->categoryName;?>">
                            <?php echo $category->categoryName;?>
                        </option>
                    <?php endforeach;?>
                </select>
            </div>
            <div class="col-md-2">
                <div class="row" style="margin-top: 120px;">
                    <button id="addCategory-<?php echo $preview->templateId; ?>-<?php echo $this->advertiserId; ?>"
                            class="addCategoryOverview"><i class="btn btn-lg fa
                    fa-angle-right"></i></button>
                </div>
                <div class="row">
                    <button id="removeCategory-<?php echo $preview->templateId; ?>-<?php echo $this->advertiserId; ?>"
                            class="removeCategoryOverview"><i class="btn
                    btn-lg fa
                    fa-angle-left"></i></button>
                </div>
            </div>
            <div class="col-md-5">
                <div class="overviewTitle">Assigned category</div>
                <select id="assignedCategory" multiple size="21">
                    <?php
                        foreach($preview->templateSubscription as $templateSubscription):
                            if($templateSubscription->userStatus === 'ACTIVE'):
                    ?>
                        <option value="<?php echo $templateSubscription->idCategory;?>"
                                title="<?php echo $templateSubscription->categoryName;?>">
                            <?php echo $templateSubscription->categoryName;?>
                        </option>
                    <?php
                            endif;
                        endforeach;
                    ?>
                </select>
            </div>
        </div>
    </div>
</div>
</form>