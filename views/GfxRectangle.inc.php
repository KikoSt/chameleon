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
            <?php
                include('editorComponents/color.inc.php');
                include('editorComponents/coords.inc.php');
                include('editorComponents/dimensions.inc.php');

                $shadowColor = $element->getShadowColor();
                $shadowDist = $element->getShadowDist();

                if(isset($shadowColor, $shadowDist))
                {
                    include('editorComponents/shadow.inc.php');
                }
            ?>
        </div>
    </div>
</div>
