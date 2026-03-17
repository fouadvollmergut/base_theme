<?php 
  $animation = optionGet('animation');
  $slides = optionGet('slides');
  $slides = $slides ? $slides : 1;
?>

<section class="module grid">
  <div class="subgrid photobook end" <?php if ($animation) echo 'data-aos="fade-up"'; ?>>
    <?php include 'layouts/photobook.php'; ?>
  </div>
</section>