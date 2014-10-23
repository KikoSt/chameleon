<div class="thumbnail thumbnail-half col-md-4">
    <div class="overviewTitle">
        Assigned categories
        <button id="editCategoriesEditor" type="button" class="btn btn-xs" data-toggle="modal" data-target="#categorySelect-<?php echo $preview->templateId; ?>">
            <span class="glyphicon glyphicon-pencil"></span>
        </button>
    </div>
        <?php
            foreach($preview->templateSubscription as $templateSubscription):
                if($templateSubscription->userStatus === 'ACTIVE'):
        ?>
                <div class="row">
                    <p class="text-left" style="word-wrap: break-word;"><?php echo $templateSubscription->categoryName;?></p>
                </div>
        <?php
                endif;
            endforeach;
        ?>
</div>