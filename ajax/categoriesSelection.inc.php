<?php
    foreach($this->combinedCategories as $combinedCategories):
        if($combinedCategories['status'] === "ACTIVE"):
?>
            <div id="row_<?php echo $combinedCategories['id']; ?>" class="row">
                <div class="col-md-10">
                    <?php echo $combinedCategories['name']; ?>
                </div>
                <button id="<?php echo $combinedCategories['id']; ?>" class="removeCategory" type="button" style="color:#000000">
                    <span class="glyphicon glyphicon-minus"></span>
                </button>
                <span class="<?php echo $combinedCategories['icon']; ?>"></span>
            </div>
<?php
        endif;
    endforeach;