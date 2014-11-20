<div class="row" style="max-height: 150px;bottom:0;">
    <div class="overviewTitle">Facts</div>
    <div style="max-height: 130px;">
        <ul class="list-group">
            <li class="list-group-item" style="border-bottom: 1px solid #e8e8e8;">
                <div class="row">
                    <div class="col-md-4">
                        <p class="text-left">Description:</p>
                    </div>
                    <div class="col-md-8">
                        <p id="name-<?php echo $preview->templateId; ?>" class="text-left name" title="<?php echo $preview->description;?>">
                            <?php echo $preview->shortDescription ;?>
                        </p>
                    </div>
                </div>
            </li>
            <li class="list-group-item" style="border-bottom: 1px solid #e8e8e8;">
                <div class="row">
                    <div class="col-md-4">
                        <p class="text-left">Dimensions:</p>
                    </div>
                    <div class="col-md-8">
                        <p class="text-left"><?php echo $preview->bannerDimension->name;?><br/><?php echo $preview->bannerDimension->width . ' x
                        ' . $preview->bannerDimension->height;?> px</p>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-4">
                        <p class="text-left ">Approx. size:</p>
                    </div>
                    <div class="col-md-8">
                        <p class="text-left"><?php echo $preview->fileSize;?> kB</p>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
