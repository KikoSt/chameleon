<div class="thumbnail thumbnail-quarter col-md-2">
    <div class="overviewTitle">Action:</div>

        <div class="row"
             style="margin-top: 120px;">
            <button id="cloneTemplate-<?php echo $preview->templateId; ?>-<?php echo $preview->advertiserId; ?>-<?php echo $preview->companyId; ?>"
                    class="cloneTemplate">
                <i class="fa fa-files-o fa-5x"></i>
            </button>
        </div>

    <form method="post" action="index.php?page=editor&templateId=<?php echo $preview->templateId; ?>">
        <div class="row">
            <button title="Edit the selected template"><i class="fa fa-pencil-square-o fa-5x"></i></button>
            <input type="hidden" name="templateId" value="<?php echo $preview->templateId; ?>">
            <input type="hidden" name="advertiserId" value="<?php echo $preview->advertiserId; ?>">
            <input type="hidden" name="companyId" value="<?php echo $preview->companyId; ?>">
            <input type="hidden" name="templateName" value="<?php echo $preview->templateName; ?>">
        </div>
    </form>
</div>