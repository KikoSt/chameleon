<div class="col-md-6">
    <label>Ref:</label>
    <select name="<?php echo $element->getId();?>#cmeoRef"
            class="form-control"
            <?php echo (is_a($element, 'GfxImage') || is_a($element, 'GfxText')) ? '': 'disabled'; ?>
        >
        <option></option>
        <option value="name">name</option>
        <option value="productUrl">productUrl</option>
        <option value="productImageUrl">productImageUrl</option>
        <option value="price">price</option>
        <option value="priceOld">priceOld</option>
        <option value="currencyShort">currencyShort</option>
        <option value="currencySymbol">currencySymbol</option>
    </select>
</div>
<div class="col-md-6">
    <label>Link:</label>
    <select name="<?php echo $element->getId();?>#cmeoLink"
            class="form-control"
        >
        <option></option>
        <option value="name">name</option>
        <option value="productUrl">productUrl</option>
        <option value="productImageUrl">productImageUrl</option>
        <option value="price">price</option>
        <option value="priceOld">priceOld</option>
        <option value="currencyShort">currencyShort</option>
        <option value="currencySymbol">currencySymbol</option>
    </select>
</div>