<?php
  $seoPosition = optionGet('seo-position');
  $animation = optionGet('animation');
?>

<div class="content--text col-w6p1">
  <div class="banner-content">
    <div class="textbox" <?php if ($animation) echo 'data-aos="fade-left"'; ?>>
      <?php if (contentCheck('copy')): ?>
        <?php contentCreate('copy', $seoPosition . '/text', 'auto', 'h6'); ?>
      <?php endif; ?>

      <?php if (contentCheck('subline')): ?>
        <?php contentCreate('subline', 'span/text', 'auto'); ?>
      <?php endif; ?>
    </div>

    <div <?php if ($animation) echo 'data-aos="fade-right"'; ?>>
      <?php if (contentCheck('button')): ?>
        <?php contentCreate('button', 'buttongroup'); ?>
      <?php endif; ?>
    </div>
  </div>
</div>