<div class="panel panel-default">
    <a data-toggle="collapse" data-target=""
       href="#<?php echo $element->getId();?>">
    <div class="panel-heading rectangleTitle">
        <h3 class="panel-title">
                Rectangle: <?php echo str_replace('_', ' ', $element->getId());?>
        </h3>
    </div>
    </a>
    <div id="<?php echo $element->getId();?>" class="panel-collapse collapse"
    <div class="panel-body">
        <div class="container-fluid">
            <div class="row">
                <?php include('editorComponents/color.inc.php'); ?>
            </div>
            <div class="row">
                <?php
                include('editorComponents/dimensions.inc.php');
                include('editorComponents/coords.inc.php');
                ?>
            </div>
            <div class="row">

            </div>
            <div class="row">
                <?php include('editorComponents/cmeo.inc.php'); ?>
            </div>
            <div class="row">
                <?php include('editorComponents/shadow.inc.php'); ?>
            </div>
            <div class="row">
                <?php include('editorComponents/stroke.inc.php'); ?>
            </div>
        </div>
    </div>
</div>
