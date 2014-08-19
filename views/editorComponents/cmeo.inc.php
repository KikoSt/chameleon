<div class="col-md-6">
    <label>Ref:</label>
    <select name="<?php echo $element->getId();?>#cmeoRef"
            class="form-control"
            <?php echo (is_a($element, 'GfxImage') || is_a($element, 'GfxText')) ? '': 'disabled'; ?>
        >
        <option></option>
        <?php foreach($this->cmeoOptions as $option): ?>
            <option value="<?php echo $option;?>" <?php echo ($option === $element->getCmeoRef()) ? 'selected' : '';?>><?php echo $option;
                ?></option>
        <?php endforeach;?>
    </select>
</div>
<div class="col-md-6">
    <label>Link:</label>
    <select name="<?php echo $element->getId();?>#cmeoLink"
            class="form-control"
        >
        <option></option>
        <?php foreach($this->cmeoOptions as $option):

            var_dump($option);
            var_dump($element->getCmeoLink());

            ?>
            <option value="<?php echo $option;?>" <?php echo ($option === $element->getCmeoLink()) ? 'selected' : '';?>><?php echo $option;
                ?></option>
        <?php endforeach;?>
    </select>
</div>