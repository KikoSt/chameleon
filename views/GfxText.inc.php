<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">
            <a data-toggle="collapse" data-target="#<?php echo $element->getId();?>"
               href="#<?php echo $element->getId();?>">
                Text: <?php echo $element->getId();?>
            </a>
        </h3>
    </div>
    <div id="<?php echo $element->getId();?>" class="panel-collapse collapse"
    <div class="panel-body">
        <ul class="list-group">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-3">
                        <label>Fill:</label>
                        <input id="fill" type="text" name="<?php echo $element->getId();?>#fill" class="form-control picker" value="<?php
                        echo $element->getFill()->getHex();?>" />
                    </div>
                    <div class="col-md-3">
                        <?php if(null !== $element->getStroke()): ?>
                        <label>Stroke:</label>
                        <input id="stroke" type="text" name="<?php echo $element->getId();?>#stroke" class="form-control" value="<?php echo
                        $element->getStroke()->getHex();?>" />
                        <?php endif; ?>
                    </div>
                    <div class="col-md-3">
                        <label>text anchor:</label>
                        <input type="text" class="form-control" name="<?php echo $element->getId();?>#textAnchor" placeholder="<?php echo
                        $element->getX();?>"/>
                    </div>
                    <div class="col-md-3">
                        <label>Font family:</label>
                        <select class="form-control" name="<?php echo $element->getId();?>#fontFamily">
                            <?php foreach($GLOBALS['fontlist']['GIF'] as $font):?>
                                <option value="<?php echo $font; ?>"><?php echo $font; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-3">
                        <label>X coordinate:</label>
                        <input type="text" name="<?php echo $element->getId();?>#x"  value="<?php echo
                        $element->getX();?>"class="form-control" placeholder="<?php echo
                        $element->getX();?>"/>
                    </div>
                    <div class="col-md-3">
                        <label>Y coordinate:</label>
                        <input type="text" name="<?php echo $element->getId();?>#y" value="<?php echo
                        $element->getY();?>" class="form-control" placeholder="<?php echo
                        $element->getY();?>"/>
                    </div>
                    <div class="col-md-3">
                        <label>Width:</label>
                        <input type="text" name="<?php echo $element->getId();?>#width" value="<?php echo
                        $element->getWidth();?>" class="form-control" placeholder="<?php echo
                        $element->getWidth();?>"/>
                    </div>
                    <div class="col-md-3">
                        <label>Height:</label>
                        <input type="text" name="<?php echo $element->getId();?>#height" value="<?php
                        echo
                        $element->getHeight();?>" class="form-control" placeholder="<?php
                        echo
                        $element->getHeight();?>"/>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-12">
                        <label>Text:</label>
                        <input type="text" name="<?php echo $element->getId();?>#text"  value="<?php echo
                        $element->getText();?>"class="form-control" placeholder="<?php echo $element->getText();?>"/>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>
