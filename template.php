<?php

/**
 * @file
 * template.php
 */

/**
 * load js.php | funzioni che caricano vari javascript
 */
require('js.php');

/**
 * Implements template_preprocess_node();
 */
function mekit_preprocess_node(&$vars){

  $node = $vars['node'];

  // Erase default hook suggestions
  $vars['theme_hook_suggestions'] = array();
  // aggiungo hook suggestion nuovi
  $vars['theme_hook_suggestions'][] = 'node__' . $vars['view_mode'];
  $vars['theme_hook_suggestions'][] = 'node__' . $vars['view_mode'] . '__' . $vars['nid'];
  $vars['theme_hook_suggestions'][] = 'node__' . $vars['type'];
  $vars['theme_hook_suggestions'][] = 'node__' . $vars['type'] . '__' . $vars['nid'];
  $vars['theme_hook_suggestions'][] = 'node__' . $vars['type'] . '__' . $vars['view_mode'];
  $vars['theme_hook_suggestions'][] = 'node__' . $vars['type'] . '__' . $vars['view_mode'] . '__' . $vars['nid'];
  //dpm($vars['theme_hook_suggestions']);

  $vars['classes_array'][] = 'node-' . $vars['view_mode'];
  
  if ($vars['view_mode'] == 'child'){
    hide($vars['content']['children']);
  }

  // Add classes by taxonomy term
  if (isset($node->field_ref_cat['und'][0]['tid'])){
    $cats = $node->field_ref_cat['und'];
    foreach ($cats as $key => $cat) {
      $vars['classes_array'][] = 'node-tid-' . $cat['tid'];
    }
  }

  if (isset($node->field_ref_tag['und'][0]['tid'])){
    $tags = $node->field_ref_tag['und'];
    foreach ($tags as $key => $tag) {
      $vars['classes_array'][] = 'node-tid-' . $tag['tid'];
    }
  }
}

/**
 * Implements template_preprocess_page();
 * Aggiungo una variabile is_page, per capire quali titoli aggiungere
 */
function mekit_preprocess_page(&$variables){
  //aggiungo la variabile "is_page"
  $is_page = false;
  if (isset($variables['node'])){
    $node = $variables['node'];
  } else {
    $is_page = true;
  }
  $variables['is_page'] = $is_page;

  $variables['navbar_brand_classes'] = false;
  if (isset($variables['site_slogan'])){
    if (!empty($variables['site_slogan'])){
      $variables['navbar_brand_classes'] = 'with-slogan';
    }
  }
}

//aggiungo le classi btn ai link utili
/* TO DROP?
function mekit_links($variables){
  $links = $variables['links'];
  $attributes = $variables['attributes'];
  $heading = $variables['heading'];
  global $language_url;
  $output = '';

  if (count($links) > 0) {
    $output = '';

    // Treat the heading first if it is present to prepend it to the
    // list of links.
    if (!empty($heading)) {
      if (is_string($heading)) {
        // Prepare the array that will be used when the passed heading
        // is a string.
        $heading = array(
          'text' => $heading,
          
          // Set the default level of the heading.
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(array('class' => $heading['class']));
      }
      $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
    }

    $output .= '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = array($key);

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class[] = 'first';
      }
      if ($i == $num_links) {
        $class[] = 'last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page())) && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';

      //cerco se tra le classi, c'è la classe 'comment-add'
      if (in_array('comment-add', $class)){
        $link['attributes']['class'] = array('btn', 'btn-default', 'btn-sm', 'no-active');
      }

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}
*/

/**
 * Overrides theme_menu_local_tasks().
 * temizzo i pulsanti di editing alla bootstrap maniera
 */
