<?php
/**
 * @file
 * Template file for field_slideshow_controls
 *
 *
 */
?>
<div id="field-slideshow-<?php print $slideshow_id; ?>-controls" class="field-slideshow-controls">
  <a href="#" class="prev"><i class="fa fa-angle-left fa-2x"></i></a>
  <?php if (!empty($controls_pause)) : ?>
    <a href="#" class="play"><?php print t('Play'); ?></a>
    <a href="#" class="pause"><?php print t('Pause'); ?></a>
  <?php endif; ?>
  <a href="#" class="next"><i class="fa fa-angle-right fa-2x"></i></a>
</div>
