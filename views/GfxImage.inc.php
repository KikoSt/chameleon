<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a data-toggle="collapse" data-target="#<?php echo $element->getId();?>"
               href="#<?php echo $element->getId();?>">
                Image: <?php echo $element->getId();?>
            </a>
        </h3>
    </div>
    <div id="<?php echo $element->getId();?>" class="panel-collapse collapse"
    <div class="panel-body">
        <ul class="list-group">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-3">
                        <label>X coordinate:</label>
                        <input type="text" class="form-control" placeholder="<?php echo $element->getX();?>"/>
                    </div>
                    <div class="col-md-3">
                        <label>Y coordinate:</label>
                        <input type="text" class="form-control" placeholder="<?php echo $element->getY();?>"/>
                    </div>
                    <div class="col-md-3">
                        <label>Width:</label>
                        <input type="text" class="form-control" placeholder="<?php echo $element->getWidth();?>"/>
                    </div>
                    <div class="col-md-3">
                        <label>Height:</label>
                        <input type="text" class="form-control" placeholder="<?php echo $element->getHeight();?>"/>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
