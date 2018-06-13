<div class="eep-section-row">

	<div class="eep-section-column">

		<h2 class="eep-section-subsubtitle">
			<?php echo esc_html(($type=='other') ? 'Other' : get_term_by('name', $type, $this->configs->custom_taxonomy_names['publication_type']['name'])->description); ?>
		</h2>

		<div class="eep-section-subcontents">

            <?php foreach ($publication_array as $publicationModel) : ?>

                <?php $hrefInfo = $publicationModel->getHref(); ?>

                <div class="eep-item eep-item-publication">
                    <div class="eep-item-publication-container">
                        <div class="eep-item-publication-title">
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
                        <?php $svgHeight = '1.2rem'; $svgWidth = '1.0rem'; ?>
                        <?php if ($hrefInfo != null && $hrefInfo['type'] == 'file') : ?>
                            <?php include EEPF_PLUGIN_DIR.'/assets/img/'.'chevron-right.svg'; ?>
                        <?php elseif ($hrefInfo != null && $hrefInfo['type'] == 'link') : ?>
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
