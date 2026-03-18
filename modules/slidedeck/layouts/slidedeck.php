<?php
  $items = optionGet('items');
  $items = explode(',', $items);
?>

<div class="content--slidedeck col-w6p1">
  <?php foreach ($items as $i=>$item): ?>
    <div class="gdymc_sortable_item slide" data-name="<?php echo substr(str_replace('-', '', $item), 0, 8); ?>" style="bottom: <?php echo (sizeof($items) * 50) - ($i * 50); ?>px; z-index: <?php echo sizeof($items) - $i; ?>; margin-top: <?php echo $i * 50; ?>px;">
      <?php if (contentCheck('image-' . $item)): ?>
        <div class="slide-image">
          <div class="imagebox">
            <?php contentCreate('image-' . $item, 'image', 'autoxauto'); ?>
          </div>
        </div>
      <?php endif; ?>
      

      <div class="slide-content">
        <?php if (contentCheck('headline-' . $item) || contentCheck('copy-' . $item)): ?>
          <div class="textbox">
            <?php contentCreate('headline-' . $item, 'h4/text', 'auto', 'h2'); ?>

            <?php contentCreate('copy-' . $item, 'text', '375'); ?>
          </div>
        <?php endif; ?>
        
        <?php if (contentCheck('button-' . $item)): ?>
          <div class="buttonbox">
            <?php contentCreate('button-' . $item, 'buttongroup'); ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

