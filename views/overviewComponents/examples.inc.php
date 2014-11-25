
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
        <div class="carousel-inner">
            <?php
                $active = true;
                foreach($preview->examples as $example):
            ?>
            <div class="item<?php echo ($active) ? ' active' : '';?>">
                <img src="<?php echo $example;?>"
                     alt="..."
                     style="max-height: 325px;">
            </div>
            <?php
                $active = false;
                endforeach;
            ?>
        </div>
        <div class="carousel-buttons">
            <div class="col-xs-6 text-center carouselChevron">
                <a data-target="#creativesCarousel-<?php echo $preview->templateId; ?>" data-slide="prev" href="#">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
            </div>
            <div class="col-xs-6 text-center carouselChevron">
                <a data-target="#creativesCarousel-<?php echo $preview->templateId; ?>" data-slide="next" href="#">
                    <span class="glyphicon glyphicon-chevron-right">
                </a>
            </div>
        </div>
        <?php
            endif;
        ?>

    </div>
