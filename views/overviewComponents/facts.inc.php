<div style="margin-top: 50px;">Facts</div>
<div>
    <ul class="list-group">
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-left">Description:</p>
                </div>
                <div class="col-md-6">
                    <p class="text-left"><?php echo $preview->name;?></p>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-left">Filename:</p>
                </div>
                <div class="col-md-6">
                    <p class="text-left"><?php echo $preview->templateName; ?></p>
                </div>
            </div>
        </li>
        <li class="list-group-item">
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
                    <p class="text-left ">Size:</p>
                </div>
                <div class="col-md-6">
                    <p class="text-left"><?php echo $preview->fileSize;?> kb</p>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-left">Date created:</p>
                </div>
                <div class="col-md-6">
                    <p class="text-left"><?php echo $preview->dateCreate;?></p>
                </div>
            </div>
        </li>
        <li class="list-group-item">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-left">Date modified:</p>
                </div>
                <div class="col-md-6">
                    <p class="text-left"><?php echo $preview->dateModified;?></p>
                </div>
            </div>
        </li>
    </ul>
</div>