function mekit_menu_local_tasks(&$variables) {
  $output = '';

  if (!empty($variables['primary'])) {
    $variables['primary']['#prefix'] = '<h2 class="element-invisible">' . t('Primary tabs') . '</h2>';
    $variables['primary']['#prefix'] .= '<div class="tabs--primary">';
    $variables['primary']['#suffix'] = '</div>';
    $output .= drupal_render($variables['primary']);
  }

  if (!empty($variables['secondary'])) {
    $variables['secondary']['#prefix'] = '<h2 class="element-invisible">' . t('Secondary tabs') . '</h2>';
    $variables['secondary']['#prefix'] .= '<div class="tabs--secondary">';
    $variables['secondary']['#suffix'] = '</div>';
    $output .= drupal_render($variables['secondary']);
  }

  return $output;
}

/**
 * Overrides theme_menu_local_task().
 */
function mekit_menu_local_task($variables) {
  $link = $variables['element']['#link'];
  $link_text = $link['title'];
  $classes = array();

  if (!empty($variables['element']['#active'])) {
    $link['localized_options']['attributes']['class'][] = 'active';
  }

  $link['localized_options']['attributes']['class'][] = 'btn';
  $link['localized_options']['attributes']['class'][] = 'btn-default';

  $classes = implode(' ' , $link['localized_options']['attributes']['class']);
  $url = url($link['href'], $link['localized_options']);

  return l($link_text, $link['href'], $link['localized_options']) . "\n";
}

/**
 * implements theme_preprocess_image();
 * aggiungo la classe img-responsive a tutte le immagini
 */
function mekit_preprocess_image(&$variables) {
  $variables['attributes']['class'][] = 'img-responsive';
}

/**
 * rimuovo del markup inutile di bootstrap
 */
function mekit_menu_tree__primary(&$vars) {
  return $vars['tree'];
}

function mekit_menu_tree__devel(&$vars){
  return '<ul class="menu-devel">' . $vars['tree'] . '</ul>';
}

/**
 * Implements hook_preprocess_menu_link();
 * Aggiunge alcune classi alle voci di menu del menu devel
 */
function mekit_preprocess_menu_link(&$vars){
  if (isset($vars['element']['#original_link']['menu_name'])){
    if ($vars['element']['#original_link']['menu_name'] == 'devel'){
      $vars['element']['#localized_options']['attributes']['class'][] = 'btn';
      $vars['element']['#localized_options']['attributes']['class'][] = 'btn-xs';
      $vars['element']['#localized_options']['attributes']['class'][] = 'btn-default';
    }
  }
}


/**
 * Implements template_preprocess_field().
 */
function mekit_preprocess_field(&$vars, $hook) {
  // Aggiungo hook suggestion per i tipi di field
  array_unshift($vars['theme_hook_suggestions'], 'field__' . $vars['element']['#field_type']);

  // Add line breaks to plain text textareas.
  if ($vars['element']['#field_type'] == 'text_long'){
    //dpm($vars['element']);
    if (isset($vars['element']['#items'][0]['format'])) {
      if ($vars['element']['#items'][0]['format'] == null) {
        if (isset($vars['element']['#formatter'])){
          array_unshift($vars['theme_hook_suggestions'], 'field__' . $vars['element']['#field_type'] . '__' . $vars['element']['#formatter']);
        }
      }
    } else {
      if (isset($vars['element']['#formatter'])){
        array_unshift($vars['theme_hook_suggestions'], 'field__' . $vars['element']['#field_type'] . '__' . $vars['element']['#formatter']);
      }
      $vars['items'][0]['#markup'] = nl2br($vars['items'][0]['#markup']);
    }
  }
}

/**
 * implements theme_field();
 * pulisco la renderizzazione di tutti i field video
 */
function mekit_field__video_embed_field($variables) {
  $output = '';
  foreach ($variables['items'] as $delta => $item) {
    
    if (isset($item['#theme']) && $item['#theme'] == 'image_formatter'){
      $output .= '<div class="video-image">' . drupal_render($item) . '</div>';
    } else {

      $description = false;
      if (isset($item['#suffix'])){
        unset($item['#suffix']);
        if (isset($variables['element']['#items'][$delta]['description'])){
          $description = '<p>' . $variables['element']['#items'][$delta]['description'] . '</p>';
        }
      }

      $output .= '<div class="' . $variables['classes'] . '">' . drupal_render($item) . '</div>';
      if ($description) {
        $output .= $description;
      } 
    }
  }
  return $output;
}

