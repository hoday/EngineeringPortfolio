<section id="eep-project-content" class="eep-project-section eep-project-content">

    <div class="eep-section-container">

        <div class="eep-project-featuredimg">

            <?php
            $id = $this->model->post->ID;
            if (
                !current_theme_supports('post-thumbnails') &&
                has_post_thumbnail($id)
            ) {                echo get_the_post_thumbnail(
                    $id,
                    'medium_large',
                    array(
                        'class' => 'img-fluid img-responsive responsive--full',
                        'alt'   => esc_attr($this->model->getTitle()),
                    )
                );
            }
            ?>

        </div>

        <h2 class="eep-section-subtitle">

            <?php echo esc_html($this->model->getTitle()); ?>

        </h2>

        <div class="eep-description">

            <?php echo esc_html($this->model->getDescription()); ?>

        </div>

        <div class="eep-section-contents">

            <?php echo ($this->model->getContent()); ?>

        </div>

    </div>

</section>
