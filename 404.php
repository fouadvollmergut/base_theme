<?php get_header(); ?>

<section class="grid module">
  <div class="subgrid not-found">
    <div class="card col-w4p2 background-dark">
      <div class="textbox">
        <h1>404 - Not Found</h1>
        <p><?php _e('Die Seite konnte nicht gefunden werden.', 'Theme'); ?></p>
      </div>
      <div class="buttonbox">
        <a href="<?php echo home_url(); ?>" class="button button-primary"><?php _e('Zur Startseite', 'Theme'); ?></a>
      </div>
    </div>
  </div>
</section>

<?php get_footer(); ?>