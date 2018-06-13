<?php $projectModel = $this->model; ?>

<p>
    <label>Subtitle:</label><br />
    <input
        name="<?php echo esc_attr($this->configs->cpt_meta['project']['subtitle']); ?>"
        value="<?php echo esc_attr($projectModel->subtitle); ?>"
        class="eep-full-width"
        />
</p>
<p>
    <label>Description:</label><br />
    <input
        name="<?php echo esc_attr($this->configs->cpt_meta['project']['description']); ?>"
        value="<?php echo esc_attr($projectModel->description); ?>"
        class="eep-full-width"
        />
</p>
<p>
    <label>Credits:</label><br />
    <textarea cols="50" rows="5"
        name="<?php echo esc_attr($this->configs->cpt_meta['project']['credits']); ?>"
        class="eep-full-width"
        >
        <?php echo esc_html($projectModel->credits); ?>
    </textarea>
</p>
