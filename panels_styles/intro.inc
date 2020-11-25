<?php

/**
 * @file
 * Definition of the 'intro' panel style for EBI introduction paragraphs.
 */

// Plugin definition
$plugin = array(
  'title' => t('Intro'),
  'description' => t('Introduction box with grey background'),
  'render pane' => 'ebi_compliance_intro_style_render_pane',
/*   'pane settings form' => 'innovate_collapsible_settings_form', */
);



/**
* Render callback.
*
* @ingroup themeable
*/
function theme_ebi_compliance_intro_style_render_pane($vars) {
  $content = $vars['content'];
  $pane = $vars['pane'];
  $display = $vars['display'];
/*   $settings = $vars['settings']; */

  if (empty($content->content)) {
    return;
  }
    $output = '<div id="intro">';
    $output .= theme('panels_pane', array('content' => $content, 'pane' => $pane, 'display' => $display));
    $output .= '</div>';
  

  return $output;
}

