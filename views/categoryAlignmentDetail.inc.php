<form method="post" id="<?php echo $templateId; ?>">
<div>
    <div class="row" style="margin-top: 8px;">
        <div class="col-md-5" style="margin-left: 11px;">
            <div class="overviewTitle">Available</div>
            <select id="availableCategory-<?php echo $templateId; ?>" multiple size="20">
                <?php foreach($availableCategories as $key => $category):?>
                    <option value="<?php echo $key;?>"
                            title="<?php echo $category;?>">
                        <?php echo $category;?>
                    </option>
                <?php endforeach;?>
            </select>
        </div>
        <div class="col-md-1" style="margin-right: 10px; margin-left: 10px;">
            <div class="row" style="margin-top: 120px;">
                <button id="addCategory-<?php echo $templateId; ?>"
                        class="addCategoryOverview"><i class="fa fa-angle-right fa-3x"></i></button>
            </div>
            <div class="row">
                <button id="removeCategory-<?php echo $templateId; ?>"
                        class="removeCategoryOverview"><i class="fa fa-angle-left fa-3x"></i></button>
            </div>
        </div>
        <div class="col-md-5">
            <div class="overviewTitle">Assigned</div>
            <select id="assignedCategory-<?php echo $templateId; ?>" multiple size="20">
                <?php
                    foreach($templateSubscriptions as $singleSubscription):
                        if($singleSubscription->userStatus === 'ACTIVE'):
                ?>
                    <option value="<?php echo $singleSubscription->idCategory;?>"
                            title="<?php echo $singleSubscription->categoryName;?>">
                        <?php echo $singleSubscription->categoryName;?>
                    </option>
                <?php
                        endif;
                    endforeach;
                ?>
            </select>
        </div>
    </div>
</div>
</form>

