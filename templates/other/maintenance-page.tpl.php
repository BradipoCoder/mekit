<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page while offline.
 *
 * All the available variables are mirrored in html.tpl.php and page.tpl.php.
 * Some may be blank but they are provided for consistency.
 *
 * @see template_preprocess()
 * @see template_preprocess_maintenance_page()
 *
 * @ingroup themeable
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <!-- HTML5 element support for IE6-8 -->
  <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
  <![endif]-->
  <?php print $scripts; ?>
</head>
<body class="<?php print $classes; ?>">
  <div id="page" class="container">
    <div class="row">
      <div class="col-md-12">
        <div class="logo margin-b-1">
          <?php if (!empty($logo)): ?>
            <p class="text-center"><a href="<?php print $base_path; ?>" title="<?php print t('Home'); ?>" rel="home" id="logo">
              <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
            </a>
            </p>
          <?php endif; ?>
        </div>

        <div id="body" class="margin-b-2">
          <?php if (!empty($messages)): print $messages; endif; ?>
          
          <div id="content-content" class="text-center margin-b-2 lead">
            <?php print $content; ?>
          </div>

          <p class="text-center">
            <?php $opt2['attributes']['class'] = array('btn','btn-primary'); ?>
            <?php print l('Effettua il login','user', $opt2); ?>
          </p>
        </div>
      </div> 
    </div>
  </div>
</body>
</html>
