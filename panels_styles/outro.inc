<?php

/**
 * @file
 * Definition of the 'outro' panel style for EBI trailing paragraphs.
 */

// Plugin definition
$plugin = array(
  'title' => t('Outro'),
  'description' => t('Trailing box with grey background'),
  'render pane' => 'ebi_compliance_outro_style_render_pane',
/*   'pane settings form' => 'innovate_collapsible_settings_form', */
);



/**
* Render callback.
*
* @ingroup themeable
*/
function theme_ebi_compliance_outro_style_render_pane($vars) {
  $content = $vars['content'];
  $pane = $vars['pane'];
  $display = $vars['display'];
/*   $settings = $vars['settings']; */

  if (empty($content->content)) {
    return;
  }
    $output = '<div id="outro">';
    $output .= theme('panels_pane', array('content' => $content, 'pane' => $pane, 'display' => $display));
    $output .= '</div>';
  

  return $output;
}

