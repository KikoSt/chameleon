<div class="row">
    <label class="col-md-4">lLink:</label>
    <select name="<?php echo $element->getId();?>#cmeoLink"
            id="<?php echo $element->getId();?>_link"
            class="form-control"
        >
        <option value="">Select option...</option>
        <?php foreach($this->cmeoLinkOptions as $option): ?>
            <option value="<?php echo $option;?>" <?php echo ($option === $element->getLink()) ? 'selected' : '';?>><?php echo $option;
                ?></option>
        <?php endforeach;?>
    </select>
</div>
