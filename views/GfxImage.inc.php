<div id="panel_<?php echo $element->getId();?>" data-type="image" data-groupid="<?php echo $element->getEditGroup(); ?>" class="panel panel-default component">
    <div class="panel-heading imageTitle">
        <h3 class="panel-title">
            Image: <?php echo str_replace('_', ' ', $element->getId());?>
            <span id="<?php echo $element->getId();?>" class="glyphicon glyphicon-remove-circle"></span>
        </h3>
    </div>
    <div class="panel-body">
        <div class="container-fluid">
            <?php
                include('editorComponents/imageUrl.inc.php');

                if($this->premiumUser):
                    include('editorComponents/coords.inc.php');
                    include('editorComponents/dimensions.inc.php');
                endif;

                include('editorComponents/cmeo.inc.php');
                include('editorComponents/shadow.inc.php');
                include('editorComponents/stroke.inc.php');
                if($this->premiumUser):
                    include('editorComponents/editGroup.inc.php');
                endif;
            ?>
        </div>
    </div>
</div>
