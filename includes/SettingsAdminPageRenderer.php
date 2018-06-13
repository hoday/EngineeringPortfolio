<?php

namespace ElectrifyingEngineeringPortfolio;

class SettingsAdminPageRenderer {

    const PLUGIN_SETTINGS_GROUP_NAME = 'eep-settings';

    public $configs = null;
    public $plugin_options_key = null;
    public $plugin_options_array_keys = null;

    public function __construct($configs) {

        $this->configs =  $configs;
        $this->plugin_options_key = $configs->plugin_options_key;
        $this->plugin_options_array_keys =  $configs->plugin_options_array_keys;
    }

    public function registerCallbacks() {

        // register settings for this plugin
        add_action('admin_init', array($this, 'registerPluginSettings'));
    }


    function registerPluginSettings() {

        if (current_user_can('manage_options')) {

            if (!wp_doing_ajax()) {

                register_setting(
                    self::PLUGIN_SETTINGS_GROUP_NAME,
                    $this->plugin_options_key
                );

                add_settings_section(
                    'eep-settingssection-display',
                    __('Display Settings', 'electrifying-engineering-portfolio'),
                    array($this, 'printPluginOptionsPageSectionDisplay'),
                    self::PLUGIN_SETTINGS_GROUP_NAME
                );

                add_settings_field(
                     'id-eep-setting-project-num-columns',
                     __( 'Number of Columns for Projects Section', 'electrifying-engineering-portfolio' ),
                     array($this, 'printPluginSettingsPageFieldNumColumns'),
                     self::PLUGIN_SETTINGS_GROUP_NAME,
                     'eep-settingssection-display',
                     array(
                         'label_for' => $this->plugin_options_array_keys['eep_field_num_cols']
                     )
                 );

                 add_settings_field(
                      'id-eep-setting-style',
                      __( 'Style to apply', 'electrifying-engineering-portfolio' ),
                      array($this, 'printPluginSettingPageFieldStyle'),
                      self::PLUGIN_SETTINGS_GROUP_NAME,
                      'eep-settingssection-display',
                      array(
                          'label_for' => $this->plugin_options_array_keys['eep_field_style']
                      )
                  );

            }

        }



    }

    public function printPluginSettingsSubmenuPage() {

         if ( isset( $_GET['settings-updated'] ) ) {
             add_settings_error( 'eep-settings_messages', 'eep-settings_message', __( 'Settings Saved', 'electrifying-engineering-portfolio' ), 'updated' );
         }
         settings_errors( 'eep-settings_messages' );
         ?>

            <div class="wrap">
                <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
                <form action="options.php" method="post">
                <?php
                    settings_fields(self::PLUGIN_SETTINGS_GROUP_NAME);
                    do_settings_sections(self::PLUGIN_SETTINGS_GROUP_NAME);
                    submit_button( 'Save Settings' );
                ?>
                </form>
            </div>

        <?php

    }

    function printPluginOptionsPageSectionDisplay($args) {
        ?>
            <p id="<?php echo esc_attr($args['id']); ?>">
                <?php esc_html_e($args['title']); ?>
            </p>
        <?php
    }

    function printPluginSettingsPageFieldNumColumns($args) {

        $options     = get_option($this->plugin_options_key);
        $option_name = $args['label_for'];
        $option_val  = $options[$option_name];
        if ($option_val == '') {
            $option_val = 3;
        }
        ?>

        <select
            id="<?php echo esc_attr($option_name); ?>"
            name="<?php echo esc_attr($this->plugin_options_key); ?>[<?php echo esc_attr($option_name); ?>]"
            >
            <?php for ($val = 1; $val <= 4; $val++) : ?>
            <option value="<?php echo $val; ?>" <?php if ($option_val == $val) {echo 'selected';} ?>><?php echo $val; ?></option>
            <?php endfor; ?>
        </select>

        <?php
    }

    function printPluginSettingPageFieldStyle($args) {

        $options     = get_option($this->plugin_options_key);
        $option_name = $args['label_for'];
        $option_val  = $options[$option_name];
        if ($option_val == '') {
            $option_val = 'default';
        }
        ?>
        <select
            id="<?php echo esc_attr($option_name); ?>"
            name="<?php echo esc_attr($this->plugin_options_key); ?>[<?php echo esc_attr($option_name); ?>]"
            >
            <?php foreach ($this->configs->styleDefs as $styleName => $styleDef) : ?>
            <option
                value="<?php echo esc_attr($styleName); ?>"
                <?php
                    echo
                        esc_attr(
                            isset($option_val) ?
                            (selected($option_val, $styleName, false)) :
                            ('')
                        );
                ?>
                >
                <?php echo esc_html($styleDef['label']); ?>
            </option>
        <?php endforeach; ?>
        </select>
        <?php
    }


}
