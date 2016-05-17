<?php
/* -- load theme js -- */

function load_jquery_easing() {
  //carico javascript library
  $path = libraries_get_path('jquery.easing') . '/jquery.easing.1.3.js';
  drupal_add_js( $path , array('group' => JS_LIBRARY, 'weight' => 2));
}
load_jquery_easing();

function load_jquery_resize() {
  //carico javascript library
  $path = libraries_get_path('jquery.resize') . '/jquery.resize.js';
  drupal_add_js( $path , array('group' => JS_LIBRARY, 'weight' => 1));
}
load_jquery_resize();

function load_jquery_imagesloaded() {
  //carico javascript library
  $path = libraries_get_path('jquery.imagesloaded') . '/jquery.imagesloaded.min.js';
  drupal_add_js( $path , array('group' => JS_LIBRARY, 'weight' => 1));
}
load_jquery_imagesloaded();

function load_jquery_bootstrapstate() {
  //carico javascript library
  $path = libraries_get_path('jquery.bootstrapstate') . '/jquery.bootstrapstate.js';
  drupal_add_js( $path , array('group' => JS_LIBRARY, 'weight' => 1));
}
load_jquery_bootstrapstate();

function load_jquery_hover() {
  //carico javascript library
  $path = libraries_get_path('jquery.hover') . '/jquery.hover.js';
  drupal_add_js( $path, array('type' => 'file', 'scope' => 'footer', 'weight' => 2));
}
load_jquery_hover();