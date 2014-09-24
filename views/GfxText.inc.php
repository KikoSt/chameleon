<div class="panel panel-default">
    <div class="panel-heading texttitle">
        <h3 class="panel-title ">
                Text: <?php echo str_replace('_', ' ', $element->getId());?>
        </h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <?php
            include('editorComponents/text.inc.php');
            ?>
        </div>
        <div class="row">
            <?php
            include('editorComponents/fontFamily.inc.php');
            ?>
        </div>
        <div class="row">
            <?php
                include('editorComponents/color.inc.php');
            ?>
        </div>
        <div class="row">
            <?php
                include('editorComponents/coords.inc.php');
            ?>
        </div>
        <div class="row">
            <?php include('editorComponents/cmeo.inc.php');?>
        </div>
        <div class="row">
            <?php
                include('editorComponents/shadow.inc.php');
            ?>
        </div>
        <div class="row">
            <?php
            include('editorComponents/stroke.inc.php');
            ?>
        </div>
    </div>
</div>
