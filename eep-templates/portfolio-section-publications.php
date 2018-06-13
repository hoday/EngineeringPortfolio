<section id="eep-featured-publications" class="eep-section eep-section-featured-publications">

    <div class="eep-section-container">

        <h1 class="eep-section-title">
            Publications
        </h1>

        <div class="eep-section-contents">

            <?php $publications_by_type = $this->model->getFeaturedPublicationsByType(); ?>

            <?php foreach ($publications_by_type as $type => $publication_array) : ?>

                <?php if (!empty($publication_array)) : ?>

                    <div class="eep-section-row">

                    	<div class="eep-section-column">

                    		<h2 class="eep-section-subtitle">
                                <?php echo esc_html(($type=='other') ? 'Other' : get_term_by('name', $type, $this->configs->custom_taxonomy_names['publication_type']['name'])->description); ?>
                    		</h2>

                    		<div class="eep-section-subcontents">

                                <?php foreach ($publication_array as $publicationModel) : ?>

                                    <?php $hrefInfo = $publicationModel->getHref(); ?>

                                    <div class="eep-item eep-item-publication">
                                        <div class="eep-item-publication-container">
                                            <div class="eep-item-title eep-item-publication-title">
                                                <?php if ($hrefInfo != null) : ?>
                                                    <a
                                                        class="eep-publicationlink"
                                                        href="<?php echo esc_url($hrefInfo['href']); ?>"
                                                        >
                                                <?php endif; ?>
                                                <?php echo esc_html($publicationModel->title); ?>
                                                <?php if ($hrefInfo != null) : ?>
                                                    </a>
                                                <?php endif; ?>
                                            </div>
                                             <div class="eep-item-publication-author">
                                                <?php echo esc_html($publicationModel->authors); ?>
                                            </div>
                                            <div class="eep-item-publication-location">
                                                <?php echo esc_html($publicationModel->details); ?>
                                            </div>
                                        </div>
                                        <div class="eep-item-publication-icon-container">
                                            <?php if ($hrefInfo != null) : ?>
                                                <a
                                                    class="eep-publicationlink"
                                                    href="<?php echo esc_url($hrefInfo['href']); ?>"
                                                    >
                                            <?php endif; ?>
                                            <?php if ($hrefInfo != null) : ?>
                                                <?php $svgHeight='1.2rem'; $svgWidth='auto'; ?>
                                                <?php include EEPF_PLUGIN_DIR.'/assets/img/'.'chevron-right.svg'; ?>
                                            <?php endif; ?>
                                            <?php if ($hrefInfo != null) : ?>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                <?php endforeach; ?>

                            </div>

                    	</div>

                    </div>

                <?php endif; ?>

            <?php endforeach; ?>

        </div>

        <div class="eep-section-seemore">
            <a href="<?php echo get_post_type_archive_link($this->configs->cpt_names['publication']); ?>">
                <div>
                    See more publications &raquo;
                </div>
            </a>
        </div>

    </div>

</section>
