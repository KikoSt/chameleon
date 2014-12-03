<div class="modal fade" id="categorySelect-<?php echo $templateId; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header imageTitle">
                <button type="button" class="close" data-dismiss="modal">
                    <span class="glyphicon glyphicon-remove-circle cursor-pointer"></span>
                </button>
                <h4 class="modal-title" id="myModalLabel">Select categories:</h4>
            </div>
            <div class="modal-body">
                <?php include('categoryAlignmentDetail.inc.php'); ?>
            </div>
        </div>
    </div>
</div>