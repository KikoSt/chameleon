<div class="panel panel-default">
    <div class="panel-heading rectangleTitle">
        <h3 class="panel-title">
            <a data-toggle="collapse" data-target="#<?php echo $element->getId();?>"
               href="#<?php echo $element->getId();?>">
                Rectangle: <?php echo $element->getId();?>
            </a>
        </h3>
    </div>
    <div id="<?php echo $element->getId();?>" class="panel-collapse collapse"
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2">
                <label>Fill:</label>
                <input id="fill"
                       type="text"
                       class="form-control picker"
                       name="<?php echo $element->getId();?>#fill"
                       value="<?php echo $element->getFill()->getHex();?>"
                       placeholder="<?php echo $element->getFill()->getHex();?>"
                    />
            </div>
            <?php if(null !== $element->getStroke()): ?>

            <?php endif; ?>
            <?php
                if(null !== $element->getShadowColor()):
                    include('subviews/shadowColor.inc.php');
                endif;
                include('subviews/xcoord.inc.php');
                include('subviews/ycoord.inc.php');
                include('subviews/width.inc.php');
                include('subviews/height.inc.php');
            ?>
        </div>
    </div>
</div>
