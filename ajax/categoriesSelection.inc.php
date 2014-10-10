<?php
    session_start();
    foreach($_SESSION['category'] as $id => $value):
?>
<div id="row_<?php echo $id;?>" class="row">
    <div class="col-md-10">
        <?php echo $value;?>
    </div>
    <button id="<?php echo $id;?>" class="removeCategory" type="button" style="color:#000000">
        <span class="glyphicon glyphicon-minus"></span>
    </button>
</div>
<?php
    endforeach;
?>