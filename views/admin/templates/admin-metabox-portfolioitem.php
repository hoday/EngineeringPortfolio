<p>
    <label>Details:</label><br />
    <input
        name="<?php echo esc_attr($this->configs->cpt_meta['portfolio_item']['details']); ?>"
        value="<?php echo esc_attr($this->model->details); ?>"
        class="eep-full-width"
        />
</p>
<p>
    <label>Link:</label><br />
    <input
        name="<?php echo esc_attr($this->configs->cpt_meta['portfolio_item']['link']); ?>"
        value="<?php echo esc_attr($this->model->link); ?>"
        class="eep-full-width"
        />
</p>