/**
 * implements theme_field();
 * pulisco la renderizzazione di tutti i field datetime
 */
function mekit_field__datetime($vars) {
  $output = '';
  $output = '<div class="' . $vars['classes'] . '"><p>';
  foreach ($vars['items'] as $delta => $item) {
    $output .= drupal_render($item) . '<br />';
  }
  $output .= '</p></div>';
  return $output;
}

/**
 * pulisco la renderizzazione di tutti i field text long plain text
 */
function mekit_field__text_long__text_plain($variables){
  //dpm($variables);
  $output = '';
  foreach ($variables['items'] as $delta => $item) {
    $output .= $item['#markup'];  
  }
  return $output;
}

/**
 * implements theme_field();
 * aggiungo classi per renderizzare i tags
 */
function mekit_field__field_ref_tag($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<div class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
  }

  // Render the items.
  $output .= '<ul class="tag-list field-items"' . $variables['content_attributes'] . '>';
  $opt['attributes']['class'] = array('btn', 'btn-default', 'btn-xs');
  foreach ($variables['items'] as $delta => $item) {
    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
    $output .= '<li class="' . $classes . '"' . $variables['item_attributes'][$delta] . '>';
      $output .= l($item['#title'], $item['#href'], $opt); 
    $output .= '</li>';
  }
  $output .= '</ul>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

  return $output;
}

// ** FIELD SLIDESHOW **
// ---------------------

/**
 * Implements template_preprocess().
 */
