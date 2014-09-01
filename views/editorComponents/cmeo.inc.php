<div class="col-md-6">
    <label class="col-md-2 control-label" for="<?php echo $element->getId();?>#cmeoRef">Ref:</label>
    <div class="col-md-10">
        <select name="<?php echo $element->getId();?>#cmeoRef"
                class="form-control"
            <?php echo (is_a($element, 'GfxImage') || is_a($element, 'GfxText')) ? '': 'disabled'; ?>
            >
            <option value=""><?php echo (is_a($element, 'GfxImage') || is_a($element, 'GfxText')) ? 'Select ref option...' : ''; ?></option>
            <?php foreach($this->cmeoRefOptions as $option): ?>
                <option value="<?php echo $option;?>" <?php echo ($option === $element->getCmeoRef()) ? 'selected' : '';?>><?php echo $option;
                    ?></option>
            <?php endforeach;?>
        </select>
    </div>
</div>
<div class="col-md-6">
    <label class="col-md-2 control-label" for="<?php echo $element->getId();?>#cmeoLink">Link:</label>
    <div class="col-md-10">
        <select name="<?php echo $element->getId();?>#cmeoLink"
                class="form-control"
            >
            <option value="">Select link option...</option>
            <?php foreach($this->cmeoLinkOptions as $option): ?>
                <option value="<?php echo $option;?>" <?php echo ($option === $element->getCmeoLink()) ? 'selected' : '';?>><?php echo $option;
                    ?></option>
            <?php endforeach;?>
        </select>
    </div>
</div>