<?php 
  $showLabels = !optionGet('labels') ? 'none' : '';
  $recipient = optionGet('recipient');
  $subject = optionGet('subject');
  $seoPosition = optionGet('seo-position');
  $animation = optionGet('animation');
?>

<div class="form--text col-w2p1" <?php if ($animation) echo 'data-aos="fade-up"'; ?>>
  <div class="textbox">
    <div>
      <?php if( contentCheck( 'headline' ) ): ?>
        <?php contentCreate( 'headline', $seoPosition . '/text', 'auto', 'h4'); ?>
      <?php endif; ?>

      <?php if( contentCheck( 'copy' ) ): ?>
        <?php contentCreate( 'copy', 'text' ); ?>
      <?php endif; ?>
    </div>

    <?php if( contentCheck( 'button' ) ): ?>
      <?php contentCreate( 'button', 'buttongroup' ); ?>
    <?php endif; ?>
  </div>
</div>

<form 
  class="form--form col-w4p3" 
  enctype="multipart/form-data"
  data-recipient="<?php echo esc_attr( $recipient ); ?>" 
  data-subject="<?php echo esc_attr( $subject ); ?>"
  data-action="fvt_form_send"
  data-ajax-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
  data-nonce="<?php echo esc_attr( wp_create_nonce( 'fvt_form_send' ) ); ?>"
  <?php if ($animation) echo 'data-aos="fade-up" data-aos-delay="100"'; ?>
>
  <div class="form--row">
    <div class="input-container flex column space-between">
      <label 
        class="<?php echo $showLabels; ?> required"
        for="name">
        <?php _e('Name*', 'Theme'); ?>
      </label>
      
      <input 
        type="text" 
        name="name" 
        placeholder="<?php _e('Name*', 'Theme'); ?>"
        autocomplete="family-name"
        required
      />
    </div>
  </div>

  <div class="form--row">
    <div class="input-container flex column space-between">
      <label 
        class="<?php echo $showLabels; ?> required"
        for="mail">
        <?php _e('E-Mail*', 'Theme'); ?>
      </label>
      
      <input 
        type="email" 
        name="mail" 
        placeholder="<?php _e('E-Mail*', 'Theme'); ?>"
        autocomplete="email"
        required
      />
    </div>

    <div class="input-container flex column space-between">
      <label 
        class="<?php echo $showLabels; ?>" 
        for="telefon">
        <?php _e('Telefon*', 'Theme'); ?>
      </label>

      <input 
        type="tel" 
        name="telefon" 
        placeholder="<?php _e('Telefon*', 'Theme'); ?>"
        autocomplete="tel"
        required
      />
    </div>
  </div>

  <div class="input-container flex column space-between">
    <label 
      class="<?php echo $showLabels; ?> required"
      for="message">
      <?php _e('Nachricht', 'Theme'); ?>
    </label>

    <textarea 
      name="message"
      rows="1"
      placeholder="<?php _e('Nachricht', 'Theme'); ?>"
      onkeyup="this.rows = this.value.split('\n').length"
      required
    ></textarea>
  </div>

   <div class="input-container flex column space-between">
    <label 
      class="<?php echo $showLabels; ?> required"
      for="file">
      <?php _e('Datei', 'Theme'); ?>
    </label>

    <input 
      type="file"
      name="file"
      required
    />

    <div class="filePreview"></div>
  </div>

  <div class="footer-container flex row space-between">
    <div class="flex row-static space-between">
      <input 
        type="checkbox" 
        id="privacy"
        name="privacy" 
        required
      />

      <label for="privacy">
        Ich habe die <a href="<?php echo get_privacy_policy_url(); ?>">Datenschutzerklärung</a> zur Kenntnis genommen. Ich stimme zu, dass meine Angaben zur Kontaktaufnahme und für Rückfragen dauerhaft gespeichert werden. Diese Einwilligung kann jederzeit mit Wirkung für die Zukunft widerrufen werden.
      </label>
    </div>

    <div class="gdymc_button_container">
      <div class="submit_underline">
        <input 
          class="button button-primary"
          name="submit"
          type="submit"
          required="false"
          value="<?php _e('Senden', 'Theme'); ?>"
        />
      </div>
    </div>
  </div>

  <span class="form-overlay form-overlay__success">
    <h3><?php _e('Die Nachricht wurde erfolgreich versendet.', 'Theme'); ?></h3>
    <?php _e('Wir melden uns zeitnah bei Ihnen.', 'Theme'); ?>
  </span>

  <span class="form-overlay form-overlay__failure">
    <h3><?php _e('Es ist ein Fehler aufgetreten.', 'Theme'); ?></h3>
    <?php _e('Bitte kontaktieren Sie uns telefonisch.', 'Theme'); ?>
  </span>
</form>