


<section id="eep-project-readmore" class="eep-project-section eep-project-readmore">

    <div class="eep-section-container">

        <h2 class="eep-section-subtitle">
            More Projects
        </h2>

        <div class="eep-section-contents">
            <?php $read_more_id_list = $this->model->getReadMore(); ?>

            <?php foreach ($read_more_id_list as $read_more_id => $project_model) : ?>

                <div id="<?php echo esc_attr($project_model->slug); ?>" class="eep-readmoreitem">
                    <div class="eep-readmoreitem-thumbnail">
                        <a href="<?php echo get_the_permalink($read_more_id); ?>">
                            <?php
                                if (has_post_thumbnail($read_more_id)) {
                                    echo get_the_post_thumbnail(
                                        $read_more_id,
                                        $this->configs->thumbnail_settings['readmoreimg']['name'],
                                        array(
                                            'class' => 'img-responsive responsive--full wp-post-image',
                                            'alt' => esc_attr($project_model->title),
                                        )
                                    );
                                } else {
                                    ?>
                                    <img
                                        src="<?php echo esc_url(EEPF_PLUGIN_URL . '/assets/img/defaultthumbnail.png'); ?>"
                                        width="<?php echo esc_attr($this->configs->thumbnail_settings['readmoreimg']['width']); ?>"
                                        height="<?php echo esc_attr($this->configs->thumbnail_settings['readmoreimg']['height']); ?>"
                                        class="img-responsive responsive--full wp-post-image"
                                        alt="<?php echo esc_attr($project_model->title); ?>"
                                        />
                                    <?php
                                }
                             ?>
                        </a>
                    </div>
                    <div class="eep-readmoreitem-text">
                        <a href="<?php echo get_the_permalink($read_more_id); ?>" title="<?php esc_attr($project_model->title); ?>">
                            <h3 class="eep-readmoreitem-title">
                                <?php echo esc_html($project_model->title); ?>
                            </h3>
                            <div class="eep-readmoreitem-excerpt">
                                <?php echo esc_html($project_model->description); ?>
                            </div>
                        </a>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>

    </div>

</section>
