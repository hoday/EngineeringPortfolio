<p>
    <label>Authors:</label><br />
    <input
        name="<?php echo esc_attr($this->configs->cpt_meta['publication']['authors']); ?>"
        value="<?php echo esc_attr($this->model->authors); ?>"
        class="eep-full-width"
        />
</p>
<p>
    <label>Details:</label><br />
    <input
        name="<?php echo esc_attr($this->configs->cpt_meta['publication']['details']); ?>"
        value="<?php echo esc_attr($this->model->details); ?>"
        class="eep-full-width"
        />
</p>


<p>
    <label>Publication link type:</label><br />
</p>
<?php $checkedInput = ($this->model->link != '') ? 'link' : 'file'; ?>
<div id="href_type">

    <input
        type="radio"
        id="href_type-1"
        name="href_type"
        value="link"
        <?php if ($checkedInput === 'link' ) {echo 'checked'; } ?>
    />
    <label for="href_type-1">
        Link
    </label>

    <input
        type="radio"
        id="href_type-2"
        name="href_type"
        value="file"
        <?php if ($checkedInput === 'file' ) {echo 'checked'; } ?>
     />
    <label for="href_type-2">
        File
    </label>

</div>

<div>
    <div id="href_type-link" style="display:<?php echo (($checkedInput === 'link') ? 'block' : 'none'); ?>">
        <p>
            <label>Link:</label><br />
            <input
                name="<?php echo esc_attr($this->configs->cpt_meta['publication']['link']); ?>"
                value="<?php echo esc_attr($this->model->link); ?>"
                class="eep-full-width"
                />
        </p>
    </div>

    <div id="href_type-file" style="display:<?php echo ($checkedInput === 'file') ? 'block' : 'none'; ?>">
        <p>
            <label>File:</label><br />
            <?php if ($this->model->file_id != '') : ?>
                <a href="<?php echo wp_get_attachment_url($this->model->file_id); ?>">
                    <?php echo esc_html($this->model->file_name); ?>
                </a>
                <br />
            <?php endif; ?>
            <input
                name="publication_file"
                type="file"
                />
        </p>
    </div>

</div>


<!--
    <ul id="href_type-tabs" class="category-tabs">
        <li class="">
            <a href="#href_type-link">Link</a>
        </li>
        <li class="hide-if-no-js tabs">
            <a href="#href_type-file">File</a>
        </li>

    </ul>

    <div id="href_type-link" class="tabs-panel" style="display:block">
        Link!!
    </div>

    <div id="href_type-file" class="tabs-panel" style="display:none">
        File!!
    </div>
-->
