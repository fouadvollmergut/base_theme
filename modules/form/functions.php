<?php

  /**
   * AJAX handler for the contact form.
   *
   * Receives the FormData posted by `modules/form/assets/script.js`,
   * validates the required fields server-side and dispatches the message
   * via WordPress' native `wp_mail()` function. Returns a JSON response
   * the client can use to toggle the success / failure overlays.
   */
  function fvt_form_send_handler () {
    check_ajax_referer( 'fvt_form_send', 'nonce' );

    $name      = isset( $_POST['name'] )      ? sanitize_text_field( wp_unslash( $_POST['name'] ) )      : '';
    $mail      = isset( $_POST['mail'] )      ? sanitize_email( wp_unslash( $_POST['mail'] ) )           : '';
    $telefon   = isset( $_POST['telefon'] )   ? sanitize_text_field( wp_unslash( $_POST['telefon'] ) )   : '';
    $message   = isset( $_POST['message'] )   ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
    $privacy   = isset( $_POST['privacy'] )   && $_POST['privacy'];
    $recipient = isset( $_POST['recipient'] ) ? sanitize_email( wp_unslash( $_POST['recipient'] ) )      : '';
    $subject   = isset( $_POST['subject'] )   ? sanitize_text_field( wp_unslash( $_POST['subject'] ) )   : '';

    if ( empty( $name ) || empty( $mail ) || ! is_email( $mail ) || empty( $telefon ) || empty( $message ) || ! $privacy ) {
      wp_send_json_error( array( 'message' => __( 'Bitte füllen Sie alle Pflichtfelder aus.', 'Theme' ) ), 400 );
    }

    if ( empty( $recipient ) || ! is_email( $recipient ) ) {
      $recipient = sanitize_email( get_option( 'fvt_ct_mail_recipient' ) );
    }

    if ( empty( $recipient ) || ! is_email( $recipient ) ) {
      $recipient = sanitize_email( get_option( 'admin_email' ) );
    }

    if ( empty( $subject ) ) {
      $subject = __( 'Neue Nachricht von der Website', 'Theme' );
    }

    $body  = sprintf( "%s: %s\n", __( 'Name', 'Theme' ),    $name );
    $body .= sprintf( "%s: %s\n", __( 'E-Mail', 'Theme' ),  $mail );
    $body .= sprintf( "%s: %s\n", __( 'Telefon', 'Theme' ), $telefon );
    $body .= "\n";
    $body .= sprintf( "%s:\n%s\n", __( 'Nachricht', 'Theme' ), $message );

    $headers = array(
      'Content-Type: text/plain; charset=UTF-8',
      sprintf( 'Reply-To: %s <%s>', $name, $mail ),
    );

    $attachments = array();

    if ( ! empty( $_FILES['file']['tmp_name'] ) && UPLOAD_ERR_OK === (int) $_FILES['file']['error'] && is_uploaded_file( $_FILES['file']['tmp_name'] ) ) {
      if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
      }

      $upload = wp_handle_upload( $_FILES['file'], array(
        'test_form' => false,
        'action'    => 'fvt_form_send',
        // Restrict the attachment to a small set of safe document/image
        // types so the endpoint cannot be used to upload executable files.
        'mimes'     => array(
          'jpg|jpeg|jpe' => 'image/jpeg',
          'png'          => 'image/png',
          'gif'          => 'image/gif',
          'webp'         => 'image/webp',
          'pdf'          => 'application/pdf',
          'doc'          => 'application/msword',
          'docx'         => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
          'odt'          => 'application/vnd.oasis.opendocument.text',
          'txt'          => 'text/plain',
        ),
      ) );

      if ( ! empty( $upload['file'] ) ) {
        $attachments[] = $upload['file'];
      }
    }

    $sent = wp_mail( $recipient, $subject, $body, $headers, $attachments );

    // Clean up the temporarily uploaded attachment.
    foreach ( $attachments as $attachment ) {
      if ( file_exists( $attachment ) ) {
        wp_delete_file( $attachment );
      }
    }

    if ( ! $sent ) {
      wp_send_json_error( array( 'message' => __( 'Die Nachricht konnte nicht versendet werden.', 'Theme' ) ), 500 );
    }

    wp_send_json_success();
  }

  add_action( 'wp_ajax_fvt_form_send',        'fvt_form_send_handler' );
  add_action( 'wp_ajax_nopriv_fvt_form_send', 'fvt_form_send_handler' );

  add_action( 'gdymc_module_options_settings', function ( $module ) {
    $defaultRecipient = get_option('fvt_ct_mail_recipient');

    if( $module->type == gdymc_module_name( __FILE__ ) ):
      optionInput( 'subject', array(
        'type' => 'text',
        'label' => __( 'Betreff', 'Theme' ),
        'default' => 'Neue Nachricht von der Website',
      ), $module->id );

      optionInput( 'recipient', array(
        'type' => 'text',
        'label' => __( 'Empfänger', 'Theme' ),
        'default' => $defaultRecipient
      ), $module->id );

      optionInput( 'labels', array(
        'type' => 'select',
        'default' => false,
        'label' => __( 'Labels anzeigen', 'Theme' ),
        'options' => array(
          true => __( 'Ja', 'Theme' ),
          false => __( 'Nein', 'Theme' ),
        ),
      ), $module->id );
    endif;
  });

?>