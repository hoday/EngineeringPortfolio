<section id="eep-featured-projects" class="eep-section eep-section-featured-projects" >

    <div class="eep-section-container">

        <h1 class="eep-section-title">
            Projects
        </h1>

        <div class="eep-section-contents">

            <?php $loop_counter = 0; ?>
            <?php $featuredProjects = $this->model->getFeaturedProjects(); ?>
            <?php foreach($featuredProjects as $post) : ?>

            <?php if (($loop_counter % $this->model->numberColumns) == 0): ?>
            <div class="eep-section-row">
            <?php endif; ?>


                <div class="eep-section-column eep-columns-<?php echo esc_attr($this->model->numberColumns); ?>">

                    <div class="eep-item eep-item-project">
                        <div class="eep-item-thumbnail">
                            <a href="<?php echo the_permalink($post->ID); ?>">
                                <?php
                                    if (has_post_thumbnail($post->ID)) {
                                        echo get_the_post_thumbnail(
                                            $post->ID,
                                            $this->configs->thumbnail_settings['portfolioimg']['name'],
                                            array(
                                                'class' => 'img-fluid img-responsive responsive--full',
                                                'alt'   => esc_attr($post->post_title),
                                            )
                                        );
                                    } else {
                                        ?>
                                        <img
                                            src="<?php echo esc_url(EEPF_PLUGIN_URL . '/assets/img/defaultthumbnail.png'); ?>"
                                            width="<?php echo esc_attr($this->configs->thumbnail_settings['portfolioimg']['width']); ?>"
                                            height="<?php echo esc_attr($this->configs->thumbnail_settings['portfolioimg']['height']); ?>"
                                            class="img-fluid img-responsive responsive--full wp-post-image"
                                            alt="<?php echo esc_attr($post->post_title); ?>"
                                            />
                                        <?php
                                    }
                                 ?>
                            </a>
                        </div>
                        <div class="eep-item-caption">
                            <a href="<?php echo get_the_permalink($post->ID); ?>">
                                <h3 class="eep-item-title">
                                    <?php echo esc_html($post->post_title); ?>
                                </h3>
                            </a>
                            <div class="eep-item-description">
                                <?php echo esc_html(get_post_meta($post->ID, $this->configs->cpt_meta['project']['description'], true)); ?>
                            </div>
                        </div>
                        <?php if ($this->model->hasSkills($post->ID)) : ?>
                        <div class="eep-item-skills-container">
                            <?php $skills = $this->model->getSkills($post->ID); ?>
                            <?php foreach ($skills as $skill): ?>
                                <div class="skill">
                                    <?php echo esc_html($skill); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                        <div class="eep-item-link-container">
                            <a href="<?php echo get_the_permalink($post->ID); ?>">
                                <div class="eep-item-link">
                                    Learn more
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            <?php if (($loop_counter % $this->model->numberColumns) == ($this->model->numberColumns - 1) || $loop_counter == sizeof($featuredProjects) - 1): ?>
            </div>
            <?php endif; ?>

            <?php $loop_counter++; ?>

        <?php endforeach; ?>

        </div>

        <div class="eep-section-seemore">
            <a href="<?php echo get_post_type_archive_link($this->configs->cpt_names['project']); ?>">
                <div>
                    See more projects &raquo;
                </div>
            </a>
        </div>

    </div>

</section>