function mekit_preprocess_field_slideshow(&$variables) {
  // Add js variables
  $js_variables = $variables["js_variables"];

  drupal_add_js(array('field_slideshow' => array('field-slideshow-' . $variables['slideshow_id'] => $js_variables)), 'setting');

  // Add the JQuery plugins and the JS code
  if (module_exists('libraries')) {
    $path = libraries_get_path('jquery.cycle');
    if (file_exists($path . '/jquery.cycle.all.min.js')) drupal_add_js($path . '/jquery.cycle.all.min.js');
    elseif (file_exists($path . '/jquery.cycle.all.js')) drupal_add_js($path . '/jquery.cycle.all.js');

    if (isset($js_variables['pager']) && $js_variables['pager'] == 'carousel') {
      $path = libraries_get_path('jquery.jcarousel');
      if (file_exists($path . '/lib/jquery.jcarousel.min.js')) drupal_add_js($path . '/lib/jquery.jcarousel.min.js');
      elseif (file_exists($path . '/lib/jquery.jcarousel.js')) drupal_add_js($path . '/lib/jquery.jcarousel.js');
    }

    $path = libraries_get_path('jquery.imagesloaded');
    if (file_exists($path . '/jquery.imagesloaded.min.js')) drupal_add_js($path . '/jquery.imagesloaded.min.js');
    elseif (file_exists($path . '/jquery.imagesloaded.js')) drupal_add_js($path . '/jquery.imagesloaded.js');

    drupal_add_js(drupal_get_path('theme', 'smile') . '/templates/slideshow/field_slideshow.js');
  }

  // Add css
  drupal_add_css(drupal_get_path('module', 'field_slideshow') . '/field_slideshow.css');

  // Generate classes
  $variables['classes_array'][] = 'field-slideshow-' . $variables['slideshow_id'];
  $variables['classes_array'][] = 'effect-' . $variables['js_variables']['fx'];
  $variables['classes_array'][] = 'timeout-' . $variables['js_variables']['timeout'];
  if (isset($variables['pager']) && $variables['pager'] != '') $variables['classes_array'][] = 'with-pager';
  if (isset($variables['controls'])) $variables['classes_array'][] = 'with-controls';

  // Change order if needed
  if (isset($variables['order'])) {
    if ($variables['order'] == 'reverse') $variables['items'] = array_reverse($variables['items']);
    elseif ($variables['order'] == 'random') shuffle($variables['items']);
  }

  // Generate slides
  $field_slideshow_zebra = 'odd';
  $variables['slides_max_width'] = 0;
  $variables['slides_max_height'] = 0;
  $slide_theme = (isset($variables['breakpoints']) && isset($variables['breakpoints']['mapping']) && !empty($variables['breakpoints']['mapping'])) ? 'picture' : 'image_style';
  foreach ($variables['items'] as $num => $item) {
    // Generate classes
    $classes = array('field-slideshow-slide', 'field-slideshow-slide-' . (1+$num));
    $field_slideshow_zebra = ($field_slideshow_zebra == 'odd') ? 'even' : 'odd';
    $classes[] = $field_slideshow_zebra;
    if ($num == 0) $classes[] = 'first';
    elseif ($num == count($variables['items']) - 1) $classes[] = 'last';
    $variables['items'][$num]['classes'] = implode(' ', $classes);

    // Generate the image html
    $image = array();
    $image['path'] = $item['uri'];
    $image['attributes']['class'] = array('field-slideshow-image', 'field-slideshow-image-' . (1+$num));
    $image['alt'] = isset($item['alt']) ? $item['alt'] : '';
    if (isset($item['width']) && isset($item['height'])) {
      $image['width'] = $item['width'];
      $image['height'] = $item['height'];
    }
    else {
      $image_dims = getimagesize($image['path']);
      $image['width'] = $image_dims[0];
      $image['height'] = $image_dims[1];
    }
    if (isset($item['title']) && drupal_strlen($item['title']) > 0) $image['title'] = $item['title'];
    if (isset($variables['image_style']) && $variables['image_style'] != '') {
      $image['style_name'] = $variables['image_style'];
      $image['breakpoints'] = $variables['breakpoints'];
      $variables['items'][$num]['image'] = theme($slide_theme, $image);
    }
    else {
      $variables['items'][$num]['image'] = theme('image', $image);
    }

    // Get image sizes and keeps the bigger ones, so height is correctly calculated by Cycle
    $dimensions = array(
      'width' => $image['width'],
      'height' => $image['height'],
    );
    if (isset($variables['image_style']) && $variables['image_style'] != '') {
      if (function_exists('image_style_transform_dimensions')) {
        image_style_transform_dimensions($image['style_name'], $dimensions);
      }
      // manual calculation if Drupal < 7.9 or image_style_transform_dimensions doesn't work
      if (!function_exists('image_style_transform_dimensions') || !is_numeric($dimensions['width'])) {
        $thumbnail_path = image_style_path($variables['image_style'], $image['path']);
        // if thumbnail is not generated, do it, so we can get the dimensions
        if (!file_exists($thumbnail_path)) {
          image_style_create_derivative(image_style_load($variables['image_style']), $image['path'], $thumbnail_path);
        }
        $thumbnail_dims = getimagesize($thumbnail_path);
        $dimensions = array(
          'width' => $thumbnail_dims[0],
          'height' => $thumbnail_dims[1],
        );
      }
    }

  // If the theme function hasn't added width and height attributes to the image, add them
  if (strpos($variables['items'][$num]['image'], 'width=') === FALSE) {
    $variables['items'][$num]['image'] = drupal_substr($variables['items'][$num]['image'], 0, -2) . "width=\"$dimensions[width]\" height=\"$dimensions[height]\" />";
  }

  // Keeps biggest dimensions
  if ($dimensions['width'] > $variables['slides_max_width']) $variables['slides_max_width'] = $dimensions['width'];
  if ($dimensions['height'] > $variables['slides_max_height']) $variables['slides_max_height'] = $dimensions['height'];

    // Add links if needed
    $links = array('path' => 'image');
    if (isset($item['caption']) && $item['caption'] != '') $links['caption_path'] = 'caption';
    // Loop thru required links (because image and caption can have different links)
    foreach ($links as $link => $out) {
      if (!empty($item[$link])) {
        $path = $item[$link]['path'];
        $options = $item[$link]['options'];
        // When displaying an image inside a link, the html option must be TRUE.
        $options['html'] = TRUE;
        // Generate differnet rel attribute for image and caption, so colorbox doesn't double the image list
        if (isset($options['attributes']['rel'])) $options['attributes']['rel'] .= $out;
        $options = array_merge($options, drupal_parse_url($path));
        $variables['items'][$num][$out] = l($variables['items'][$num][$out], $options['path'], $options);
      }
    }
  }

  // Don't add controls if there's only one image
  if (count($variables['items']) == 1) {
    $variables['controls'] = '';
    $variables['pager'] = '';
  }
}

