<div class="thumbnail thumbnail-quarter col-md-2">
    <div class="overviewTitle">Actions:</div>
    <div class="row" style="margin-top: 10px;">
        <form method="post" action="index.php?page=editor&amp;templateId=<?php echo $preview->templateId; ?>">
            <div class="row">
                <button title="Edit this template">
                    <i class="fa fa-pencil-square-o fa-5_3x"></i>
                </button>
                <?php // most likely, those are unused: ?>
                <input type="hidden" name="templateId" value="<?php echo $preview->templateId; ?>">
                <input type="hidden" name="advertiserId" value="<?php echo $preview->advertiserId; ?>">
                <input type="hidden" name="companyId" value="<?php echo $preview->companyId; ?>">
                <input type="hidden" name="templateName" value="<?php echo $preview->templateName; ?>">
            </div>
        </form>
    </div>
    <div class="row">
        <button id="cloneTemplate-<?php echo $preview->templateId; ?>"
                class="cloneTemplate"
                title="Clone this template">
            <i class="fa fa-files-o fa-2x"></i>
        </button>
        <button id="deleteTemplate-<?php echo $preview->templateId; ?>"
                class="deleteTemplate"
                title="Delete this template">
            <i class="fa fa-trash fa-2x"></i>
        </button>
    </div>
    <div class="row">
        <button id="createCreatives-<?php echo $preview->templateId; ?>-<?php echo $preview->advertiserId; ?>-<?php echo
        $preview->companyId; ?>"
                class="createCreatives"
                title="create banners based on this template">
            <i class="fa fa-caret-square-o-down fa-5_3x"></i>
        </button>
    </div>
</div>
