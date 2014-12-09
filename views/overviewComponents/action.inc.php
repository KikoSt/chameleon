<div class="thumbnail thumbnail-quarter col-md-2">
    <div class="overviewTitle">Actions:</div>
    <div id="actionsContainer">
        <div class="row">
            <form method="post" action="index.php?page=editor&amp;templateId=<?php echo $preview->templateId; ?>">
                    <button title="Edit this template">
                        <i class="fa fa-pencil-square-o fa-5_3x"></i>
                    </button>
            </form>
        </div>
        <div class="row">
            <button id="cloneTemplate-<?php echo $preview->templateId; ?>"
                    class="cloneTemplate smallActionButton"
                    title="Clone this template">
                <i class="fa fa-files-o fa-2x"></i>
            </button>
            <button id="deleteTemplate-<?php echo $preview->templateId; ?>"
                    class="deleteTemplate smallActionButton"
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
</div>