// ** FIELD FILE **
// ----------------

/**
 * Implements hook_field($variables);
 */
function mekit_field__file($vars) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$vars['label_hidden']) {
    $output .= '<div class="field-label"' . $vars['title_attributes'] . '>' . $vars['label'] . ':&nbsp;</div>';
  }

  // Render the items.
  $output .= '<div class="field-items"' . $vars['content_attributes'] . '>';
  foreach ($vars['items'] as $delta => $item) {
    $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
    $output .= '<div class="' . $classes . '"' . $vars['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
  }
  $output .= '</div>';

  // Render the top-level DIV.
  $output = '<div class="' . $vars['classes'] . '"' . $vars['attributes'] . '>' . $output . '</div>';

  return $output;
}

/**
 * Implements theme_file_link($variables)
 * file.module
 */
function mekit_file_link($variables) {
  $file = $variables['file'];
  $icon_directory = $variables['icon_directory'];

  $url = file_create_url($file->uri);
  $icon = theme('file_icon', array('file' => $file, 'icon_directory' => $icon_directory));

  // Set options as per anchor format described at
  // http://microformats.org/wiki/file-format-examples
  $options = array(
    'attributes' => array(
      'type' => $file->filemime . '; length=' . $file->filesize,
      'target' => '_blank',
    ),
    'html' => true,
  );

  // Use the description as the link text if available.
  if (empty($file->description)) {
    $link_text = $icon . ' ' . $file->filename;
    $description = '';
    $link = l($icon . ' <span class="file-name">' . $file->filename . '</span>', $url, $options);
  }
  else {
    // Con descrizione
    $link_text = $icon . ' ' . $file->description;
    $options['attributes']['title'] = check_plain($file->filename);
    $link = l($link_text, $url, $options);
    //$link = '<h4 class="spazio-5">' . $file->description . '</h4>';
    //$link .=  l( $icon . ' <span class="file-name">' . $file->filename . '</span>', $url, $options);
  }

  return $link;
}

/**
 * Implements theme_file_icon($varibles);
 * file.module
 */
function mekit_file_icon($variables) {
  $file = $variables['file'];
  $icon_directory = $variables['icon_directory'];
  $mime = check_plain($file->filemime);
  //dpm($mime);

  switch ($mime) {
    case 'image/jpeg':
      $fa_class = 'fa-file-image-o';
      break;

    case 'audio/mpeg':
      $fa_class = 'fa-file-audio-o';
      break;

    case 'application/pdf':
      $fa_class = 'fa-file-text-o';
      break;
    
    default:
      $fa_class = 'fa-file-o';
      break;
  }
  
  return '<i class="fa ' . $fa_class . '"></i>';
}

/**
 * Override node preview to remove trimmed teaser version.
 */
function mekit_node_preview($variables) {
  $node = $variables['node'];
  $output = '<div class="preview">';
  $preview_trimmed_version = FALSE;
  $elements = node_view(clone $node, 'child');
  $trimmed = drupal_render($elements);
  $elements = node_view($node, 'full');
  $full = drupal_render($elements);
  // Do we need to preview trimmed version of post as well as full version?
  if ($trimmed != $full) {
    // drupal_set_message(t('The trimmed version of your post shows what your post looks like when promoted to the main page or when exported for syndication.<span class="no-js"> You can insert the delimiter "&lt;!--break--&gt;" (without the quotes) to fine-tune where your post gets split.</span>'));
    //$output .= '<h3>' . t('Preview trimmed version') . '</h3>';
    $output .= '<hr><div class="row">';
    $output .= $trimmed;
    //$output .= '<h3>' . t('Preview full version') . '</h3>';
    $output .= '</div><hr>';
    $output .= $full;
  }
  else {
    $output .= $full;
  }
  //drupal_set_message(t('Preview: ' . $node->title));
  $output .= "</div>\n";
  return $output;
}

