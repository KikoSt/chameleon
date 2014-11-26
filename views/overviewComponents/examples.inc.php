
    <div class="overviewTitle">Preview of existing creatives</div>
    <div id="creativesCarousel-<?php echo $preview->templateId; ?>" class="carousel slide" data-ride="carousel" style="margin-top: 10px;">
        <?php
            if(empty($preview->examples)):
        ?>
        <div id="previewcarousel-<?php echo $preview->templateId; ?>" class="carousel-inner ajaxPreview">

        </div>
        <?php
            else:
        ?>
        <!-- Wrapper for slides -->
        <div class="carousel-inner" style="min-height: 320px;">
            <?php
                $active = true;
                foreach($preview->examples as $example):
            ?>
            <div class="item<?php echo ($active) ? ' active' : '';?>">
                <img src="<?php echo $example;?>"
                     alt="..."
                     style="max-height: 320px;">
            </div>
            <?php
                $active = false;
                endforeach;
            ?>
        </div>
        <div class="carousel-buttons">
            <div class="col-xs-4 text-center carouselChevron"></div>
            <div class="col-xs-4 text-center carouselChevron">
                <a data-target="#creativesCarousel-<?php echo $preview->templateId; ?>" data-slide="prev" href="#" title="Show previous
                example">
                    <i class="fa fa-reply fa-2x"></i>
                </a>
                <a data-target="#creativesCarousel-<?php echo $preview->templateId; ?>" data-slide="next" href="#" title="Show next
                example">
                    <i class="fa fa-share fa-2x"></i>
                </a>
            </div>
            <div class="col-xs-4 text-center carouselChevron"></div>
        </div>
        <?php
            endif;
        ?>

    </div>
