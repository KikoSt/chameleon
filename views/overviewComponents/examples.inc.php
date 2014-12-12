
    <div class="overviewTitle">Preview of existing creatives</div>
    <div id="creativesCarousel-<?php echo $preview->templateId; ?>" class="carousel slide" data-ride="carousel" style="margin-top: 10px;">
        <div id="previewcarousel-<?php echo $preview->templateId; ?>" class="carousel-inner ajaxPreview" style="min-height: 320px;">
            <!-- slides added via js --->
        </div>
        <div class="carousel-buttons">
            <div class="col-xs-2 carouselChevron">
                <a data-target="#creativesCarousel-<?php echo $preview->templateId; ?>" data-slide="prev" href="#" title="Show previous
            example">
                    <i class="fa fa-reply fa-2x"></i>
                </a>
            </div>
            <div class="col-xs-8 carouselChevron"></div>
            <div class="col-xs-2 carouselChevron">
                <a data-target="#creativesCarousel-<?php echo $preview->templateId; ?>" data-slide="next" href="#" title="Show next
            example">
                    <i class="fa fa-share fa-2x"></i>
                </a>
            </div>
        </div>
    </div>
