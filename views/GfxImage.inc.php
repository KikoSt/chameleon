<div id="panel_<?php echo $element->getId();?>" class="panel panel-default component">
    <div class="panel-heading imageTitle">
        <h3 class="panel-title">
            Image: <?php echo str_replace('_', ' ', $element->getId());?>
            <span id="<?php echo $element->getId();?>" class="glyphicon glyphicon-remove-circle"></span>
        </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <?php
                include('editorComponents/imageUrl.inc.php');

                if($this->premiumUser):
                    include('editorComponents/coords.inc.php');
                    include('editorComponents/dimensions.inc.php');
                endif;

                include('editorComponents/cmeo.inc.php');
                include('editorComponents/shadow.inc.php');
                include('editorComponents/stroke.inc.php');
            ?>
        </div>
    </div>
</div>