/**
 * Process variables for taxonomy-term.tpl.php.
 */
function mekit_preprocess_taxonomy_term(&$vars){
  $vid = $vars['vid'];
  $vocabulary = taxonomy_vocabulary_load($vid);
  $this_tid = $vars['tid'];
  $data = get_taxonomy_menu($vid, $this_tid);
  $vars['content']['menu_vocabulary'] = $data;
  $vars['vocabulary_name'] = $vocabulary->name;
}

/**
 * crea il menu del vocabolario
 */
function get_taxonomy_menu($vid, $this_tid){
  $taxonomy = taxonomy_get_tree($vid);
  $data = false;
  if ($taxonomy){
    foreach ($taxonomy as $key => $term) {
      // Carica il termine in lingua
      $term = taxonomy_term_load($term->tid);

      $opt = array();
      $opt['attributes']['class'] = array('btn', 'btn-default');
      $opt['attributes']['class'][] = 'btn-list__btn';
      $opt['attributes']['class'][] = 'menu-tt__' . $term->tid;
      //$opt['attributes']['data-filter'] = '.node-tid-' . $term->tid;
      if ($term->tid == $this_tid){
        $opt['attributes']['class'][] = 'btn-taxonomy-term--active';
      }
      //$opt['query']['filter'] = $term->tid;
      $voci[$key]['#markup'] = l($term->name, 'taxonomy/term/' . $term->tid , $opt);
    }
    if (isset($voci)) {
      $data['list']= array(
        '#prefix' => '<div class="menu-tt btn-list">',
        '#suffix' => '</div>',
        'voci' => $voci,
      );
    }
  }
  return $data;
}

/**
 * Implements theme_manualcrop_croptool_inline()
 */
function mekit_manualcrop_croptool_inline($variables) {
  $output = '<div ' . drupal_attributes($variables['attributes']) . '>';

  $output .= '<div class="manualcrop-image-holder">';
  $output .= theme('image', $variables['image']);
  $output .= '</div>';

  $output .= '<div class="clearfix">';

  if ($variables['crop_info']) {
    $output .= '<div class="manualcrop-selection-info hidden">';
    $output .= '<div class="manualcrop-selection-label manualcrop-selection-xy">';
    $output .= '<div class="manualcrop-selection-label-content">';
    $output .= '<span class="manualcrop-selection-x">-</span> x <span class="manualcrop-selection-y">-</span>';
    $output .= '</div>';
    $output .= '</div>';
    $output .= '<div class="manualcrop-selection-label manualcrop-selection-width">';
    $output .= '<div class="manualcrop-selection-label-content">-</div>';
    $output .= '</div>';
    $output .= '<div class="manualcrop-selection-label manualcrop-selection-height">';
    $output .= '<div class="manualcrop-selection-label-content">-</div>';
    $output .= '</div>';
    $output .= '</div>';
  }

  if ($variables['instant_preview']) {
    $output .= '<div class="manualcrop-instantpreview"></div>';
  }

  if ($variables['crop_info']) {
    $output .= '<div class="manualcrop-style-info">';
    $output .= t('Image style') . ': <span class="manualcrop-style-name">&nbsp;</span><br />';
    $output .= '</div>';
  }

  $output .= '<div class="manualcrop-buttons">';
    $output .= '<input type="button" value="' . t('Save') . '" class="btn btn-xs btn-primary manualcrop-button manualcrop-close form-submit" onmousedown="ManualCrop.closeCroptool();" />';
    $output .= '<input type="button" value="' . t('Revert selection') . '" class="btn btn-xs btn-default manualcrop-button manualcrop-reset form-submit" onmousedown="ManualCrop.resetSelection();" />';
    $output .= '<input type="button" value="' . t('Remove selection') . '" class="btn btn-xs btn-default manualcrop-button manualcrop-clear form-submit" onmousedown="ManualCrop.clearSelection();" />';
    $output .= '<input type="button" value="' . t('Maximize selection') . '" class="btn btn-xs btn-default manualcrop-button manualcrop-maximize form-submit" onmousedown="ManualCrop.maximizeSelection();" />';
    $output .= '<input type="button" value="' . t('Cancel') . '" class="btn btn-default btn-xs manualcrop-button manualcrop-cancel form-submit" onmousedown="ManualCrop.closeCroptool(true);" />';
  $output .= '</div>';

  $output .= '</div>';
  $output .= '</div>';

  return $output;
}

