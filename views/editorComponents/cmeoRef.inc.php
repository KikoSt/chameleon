<div class="row">
    <label class="col-md-4" for="<?php echo $element->getId();?>#cmeoRef">Source:</label>
    <select name="<?php echo $element->getId();?>#cmeoRef"
            class="form-control"
        <?php
            if(is_a($element, 'GfxImage'))
            {
                $options = $this->cmeoRefOptions['GfxImage'];
            }
            else if(is_a($element, 'GfxText'))
            {
                $options = $this->cmeoRefOptions['GfxText'];
                var_dump($options);
            }
            else
            {
                echo 'disabled';
            }
        ?>
        >
        <option value=""><?php echo (is_a($element, 'GfxImage') || is_a($element, 'GfxText')) ? 'Select option...' : ''; ?></option>
        <?php foreach($options as $option): ?>
            <option value="<?php echo $option;?>" <?php echo ($option === $element->getRef()) ? 'selected' : '';?>><?php echo $option;
                ?></option>
        <?php endforeach;?>
    </select>
</div>
