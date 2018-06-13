<?php

namespace ElectrifyingEngineeringPortfolio;

require_once(EEPF_PLUGIN_DIR . '/includes/ProjectModel.php');
require_once(EEPF_PLUGIN_DIR . '/includes/PublicationModel.php');
require_once(EEPF_PLUGIN_DIR . '/includes/PortfolioItemModel.php');
require_once(EEPF_PLUGIN_DIR . '/includes/FeaturedModel.php');
require_once(EEPF_PLUGIN_DIR . '/views/admin/View.php');

const EEP_ADMIN_TEMPLATES_DIR = EEPF_PLUGIN_DIR . '/views/admin/templates';

class AdminPagesRenderer {

    protected $configs = null;

    public function __construct($configs) {
        $this->configs = $configs;
        $this->errors = array(
            'checkbox' => array(
                'param' => 'meta-error-checkbox',
                'code' => 1,
                'errorMessage' => esc_html__('An error has occurred updating the checkbox.', 'electrifying-engineer-portfolio')
            ),
            'link' => array(
                'param' => 'meta-error-link',
                'code' => 2,
                'errorMessage' => esc_html__('An error has occurred updating the link.', 'electrifying-engineer-portfolio')
            ),
        );
    }

    public function registerCallbacks() {

		// Handle metaboxes
		add_action('add_meta_boxes', array( $this, 'addMetaBoxes' ));
		add_action('save_post',       array( $this, 'saveMeta' ));

        add_action('admin_enqueue_scripts', array($this, 'enqueueAssetsTemporary'));

        // allow file uploads in the form for the edit publication admin panel page
        add_action('post_edit_form_tag', array($this, 'postEditFormTagCallbackMultipartEncoding'));

        add_action('admin_notices', array($this, 'adminNoticeCheckbox'));

        add_filter('default_content', array($this, 'overwriteDefaultMenuOrder'), 10, 2);

    }

    function enqueueAssetsTemporary($pageName) {

        wp_enqueue_style('eep-admin', EEPF_PLUGIN_URL . '/assets/css/admin.css');

        error_log('enqueueAssetsTemporary');
        error_log($pageName);

        if ('post.php' === $pageName) {
            error_log('enqueueing script');
    		wp_enqueue_script(
                'eep-tabs',
                EEPF_PLUGIN_URL . '/assets/js/admin_tabs.js',
                array('jquery'),
    			false,
                true
            );
    	}

    }

    public function printPluginLandingSubmenuPage() {
        ?>
       <div class="wrap">
           <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
           <p>Paste the following shortcode to display your portfolio:</p>
           <p>[eep-portfolio]</p>
       </div>

       <?php
    }


