<div class="thumbnail thumbnail-half col-md-4">
    <div class="overviewTitle">
        Assigned categories
        <span id="editAssignedCategory-<?php echo $preview->templateId; ?>"
              title="Edit the assigned categories"
              class="fa fa-pencil-square-o cursor-pointer color-black"
              data-toggle="modal"
              data-target="#categorySelect-<?php echo $preview->templateId; ?>">
        </span>
    </div>
    <div id="categoryContainerOverview-<?php echo $preview->templateId; ?>" style="overflow-y: auto; max-height: 350px;">
        <?php
            foreach($preview->templateSubscription as $templateSubscription):
                if($templateSubscription->userStatus === 'ACTIVE'):
        ?>
                    <div id="assigned-<?php echo $templateSubscription->idCategory;?>-<?php echo $preview->templateId; ?>" class="row">
                        <p class="text-left categoryItem">
                            <a id="cat-<?php echo $preview->templateId; ?>-<?php echo $templateSubscription->idCategory;?>" class="fa
                            fa-trash categoryItem cursor-pointer removeCategoryShortcut" title="Remove category"></a>
                            <?php echo $templateSubscription->categoryName;?>
                        </p>
                    </div>
                    <input id="<?php echo $templateSubscription->idCategory;?>"
                           type="hidden"
                           value="<?php echo $templateSubscription->categoryName;?>" >
        <?php
                endif;
            endforeach;
        ?>
    </div>
</div>
