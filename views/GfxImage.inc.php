<div class="panel panel-default">
    <div class="panel-heading imageTitle">
        <h3 class="panel-title">
            <a data-toggle="collapse" data-target="#<?php echo $element->getId();?>"
               href="#<?php echo $element->getId();?>">
                Image: <?php echo $element->getId();?>
            </a>
        </h3>
    </div>
    <div id="<?php echo $element->getId();?>" class="panel-collapse collapse"
    <div class="panel-body">
        <div class="row">
            <?php
                include('editorComponents/imageUrl.inc.php');
                include('editorComponents/coords.inc.php');
                include('editorComponents/dimensions.inc.php');
            ?>
        </div>
    </div>
</div>