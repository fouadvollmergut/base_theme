<?php
  $seoPosition = optionGet('seo-position');
  $background = optionGet('background');
?>

<div class="content--image col-w8p2">
  <?php if (contentCheck('image')): ?>
    <?php contentCreate('image', 'image'); ?>
  <?php endif; ?>
</div>

<div class="subgrid">
  <div class="content--text col-w6p1 <?php echo $background; ?>" <?php if ($animation) echo 'data-aos="zoom-in"'; ?>>
    <div class="textbox">
      <?php if (contentCheck('subline')): ?>
        <?php contentCreate('subline', 'span/text', 'auto', 'subline'); ?>
      <?php endif; ?>

      <?php if (contentCheck('headline')): ?>
        <?php contentCreate('headline', $seoPosition . '/text', 'auto', 'h1'); ?>
      <?php endif; ?>

      <?php if (contentCheck('copy')): ?>
        <?php contentCreate('copy', 'span/text', 'auto'); ?>
      <?php endif; ?>
    </div>

    <?php if (contentCheck('button')): ?>
      <?php contentCreate('button', 'buttongroup'); ?>
    <?php endif; ?>
  </div>
</div>