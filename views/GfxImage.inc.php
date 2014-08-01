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
                    <div class="col-md-12">
                        <label>URL:</label>
                        <input type="text"
                               class="form-control"
                               name="<?php echo $element->getId();?>#imageUrl"
                               value="<?php echo str_replace(ROOT_DIR, '', $element->getImageUrl());?>"
                               placeholder="<?php echo $element->getImageUrl();?>"
                        />
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-3">
                        <label>X coordinate:</label>
                        <input type="text"
                               class="form-control"
                               name="<?php echo $element->getId();?>#x"
                               value="<?php echo $element->getX();?>"
                               placeholder="<?php echo $element->getX();?>"
                        />
                    </div>
                    <div class="col-md-3">
                        <label>Y coordinate:</label>
                        <input type="text"
                               class="form-control"
                               name="<?php echo $element->getId();?>#y"
                               value="<?php echo $element->getY();?>"
                               placeholder="<?php echo $element->getY();?>"
                        />
                    </div>
                    <div class="col-md-3">
                        <label>Width:</label>
                        <input type="text"
                               class="form-control"
                               name="<?php echo $element->getId();?>#width"
                               value="<?php echo $element->getWidth();?>"
                               placeholder="<?php echo $element->getWidth();?>"
                        />
                    </div>
                    <div class="col-md-3">
                        <label>Height:</label>
                        <input type="text"
                               class="form-control"
                               name="<?php echo $element->getId();?>#height"
                               value="<?php echo $element->getHeight();?>"
                               placeholder="<?php echo $element->getHeight();?>"
                        />
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
