<fieldset class="inline-edit-col-right inline-edit-<?php echo $post_type; ?>">
      <div class="inline-edit-col column-<?php echo $column_name; ?>">
        <div class="inline-edit-group wp-clearfix">
            <label class="inline-edit-<?php echo $column_name; ?> alignleft">
                <span class="title">Feature on front page:</span>
                <span name="featured">
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
                        />
                </span>
            </label>
        </div>
    </div>
</fieldset>
