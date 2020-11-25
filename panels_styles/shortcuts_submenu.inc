<?php

/**
 * @file
 * Definition of the shortcutssubmenu panel style for EBI.
 */

// Plugin definition
$plugin = array(
  'title' => t('Shortcuts-Submenu'),
  'description' => t('Shortcut box with submenu background'),
  'render pane' => 'ebi_compliance_shortcutssubmenu_style_render_pane',
/*   'pane settings form' => 'ebi_compliance_shortcutssubmenu_settings_form', */
);



/**
* Render callback.
*
* @ingroup themeable
*/
function theme_ebi_compliance_shortcutssubmenu_style_render_pane($vars) {
/* drupal_set_message(print_r($vars,TRUE)); */
  $content = $vars['content'];
/*   $title = $vars['content']->title; */
/*   $vars['content']->title = ''; */
  $pane = $vars['pane'];
  $display = $vars['display'];
/*   $settings = $vars['settings']; */

  if (empty($content->content)) {
    return;
  }
    $output = '<div class="shortcuts submenu">';
/*     $output = "<h3>$title</h3>"; */
    
    $output .= theme('panels_pane', array('content' => $content, 'pane' => $pane, 'display' => $display));
    $output .= '</div>';
  

  return $output;
}



/**
* Settings form callback.
*/
/*
function ebi_compliance_shortcutssubmenu_settings_form($style_settings) {
  $form = array();
  $form['pane_collapsed'] = array(
    '#type' => 'checkbox',
    '#title' => t('Collapsed by default'),
    '#default_value' => (isset($style_settings['pane_collapsed'])) ? $style_settings['pane_collapsed'] : FALSE
  );

  return $form;
}
*/

