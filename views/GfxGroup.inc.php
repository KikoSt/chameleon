<div id="grouppanel_<?php echo $element->getId();?>" class="panel panel-default component">
    <div class="panel-heading groupTitle">
        <h3 class="panel-title">
            Group: <?php echo str_replace('_', ' ', $element->getId());?>
            <span id="<?php echo $element->getId();?>" class="glyphicon glyphicon-remove-circle"></span>
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
        <?php
            if($this->premiumUser):
                include('editorComponents/coords.inc.php');
            endif;
            include('editorComponents/foregroundcolor.inc.php');
            include('editorComponents/backgroundcolor.inc.php');
            include('editorComponents/text.inc.php');
            include('editorComponents/fontFamily.inc.php');
            // include('editorComponents/groupSource.inc.php');
            include('editorComponents/groupLink.inc.php');
        ?>
        </div>
    </div>
</div>
