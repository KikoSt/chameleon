<div class="thumbnail col-md-4">
    <div class="overviewTitle">Preview of existing creatives</div>
    <div id="creativesCarousel" class="carousel slide" data-ride="carousel" style="margin-top: 10px;">
        <div class="carousel-buttons">
            <div class="col-xs-6 text-center carouselChevron">
                <a data-target="#creativesCarousel" data-slide="prev" href="#">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                </a>
            </div>
            <div class="col-xs-6 text-center carouselChevron">
                <a data-target="#creativesCarousel" data-slide="next" href="#">
                    <span class="glyphicon glyphicon-chevron-right">
                </a>
            </div>
        </div>
        <?php
            if(empty($preview->examples)):
        ?>
        <div>
            No examples !
        </div>
        <?php
            else:
        ?>
        <!-- Wrapper for slides -->
        <div class="carousel-inner" style="padding-left: 5px; padding-right: 5px;">
            <?php
                $active = true;
                foreach($preview->examples as $example):
            ?>
            <div class="item <?php echo ($active) ? 'active' : '';?>">
                <img src="<?php echo $example;?>"
                     alt="..."
                     style="max-height: 320px; display: block;margin: auto">
            </div>
            <?php
                $active = false;
                endforeach;
            ?>
        </div>
        <?php
            endif;
        ?>
    </div>


</div>