<div class="panel panel-default">
    <div class="panel-heading texttitle">
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
                    <div class="col-md-9">
                        <label>Text:</label>
                        <input type="text"
                               class="form-control"
                               name="<?php echo $element->getId();?>#text"
                               value="<?php echo $element->getText();?>"
                               placeholder="<?php echo $element->getText();?>"
                            />
                    </div>
                    <div class="col-md-3">
                        <label>Font family:</label>
                        <select class="form-control"
                                name="<?php echo $element->getId();?>#fontFamily">
                            <?php
                            foreach($this->fontlist as $key => $font):
                                $selected = ($key === $element->getFontFamily()) ? "selected" : '';
                                ?>
                                <option value="<?php echo $key; ?>" <?php echo $selected;?>><?php echo $font; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-md-<?php echo (null !== $element->getStroke())? '3' : '6';?>">
                        <label>Fill:</label>
                        <input id="fill"
                               type="text"
                               name="<?php echo $element->getId();?>#fill"
                               class="form-control picker"
                               value="<?php echo $element->getFill()->getHex();?>"
                        />
                    </div>
                    <?php if(null !== $element->getStroke()): ?>
                    <div class="col-md-3">

                        <label>Stroke:</label>
                        <input id="stroke"
                               type="text"
                               name="<?php echo $element->getId();?>#stroke"
                               class="form-control"
                               value="<?php echo $element->getStroke()->getHex();?>"
                        />
                    </div>
                    <?php endif; ?>
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
                </div>
            </li>
        </ul>
    </div>
</div>
