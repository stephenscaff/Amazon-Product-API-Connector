<?php

/**
 * Amazon Options Page
 * Creates a Wp options page to store apai keys/tag
 */
class AmznOptions {


  function __construct() {
    add_action('admin_init', array( $this, 'init') );
    add_action('admin_menu', array( $this, 'create_menu') );
  }

  function init() {
    $page = 'amzn_apai-options';
    $group = 'amzn_apai-options';

    add_settings_section(
      'amzn_apai-general',
      'General',
      '',
      $page
    );

    // Register our settings fields
    register_setting($group, 'amzn_apai-aws-access-key');
    register_setting($group, 'amzn_apai-aws-secret-key');
    register_setting($group, 'amzn_apai-associate-tag');

    // Access Key
    add_settings_field(
      'amzn_apai-aws-access-key',
      'AWS Access Key',
      array( $this, 'access_key_render'),
      $page,
      'amzn_apai-general'
    );

    // Secret Key
    add_settings_field(
      'amzn_apai-aws-secret-key',
      'AWS Secret Key',
      array( $this, 'secret_key_render'),
      $page,
      'amzn_apai-general'
    );

    // Associate Tag
    add_settings_field(
      'amzn_apai-associate-tag',
      'Associate Tag',
      array($this, 'associate_tag_render'),
      $page,
      'amzn_apai-general'
    );
  }


  /**
   * Field Output = AWS Access Key
   */
  function access_key_render() {

    if (esc_attr( get_option("amzn_apai-aws-access-key") ) != "") {
      echo '<input type="text" name="amzn_apai-aws-access-key" value="' . esc_attr( get_option("amzn_apai-aws-access-key") ) . '" />';
    }
    else {
      echo '<input type="text" name="amzn_apai-aws-access-key" />';
    }
  }

  /**
   * Field Output - Secret Key
   */
  function secret_key_render() {

    if (esc_attr( get_option("amzn_apai-aws-secret-key") ) != "") {
      echo '<input type="text" name="amzn_apai-aws-secret-key" value="' . esc_attr( get_option("amzn_apai-aws-secret-key") ) . '" />';
    }
    else {
      echo '<input type="text" name="amzn_apai-aws-secret-key" />';
    }
  }

  /**
   * Field Output - Associate Tag
   */
  function associate_tag_render() {

    if (esc_attr( get_option("amzn_apai-associate-tag") ) != "") {
      echo '<input type="text" name="amzn_apai-associate-tag" value="' . esc_attr( get_option("amzn_apai-associate-tag") ) . '" />';
    }
    else {
      echo '<input type="text" name="amzn_apai-associate-tag" />';
    }
  }

  /**
   * Create Menu Item
   */

  function create_menu() {

    add_submenu_page(
      'options-general.php',
      'Amazon Product Advertising API',
      'Amazon APAI',
      'administrator',
      'amzn_apai-options',
      array( $this, 'settings_view')
    );
  }

  /**
   * Settings Page View/Form
   */
  function settings_view() {
  ?>
  <div class="wrap">
    <h1>Amazon Product Advertising API</h1>

    <form method="post" action="options.php" method="post">
        <?php
        settings_fields( 'amzn_apai-options' );
        do_settings_sections( 'amzn_apai-options' );
        submit_button();
        ?>
    </form>
  </div>
  <?php
  }
}

new AmznOptions;
