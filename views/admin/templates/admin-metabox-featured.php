<p>
    <label>Feature on front page:</label><br />
    <input
        name="<?php echo esc_attr($this->configs->cpt_meta['portfolio_item']['featured']); ?>"
        type="hidden"
        value=0
        />
    <input
        id="checkBox"
        name="<?php echo esc_attr($this->configs->cpt_meta['portfolio_item']['featured']); ?>"
        type="checkbox"
        value=1
        <?php if ($this->model->featured == 1) { echo "checked"; } ?>
        />
</p>
