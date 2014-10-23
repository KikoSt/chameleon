<div class="overviewTitle">Facts</div>
<div>
    <ul class="list-group">
        <li class="list-group-item" style="border-bottom: 1px solid #e8e8e8;">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-left">Description:</p>
                </div>
                <div class="col-md-6">
                    <p class="text-left" title="<?php echo $preview->description;?>">
                        <?php echo $preview->shortDescription ;?>
                    </p>
                </div>
            </div>
        </li>
<!--        <li class="list-group-item">-->
<!--            <div class="row">-->
<!--                <div class="col-md-6">-->
<!--                    <p class="text-left">Filename:</p>-->
<!--                </div>-->
<!--                <div class="col-md-6">-->
<!--                    <p class="text-left">--><?php //echo $preview->templateName; ?><!--</p>-->
<!--                </div>-->
<!--            </div>-->
<!--        </li>-->
        <li class="list-group-item" style="border-bottom: 1px solid #e8e8e8;">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-left">Dimensions:</p>
                </div>
                <div class="col-md-6">
                    <p class="text-left"><?php echo $preview->bannerWidth . ' x ' . $preview->bannerHeight;?> px</p>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-left ">Approx. size:</p>
                </div>
                <div class="col-md-6">
                    <p class="text-left"><?php echo $preview->fileSize;?> kB</p>
                </div>
            </div>
        </li>
    </ul>
</div>