/**
 * Restringo il form del login
 */
function mekit_form_user_login_alter(&$form, &$form_state) {
  $form['#prefix'] = '<div class="row"><div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">';
  $form['#suffix'] = '</div></div>';
}

/**
 * Menu callback for 'icon_bundle_list'.
 */
function mekit_icon_bundle_list_alter(&$build) {

  foreach ($build as $key => $icon) {
    $letter = substr($icon['#attributes']['title'],0,1);
    $new_build[$letter][$key] = $icon;
    $new_build[$letter][$key]['#attributes']['class'] = array('fa-lg','fa-fw');
    $new_build[$letter][$key]['#prefix'] = '<div><p>';
    
    $opt['attributes']['class'] = array('small');
    $opt['attributes']['data-toggle'] = 'collapse';
    $opt['fragment'] = 'tag-icon-' . $key;
    $opt['external'] = true;

    $button = l($icon['#attributes']['title'], '' . $key , $opt);
    
    $well = '<div class="collapse" id="tag-icon-' . $key . '">';
    $well .= '<div class="well well-sm"><p class="small">[icon:fontawesome:' . $icon['#attributes']['title'] . ']</p>';
    $well .= '</div></div>';

    $new_build[$letter][$key]['#suffix'] = ' ' . $button . '</p>'. $well . '</div>';
  }

  foreach ($new_build as $letter => $value) {
  
    unset($new_build[$letter]);

    $value = get_array_columns($value, 3);
    foreach ($value as $key => $col) {
      $new_build[$letter][$key] = $col;
      $new_build[$letter][$key]['#prefix'] = '<div class="col-md-4">';
      $new_build[$letter][$key]['#suffix'] = '</div>';
    }

    $new_build[$letter]['#prefix'] = '<div class="row wrapper-icon-by-letter"><div class="col-md-12"><h1 class="text-uppercase">' . $letter . '</h1></div>';
    $new_build[$letter]['#suffix'] = '</div>';
    
  }

  $build = array(
    '#prefix' => '<div class="margin-b-2">',
    'icons' => $new_build,
    '#suffix' => '</div>',
  );
}

function get_array_columns($array, $columns){     
  $columns_map = array();
  for($i=0; $i<$columns; $i++){ $columns_map[] = 0; }//init columns
  
  //create map
  $count = count($array);
  $position = 0;
  while($count > 0){
      $columns_map[$position]++;
      $position = ($position < $columns-1) ? ++$position : 0;
      $count--;
  }

  //chunk the array based on map
  $chunked = array();
  foreach($columns_map as $map){
      $chunked[] = array_splice($array,0,$map);
  }
  
  return $chunked;
}

// ** THEME **
// -----------
/**
 * Implements hook_theme().
 */
function mekit_theme(){
  return array(
    'smiletrap_panel' => array(
      'variables' => array(
        'title' => NULL,
        'content' => NULL,
        'id' => NULL,
        'options' => NULL,
      ),
      'function' => 'theme_smiletrap_panel',
    ),
    'smiletrap_accordion' => array(
      'variables' => array(
        'id' => NULL,
        'elements' => NULL,
        'options' => NULL,
      ),
      'function' => 'theme_smiletrap_accordion',
    ),
    'smiletrap_navbar_dropdown' => array(
      'variables' => array(
        'id' => NULL,
        'title' => NULL,
        'elements' => NULL,
        'options' => NULL,
      ),
      'function' => 'theme_smiletrap_navbar_dropdown',
    ),
    'smiletrap_navbar_dropdown_item' => array(
      'variables' => array(
        'id' => NULL,
        'item' => NULL
      ),
      'function' => 'theme_smiletrap_navbar_dropdown_item',
    ),
  );
}

