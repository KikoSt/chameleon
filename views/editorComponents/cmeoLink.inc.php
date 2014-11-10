<div class="row">
    <label class="col-md-4" for="<?php echo $element->getId();?>#cmeoLink">Link:</label>
    <select name="<?php echo $element->getId();?>#cmeoLink"
            class="form-control"
        >
        <option value="">Select option...</option>
        <?php foreach($this->cmeoLinkOptions as $option): ?>
            <?php echo $option . ' - ' . $element->getCmeoLink() . "\n"; ?>
            <option value="<?php echo $option;?>" <?php echo ($option === $element->getCmeoLink()) ? 'selected' : '';?>><?php echo $option;
                ?></option>
        <?php endforeach;?>
    </select>
</div>
