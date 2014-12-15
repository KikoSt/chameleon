<div class="row">
    <label class="col-md-4">Source:</label>
    <select name="<?php echo $element->getId();?>#cmeoRef"
            id="<?php echo $element->getId();?>_source"
            class="form-control-select"
        <?php echo (is_a($element, 'GfxImage') || is_a($element, 'GfxText')) ? '': 'disabled'; ?>
        >
        <?php if(is_a($element, 'GfxImage') || is_a($element, 'GfxText')) echo '<option value="">Select option...</option>'; ?>
        <?php foreach($this->cmeoRefOptions as $option): ?>
            <option value="<?php echo $option;?>" <?php echo ($option === $element->getRef()) ? 'selected' : '';?>><?php echo $option;
                ?></option>
        <?php endforeach;?>
    </select>
</div>
<div class="row">
    <label class="col-md-4">Link:</label>
    <select name="<?php echo $element->getId();?>#cmeoLink"
            id="<?php echo $element->getId();?>_link"
            class="form-control-select"
        >
        <option value="">Select option...</option>
        <?php foreach($this->cmeoLinkOptions as $option): ?>
            <option value="<?php echo $option;?>" <?php echo ($option === $element->getLinkUrl()) ? 'selected' : '';?>><?php echo $option;
                ?></option>
        <?php endforeach;?>
    </select>
</div>
