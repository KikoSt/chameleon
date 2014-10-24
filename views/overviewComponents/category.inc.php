<div class="thumbnail thumbnail-half col-md-4">
    <div class="overviewTitle">
        Assigned categories
        <span id="editAssignedCategory"
              class="fa fa-pencil-square-o cursor-pointer"
              data-toggle="modal"
              data-target="#categorySelect-<?php echo $preview->templateId; ?>">
        </span>
    </div>
    <div id="categoryContainerOverview-<?php echo $preview->templateId; ?>" style="overflow-y: auto; max-height: 350px;">
        <?php
            foreach($preview->templateSubscription as $templateSubscription):
                if($templateSubscription->userStatus === 'ACTIVE'):
        ?>
                    <div id="assigned-<?php echo $templateSubscription->idCategory;?>" class="row">
                        <p class="text-left overviewTitle categoryItem"><?php echo $templateSubscription->categoryName;?></p>
                    </div>
        <?php
                endif;
            endforeach;
        ?>
    </div>
</div>