    /**
	 * Add metaboxes to specify custom post type data
	 */
	public function addMetaBoxes() {

		$meta_boxes = array(

			// Add project details metabox
			'project_meta_box_project_details' => array (
				'id'		=>	'project_meta_box_project_details',
				'title'		=> esc_html__( 'Project details', 'electrifying-engineering-portfolio' ),
				'callback'	=> array( $this, 'renderMetaboxDefault' ),
				'screen'	=> $this->configs->cpt_names['project'],
				'context'	=> 'normal',
				'priority'	=> 'low',
                'callback_args' => array(
                    'template'      => EEP_ADMIN_TEMPLATES_DIR . '/admin-metabox-project.php',
                    'model_name'    => '\ElectrifyingEngineeringPortfolio\ProjectModel',
                ),
			),

			// Add a metabox
			'publication_meta' => array (
				'id'		=> 'publication_meta',
				'title'		=> esc_html__( 'Publication details', 'electrifying-engineering-portfolio' ),
				'callback'	=> array( $this, 'renderMetaboxDefault' ),
				'screen'	=> $this->configs->cpt_names['publication'],
				'context'	=> 'normal',
				'priority'	=> 'low',
                'callback_args' => array(
                    'template'      => EEP_ADMIN_TEMPLATES_DIR . '/admin-metabox-publication.php',
                    'model_name'    => '\ElectrifyingEngineeringPortfolio\PublicationModel',
                ),
			),

			// Add a metabox
			'portfolio_item_meta' => array (
				'id'		=> 'portfolio_item_meta',
				'title'		=> esc_html__( 'Portfolio Item details', 'electrifying-engineering-portfolio' ),
				'callback'	=> array( $this, 'renderMetaboxDefault' ),
				'screen'	=> $this->configs->cpt_names['portfolio_item'],
				'context'	=> 'normal',
				'priority'	=> 'low',
                'callback_args' => array(
                    'template'      => EEP_ADMIN_TEMPLATES_DIR . '/admin-metabox-portfolioitem.php',
                    'model_name'    => '\ElectrifyingEngineeringPortfolio\PortfolioItemModel',
                ),
			),

			'featured_meta' => array (
				'id'		=> 'featured_meta',
				'title'		=> esc_html__( 'Feature on front page', 'electrifying-engineering-portfolio' ),
				'callback'	=> array( $this, 'renderMetaboxDefault' ),
				'screen'	=> array(
                    $this->configs->cpt_names['project'],
                    $this->configs->cpt_names['publication'],
                    $this->configs->cpt_names['portfolio_item']
                ),
				'context'	=> 'side',
				'priority'	=> 'low',
                'callback_args' => array(
                    'template' => EEP_ADMIN_TEMPLATES_DIR . '/admin-metabox-featured.php',
                    'model_name'    => '\ElectrifyingEngineeringPortfolio\FeaturedModel',
                ),
			),

		);

		// Create the metaboxes
		foreach ( $meta_boxes as $meta_box ) {
			add_meta_box(
				$meta_box['id'],
				$meta_box['title'],
				$meta_box['callback'],
				$meta_box['screen'],
				$meta_box['context'],
				$meta_box['priority'],
                $meta_box['callback_args']
			);
		}

	}


	/**
	 * Save the metabox data
	 */
	public function saveMeta( $post_id ) {

		// Check autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

        if (!in_array(get_post_type($post_id), $this->configs->cpt_names)) {
            return;
        }

        error_log(print_r($_POST, true));

        // define sanitization callbacks
        $sanitize_text_field = 'sanitize_text_field';
        $sanitize_callback_featured = function($value) {
            if ($value == 1) {
                $sanitized = 1;
            } elseif ($value == 0){
                $sanitized = 0;
            } else {
                $sanitized = false;
            }
            return $sanitized;
        };

        $sanitize_callback_url = function($value) {
            $value = trim($value);
            if ($value === '') {
                return $value;
            }
            return filter_var($value, FILTER_VALIDATE_URL);
        };

        $sanitize_callbacks = array(
            'project' => array(
                'subtitle'  => $sanitize_text_field,
                'description' => $sanitize_text_field,
                'credits'   => $sanitize_text_field,
                'featured'  => $sanitize_callback_featured,
            ),
            'publication'  => array(
                'authors'   => $sanitize_text_field,
                'details'   => $sanitize_text_field,
                'link'      => $sanitize_callback_url,
                'featured'  => $sanitize_callback_featured,
            ),
            'portfolio_item' => array(
                'details'   => $sanitize_text_field,
                'link'      => $sanitize_callback_url,
                'featured'  => $sanitize_callback_featured,
            ),
        );

		$post_type = get_post_type($post_id);
        $post_type_id = array_search($post_type, $this->configs->cpt_names);


		// Save the metadata
		$post_type_metas = $this->configs->cpt_meta[$post_type_id];

		foreach ($post_type_metas as $meta_id => $meta_name) {

            $sanitize_callback = $sanitize_callbacks[$post_type_id][$meta_id];

            //$sanitized = isset( $_POST[$meta_name] ) ? $sanitize_callback($_POST[$meta_name]) : '';
            if (isset($_POST[$meta_name])) {
                $sanitized = $sanitize_callback($_POST[$meta_name]);

                if ($sanitized !== false) {
                    update_post_meta($post_id, $meta_name, $sanitized);
                } else {
                    // problem sanitizing
                    if ($meta_id === 'featured') {
                        add_filter('redirect_post_location', function($location) {
                            $param = $this->errors['checkbox']['param'];
                            $code = $this->errors['checkbox']['code'];
                            return add_query_arg($param, $code, $location);
                         });
                    } elseif ($meta_id === 'link') {

                        add_filter('redirect_post_location', function($location) {
                            $param = $this->errors['link']['param'];
                            $code = $this->errors['link']['code'];
                            return add_query_arg($param, $code, $location);
                         });
                    }

                }
            }


		}

        if ($post_type == $this->configs->cpt_names['publication']){
            $this->storePublicationFile($post_id);
        }


	}

