<div id="leftbox">
    <div style="position:fixed;">
    <ul class="nav nav-list">
        <li id="overview" class="<?php echo ($this->page === 'overview' ) ? 'active' : '';?>">
            <a href="index.php?page=overview">Overview</a>
        </li>
        <!--
        <li class="<?php echo ($this->page === 'editor' ) ? 'active' : 'hidden';?>">
            <a href="#">Edit</a>
            <ul class="nav nav-list">
                <?php
                    foreach($this->elements as $element):
                ?>
                <li><a id="<?php echo $element->getId();?>" class="subnav"><?php echo $element->getId();?></a></li>
                <?php
                    endforeach;
                ?>
            </ul>
        </li>
        -->
        <li class="">
            <a href="#">Manage</a>
        </li>
    </ul>
    </div>
</div>