/**
 * theme function to render a bootstrap accordion
 * 
 * $id : id univo dell'accordion
 * $elements : un array di smiletrap_panel
 *
 * $options : array
 *  - fade : aggiunge effetto fade
 */
function theme_smiletrap_accordion($vars){
  $data = array(
    '#prefix' => '<div id="' . $vars['id'] .'" class="panel-group">',
    '#suffix' => '</div>',
  );

  $first = true;
  foreach ($vars['elements'] as $key => $element) {
    // Il primo panel è espanso
    if ($first){
      $element['#options']['expanded'] = TRUE;
      $first = false;
    }

    if (isset($vars['options']['fade']) && $vars['options']['fade']){
      $element['#options']['fade'] = true;
    }

    $element['#options']['parent-id'] = $vars['id'];
    $data[$key] = $element;
  }

  return render($data);
}

/**
 * theme function to render a bootstrap panel
 * 
 * $title : titolo del panel
 * $id : id univo del panel
 * $content : element
 *
 * $options : array
 *  - active : apre il panel
 *  - parent-id : setta il parent - id
 *  - fade : aggiunge effetto fade
 */
function theme_smiletrap_panel($vars){
  
  $opt = $vars['options'];
  $classes = array('panel-collapse', 'collapse');

  if (isset($opt['fade']) && $opt['fade']){
    $classes[] = 'fade'; 
  }

  if (isset($opt['expanded']) && $opt['expanded']){
    $classes[] = 'in';
  }

  $data = array(
    '#prefix' => '<div class="panel panel-default">',
    '#suffix' => '</div>',
    'heading' => array(
      '#prefix' => '<div class="panel-heading">',
      '#suffix' => '</div>',
      '#theme' => 'link',
      '#text' => $vars['title'],
      '#path' => ' ',
      '#options' => array(
        'external' => TRUE,
        'fragment' => 'collapse-' . $vars['id'],
        'html' => TRUE,
        'attributes' => array(
          'href' => '#collapse-' . $vars['id'],
          'data-toggle' => 'collapse',
        ),
      ),
    ),
    'panel-collapse' => array(
      '#prefix' => '<div id="collapse-' . $vars['id'] . '" class="' . implode($classes, ' ') . '"><div class="panel-body">',
      'content' => $vars['content'],
      '#suffix' => '</div></div>',
    ),
  );

  if (isset($opt['parent-id'])){
    $data['heading']['#options']['attributes']['data-parent'] = '#' . $opt['parent-id'];
  }

  return render($data);
}

/**
 * theme function to render a bootstrap navbar dropdown
 */
function theme_smiletrap_navbar_dropdown($vars){

  foreach ($vars['elements'] as $key => $value) {

    $vars['elements'][$key] = array(
      '#item' => render($value),
    );
    $vars['elements'][$key]['#theme'] = 'smiletrap_navbar_dropdown_item';
  }

  $data['dropdown'] = array(
    '#prefix' => '<li id="' . $vars['id'] . '" class="smiletrap-navbar-dropdown dropdown">',
    '#suffix' => '</li>',
    'title' => array(
      '#markup' => '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">' . $vars['title'] . '</a>',
    ),
    'dropdown' => array(
      '#prefix' => '<ul class="dropdown-menu" role="menu">',
      '#suffix' => '</ul>',
      'list' => $vars['elements'],
    ),
  );

  return render($data);
}

/**
 * theme function to render a bootstrap navbar dropdown item
 */
function theme_smiletrap_navbar_dropdown_item($vars){
  if (isset($vars['id'])){
    $html = '<li id="' . $vars['id'] . '">';
  } else {
    $html = '<li>';
  }
  $html .= $vars['item'] . '</li>';
  return render($html);
}