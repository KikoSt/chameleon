<div class="row">
    <label class="col-md-4" for="<?php echo $element->getId();?>#cmeoRef">Source:</label>
    <select name="<?php echo $element->getId();?>#cmeoRef"
            class="form-control"
        <?php echo (is_a($element, 'GfxImage') || is_a($element, 'GfxText')) ? '': 'disabled'; ?>
        >
        <option value=""><?php echo (is_a($element, 'GfxImage') || is_a($element, 'GfxText')) ? 'Select option...' : ''; ?></option>
        <?php foreach($this->cmeoRefOptions as $option): ?>
            <option value="<?php echo $option;?>" <?php echo ($option === $element->getCmeoRef()) ? 'selected' : '';?>><?php echo $option;
                ?></option>
        <?php endforeach;?>
    </select>
</div>