    public function adminNoticeCheckbox() {

        foreach($this->errors as $errorId => $errorDataArray) {

            $param = $errorDataArray['param'];

            if (isset($_GET[$param])) {

                $errorCode = (int) $_GET[$param];

                $class = 'notice notice-error';
                $errorMessage = $errorDataArray['errorMessage'];

            	printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($errorMessage));
            }
        }

    }

    /*
		Add file upload functionality to Publications custom post type admin page
	*/

	// changes encoding type for form post data to allow file uploads for publication post type only
	public function postEditFormTagCallbackMultipartEncoding($post) {

            if ($post->post_type == $this->configs->cpt_names['publication']) {
                echo ' enctype="multipart/form-data"';
            }
	}


	public function storePublicationFile($post_id) {

        $file_input_id = 'publication_file';


		// If the upload field has a file in it
		if(isset($_FILES[$file_input_id]) && ($_FILES[$file_input_id]['size'] > 0)) {

			// Get the type of the uploaded file. This is returned as "type/extension"
			$arr_file_type = wp_check_filetype(basename($_FILES[$file_input_id]['name']));
			$uploaded_file_type = $arr_file_type['type'];

			// Set an array containing a list of acceptable formats
			$allowed_file_types = array(
                'application/pdf',
                'text/plain',
                'application/vnd.msword',
                'application/rtf',
            );

			// If the uploaded file is the right format
			if(in_array($uploaded_file_type, $allowed_file_types)) {

				// Options array for the wp_handle_upload function. 'test_upload' => false
				$upload_overrides = array( 'test_form' => false );

				// Handle the upload using WP's wp_handle_upload function. Takes the posted file and an options array
				$uploaded_file = wp_handle_upload( $_FILES[$file_input_id], $upload_overrides );

				// If the wp_handle_upload call returned a local path for the image
				if(isset($uploaded_file['file'])) {

					// The wp_insert_attachment function needs the literal system path, which was passed back from wp_handle_upload
					$file_name_and_location = $uploaded_file['file'];

					// Generate a title for the image that'll be used in the media library
					$file_title_for_media_library = $_FILES[$file_input_id]['name'];

					// Set up options array to add this file as an attachment
					$attachment = array(
							'post_mime_type' => $uploaded_file_type,
							'post_title' => $file_title_for_media_library,
							'post_content' => '',
							'post_status' => 'inherit'
					);

					// Run the wp_insert_attachment function. This adds the file to the media library and generates the thumbnails. If you wanted to attch this image to a post, you could pass the post id as a third param and it'd magically happen.
					$attach_id = wp_insert_attachment( $attachment, $file_name_and_location, $post_id);

				}

			}

		}

	}

   	/*
   		 Renders the metabox contents
   	*/
   	public function renderMetaboxDefault($post, $metabox) {

        $args = $metabox['args'];
        $model = new $args['model_name']($this->configs, $post->ID);
        $template = $args['template'];

        (new View(
            $this->configs,
            $model,
            $template
        ))->render();
   	}

    function overwriteDefaultMenuOrder($postContent, $post) {

        if (in_array($post->post_type, array_values($this->configs->cpt_names))) {
            $post->menu_order = $post->ID * 10;
        }

        return $postContent;
    }

}
