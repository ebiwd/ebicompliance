<?php
/**
* @file
* Definition of a collapsible panel pane.
*/

// Plugin definition
$plugin = array(
  'title' => t('Collapsible'),
  'description' => t('Display a pane that may be collapsed.'),
  'render pane' => 'innovate_collapsible_style_render_pane',
  'pane settings form' => 'innovate_collapsible_settings_form',
);

/**
* Render callback.
*
* @ingroup themeable
*/
function theme_innovate_collapsible_style_render_pane($vars) {
  $content = $vars['content'];
  $pane = $vars['pane'];
  $display = $vars['display'];
  $settings = $vars['settings'];

  if (empty($content->content)) {
    return;
  }
  if (!empty($content->title) && $content->content != '') {
    ctools_include('collapsible.theme');

    if (empty($settings['pane_collapsed'])) {
      $settings['pane_collapsed'] = FALSE;
    }
    $output = '<div class="panel-inner pane-inner panel-pane-collapsible">';
    $output .= theme_ctools_collapsible(array('handle' => $content->title, 'content' => $content->content, 'collapsed' => $settings['pane_collapsed']));
    $output .= '</div>';
  }
  else {
    $output = theme('panels_pane', array('content' => $content, 'pane' => $pane, 'display' => $display));
  }

  return $output;
}

/**
* Settings form callback.
*/
function innovate_collapsible_settings_form($style_settings) {
  $form = array();
  $form['pane_collapsed'] = array(
    '#type' => 'checkbox',
    '#title' => t('Collapsed by default'),
    '#default_value' => (isset($style_settings['pane_collapsed'])) ? $style_settings['pane_collapsed'] : FALSE
  );

  return $form;
}
