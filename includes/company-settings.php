<?php
  add_action('admin_init', 'custom_company_section');  

  function custom_company_section() {  
      add_settings_section(  
          'custom_company_information_section',
          'Company Informations',
          'custom_company_information_section_callback',
          'general'
      );
      
      add_settings_field(
          'custom_company_information', 
          'Company Information',
          'custom_company_information_callback',
          'general',
          'custom_company_information_section',
          array( 'custom_company_information')  
      );
      
      register_setting('general', 'custom_company_information');
  }

  function custom_company_information_section_callback() {
      echo '<p>Company Information shown in the footer of the website.</p>';  
  }

  function custom_company_information_callback($args) {  // Textbox Callback
    
    $settings = array( 'media_buttons' => false, 'quicktags' => false );
    $content = get_option($args[0]);
    $editor_id = 'custom_company_information';
    wp_editor($content, $editor_id, $settings);
  }



  add_action('admin_init', 'custom_company_recaptcha');  

  function custom_company_recaptcha() {  
      add_settings_section(  
          'custom_company_recaptcha_section',
          'Recaptcha',
          'custom_company_recaptcha_section_callback',
          'general'
      );

      add_settings_field(
          'custom_company_recaptcha_key', 
          'Recaptcha Site Key',
          'custom_company_recaptcha_key_callback',
          'general',
          'custom_company_recaptcha_section'
      );

      add_settings_field(
          'custom_company_recaptcha_endpoint', 
          'Recaptcha Endpoint',
          'custom_company_recaptcha_endpoint_callback',
          'general',
          'custom_company_recaptcha_section'
      );

      add_settings_field(
          'custom_company_recaptcha_api_key', 
          'Recaptcha API Key',
          'custom_company_recaptcha_api_key_callback',
          'general',
          'custom_company_recaptcha_section'
      );

      add_settings_field(
          'custom_company_recaptcha', 
          'Recaptcha verwenden?',
          'custom_company_recaptcha_callback',
          'general',
          'custom_company_recaptcha_section'
      );

      register_setting('general', 'custom_company_recaptcha_key');
      register_setting('general', 'custom_company_recaptcha_endpoint');
      register_setting('general', 'custom_company_recaptcha_api_key');
      register_setting('general', 'custom_company_recaptcha');
  }

  function custom_company_recaptcha_section_callback() {
      echo '<p>Recaptcha settings.</p>';  
  }

  function custom_company_recaptcha_callback() {
    $recaptcha = get_option('custom_company_recaptcha');
    $key = get_option('custom_company_recaptcha_key');
    $endpoint = get_option('custom_company_recaptcha_endpoint');
    $api_key = get_option('custom_company_recaptcha_api_key');

    if ($recaptcha) {
        if ($key && $endpoint && $api_key) {
            $value = 1;
        } else {
            $value = 0;
        }
    } else {
        $value = 0;
    }

    echo '<input type="checkbox" id="custom_company_recaptcha" name="custom_company_recaptcha" value="1"' . checked( 1, $value, false ) . ' />';
  }

  function custom_company_recaptcha_key_callback() {
    $value = get_option('custom_company_recaptcha_key');
    echo '<input type="text" id="custom_company_recaptcha_key" name="custom_company_recaptcha_key" class="regular-text ltr" value="' . esc_attr($value) . '" />';
  }

  function custom_company_recaptcha_endpoint_callback() {
    $value = get_option('custom_company_recaptcha_endpoint');
    echo '<input type="text" id="custom_company_recaptcha_endpoint" name="custom_company_recaptcha_endpoint" class="regular-text ltr" value="' . esc_attr($value) . '" />';
  }

  function custom_company_recaptcha_api_key_callback() {
    $value = get_option('custom_company_recaptcha_api_key');
    echo '<input type="text" id="custom_company_recaptcha_api_key" name="custom_company_recaptcha_api_key" class="regular-text ltr" value="' . esc_attr($value) . '" />';
  }