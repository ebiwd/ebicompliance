<?php

function ebicompliance_preprocess_html(&$vars) {

  // add user roles to body class
  global $user;
  foreach ($user->roles as $role) {
    $vars['classes_array'][] = ebicompliance_id_safe('role-' . $role);
  }

  // add user id to body class
  global $user;
  $vars['classes_array'][] = ebicompliance_id_safe('user-' . $user->uid);

  // default level (1 = corporate site, 2 = service)
  $level = $vars['is_front'] ? 1 : 2; // assume front page is corporate until we test for subsites and subdomains later

  $url = drupal_get_path_alias();

  $vars['classes_array'][] = ebicompliance_id_safe('page-' . $url);
  $url_parts = explode('/', $url);
  switch($url_parts[0]) {
    case 'services':
    case 'research':
    case 'training':
    case 'about':
      $level = 1;
      break;
  };

  // add section/subsection to body classes
  if ($url_parts[0] != 'node') {
    if (isset($url_parts[0])) {
      $vars['classes_array'][] = ebicompliance_id_safe('section-' . $url_parts[0]);
      if (isset($url_parts[1])) {
        $vars['classes_array'][] = ebicompliance_id_safe('subsection-' . $url_parts[1]);
      }
      else {
        $vars['classes_array'][] = ebicompliance_id_safe('subsection-overview');
      }
    }
  }

  // add subdomain indictator for ebi domains
  switch ($subdomain = ebicompliance_get_subdomain()) {
    // level 1 subdomains
    // none
    // level 2 subdomains
    case 'intranet':
    case 'staff': // legacy
    case 'content':
    case 'tsc':
      $vars['classes_array'][] = ebicompliance_id_safe('subdomain-' . $subdomain);
      $level = 2;
      break;
    default:
      $vars['classes_array'][] = ebicompliance_id_safe('subdomain-none');
      break;
  }

  // add indictator for ebi subsite: e.g. ebi.ac.uk/rdf
  switch ($subsite = ebicompliance_get_subpath()) {
    // level 1 subsites
    // none
    // level 2 subsites
    case 'rdf':
    case 'pdbe':
    case 'ega':
      $vars['classes_array'][] = ebicompliance_id_safe('subsite-' . $subsite);
      $level = 2;
      break;
    default:
      $vars['classes_array'][] = ebicompliance_id_safe('subsite-none');
      break;
  }

  // add level
  $vars['classes_array'][] = ebicompliance_id_safe('level' . $level);

  $host = explode('.', ebicompliance_get_host());

  switch ("$host[0].$host[1].$host[2]") {
    case '10.3.0':
      $vars['classes_array'][] = ebicompliance_id_safe('datacentre-hx');
      switch ($host[3] & 1) { // bitwise and last digit of ip address
        case 0:
          $vars['classes_array'][] = ebicompliance_id_safe('environment-dev');
          break;
        default:
          $vars['classes_array'][] = ebicompliance_id_safe('environment-stage');
          break;
      }
      break;

    case '10.3.2':
      $vars['classes_array'][] = ebicompliance_id_safe('datacentre-ebi');
      switch ($host[3] & 3) { // bitwise and last 2 digits of ip address
        case 0:
          $vars['classes_array'][] = ebicompliance_id_safe('environment-dev');
          break;
        case 1:
          $vars['classes_array'][] = ebicompliance_id_safe('environment-stage');
          break;
        default:
          $vars['classes_array'][] = ebicompliance_id_safe('environment-prod');
          break;
      }
      break;

    case '10.49.1':
      $vars['classes_array'][] = ebicompliance_id_safe('datacentre-pg');
      $vars['classes_array'][] = ebicompliance_id_safe('environment-prod');
      break;

    case '10.39.1':
      $vars['classes_array'][] = ebicompliance_id_safe('datacentre-oy');
      $vars['classes_array'][] = ebicompliance_id_safe('environment-prod');
      break;

    default:
      $vars['classes_array'][] = ebicompliance_id_safe('datacentre-none');
      // try to find dev/stage/prod in domain name
      if (strpos($_SERVER['HTTP_HOST'], 'dev') !== FALSE) {
        $vars['classes_array'][] = ebicompliance_id_safe('environment-dev');
      }
      elseif (strpos($_SERVER['HTTP_HOST'], 'stage') !== FALSE) {
        $vars['classes_array'][] = ebicompliance_id_safe('environment-stage');
      }
      elseif (strpos($_SERVER['HTTP_HOST'], 'prod') !== FALSE) {
        $vars['classes_array'][] = ebicompliance_id_safe('environment-prod');
      }
      else {
        $vars['classes_array'][] = ebicompliance_id_safe('environment-none');
      }
      break;
  }


  if ($level == 1 && $vars['is_front']) {
    $vars['head_title'] = "EMBL European Bioinformatics Institute";
  }
  else {
    $last_part = count($url_parts)-1;
    $head_title = "EMBL-EBI";
    $url = "";
    for ($i=0; $i<=$last_part; $i++) {
      $url .= '/' . $url_parts[$i];
      if ($i==2 && $url_parts[0]=='research' && $url_parts[2]=='publications') {
        $item['title'] = 'Publications';
      }
      elseif ($i==2 && $url_parts[0]=='research' && $url_parts[2]=='members') {
        $item['title'] = 'Members';
      }
      else {
        $item = menu_get_item(drupal_get_normal_path(substr($url,1)));
        $item['title'] = htmlentities($item['title']);
      }
      if ($item['title'] == '') {
        if ($i>0 && $url_parts[$i-1] == 'people') {
          $item['title'] = htmlentities(ucwords(str_replace('-', ' ', $url_parts[$i])));
        }
        elseif ($url_parts[$i] == 'dna-rna') { // special case for dna-rna
          $item['title'] = htmlentities('DNA & RNA');
        }
        else { // upper case first letter
          $item['title'] = htmlentities(ucfirst(str_replace('-', ' ', $url_parts[$i])));
        }
      }
      $head_title = $item['title'] . ' &lt; ' . $head_title;
    }
    $vars['head_title'] = $head_title;
  }

}

function ebicompliance_preprocess_page(&$vars) {
}

function ebicompliance_preprocess_node(&$vars) {
}

function ebicompliance_preprocess_block(&$vars) {
}

function ebicompliance_preprocess_field(&$vars) {
}

function ebicompliance_breadcrumb($vars) {
  $breadcrumb = $vars['breadcrumb'];
  $separator = ' &gt; ';

  $last_element = count($breadcrumb)-1;

  // hack for research/* *(research group) and about/* (service team) breadcrumbs
  if ($last_element <= 1) {
    $path = (explode('/', drupal_get_path_alias()));
    if (count($path)>=2 && $path[0]=='research' && $research_groups=@file_get_contents('/var/www/drupal/files/ebi.ac.uk/private/data/group-research.regex') && preg_match("#($research_groups)#", $path[1])) {
      $this_path = $path[0];
      for ($i=1; $i<count($path); $i++) {
        $this_path .= '/' . $path[$i];
        $this_item = menu_get_item(drupal_get_normal_path($this_path));
        $breadcrumb[$i] = "<a href=\"/{$this_path}\">{$this_item['title']}</a>";
      }
    }
    if (count($path)>=2 && $path[0]=='about' && $service_groups=@file_get_contents('/var/www/drupal/files/ebi.ac.uk/private/data/group-service.regex') && preg_match("#($service_groups)#", $path[1])) {
      $this_path = $path[0];
      for ($i=1; $i<count($path); $i++) {
        $this_path .= '/' . $path[$i];
        $this_item = menu_get_item(drupal_get_normal_path($this_path));
        $breadcrumb[$i] = "<a href=\"/{$this_path}\">{$this_item['title']}</a>";
      }
    }

  }

  $last_element = count($breadcrumb)-1;
  // remove any item titled Overview
  for ($i=0; $i<=$last_element; $i++) {
    if (strpos($breadcrumb[$i], ">Overview<") !== FALSE) {
      unset($breadcrumb[$i]);
    }
  }

  // remove last element if it refers to the current url
  if ($last_element>=0 && strpos($breadcrumb[$last_element], "href=\"/" . drupal_get_path_alias() . "\"") !== FALSE) {
    unset($breadcrumb[$last_element]);
  }

  // remove first element (home)
  if (count($breadcrumb) > 1) {
    unset($breadcrumb[0]);
    return implode($separator, $breadcrumb) . $separator . drupal_get_title();
  }

  // otherwise return an empty string
}

/**
 * Generate the HTML output for a menu link and submenu.
 *
 * @param $variables
 *  An associative array containing:
 *   - element: Structured array data for a menu link.
 *
 * @return
 *  A themed HTML string.
 *
 * @ingroup themeable
 *
 */
function ebicompliance_menu_link(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
    $sub_menu = drupal_render($element['#below']);
  }

  $output = l($element['#title'], $element['#href'], $element['#localized_options']);

  // Adding a class depending on the TITLE of the link (not constant)
  $element['#attributes']['class'][] = ebicompliance_id_safe('menu-' . $element['#title']);


  // check if url matches link, set li class to 'active active-trail'
  $current_url = '/'.drupal_get_path_alias();
  preg_match('/href=\"(.*)\"/U', $output, $match);
  if ($match[1] == substr($current_url,0,strlen($match[1])) && $element['#title'] !== 'Overview' && $element['#title'] !== 'Home') {
    $element['#attributes']['class'][] = 'active active-trail';
  }
  if ($match[1] == $current_url) {
    $element['#attributes']['class'][] = 'active active-trail';
  }
  // Adding a class depending on the ID of the link (constant)
  $element['#attributes']['class'][] = 'mid-' . $element['#original_link']['mlid'];
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
}


// variation of theme_links to add link id attribute
function ebicompliance_links($variables) {
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
      // add id based on link text for global_nav
      if ($attributes['id'] = 'global_nav') {
        $id = preg_replace("#[^a-z0-9]#", '-', strtolower($link['title']));
      }

      $class = array($key);

      // add class based on menu item title
      $class[] = ebicompliance_id_safe('menu-' . $id);

      // Add first, last and active classes to the list of links to help out themers.
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
           && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      if (in_array('menu-share', $class) || in_array('menu-feedback', $class) || in_array('menu-login', $class) || in_array('menu-my-account', $class) || in_array('menu-logout', $class)) {
        $class[] = 'functional';
      }

      // Set default active trail on global_nav for subdomains (XYZ.ebi.ac.uk) and subsites (ebi.ac.uk/XYZ)
      $class[] = ebicompliance_set_subdomain_trail($link,$variables);

/*
      if ($i == 1) {
        $class[] = 'first';
      }
      if ($i == $num_links) {
        $class[] = 'last';
      }
*/

      $output .= '<li' . drupal_attributes(array('class' => $class, 'id' => $id)) . '>';

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


/**
 * Converts a string to a suitable html ID attribute.
 *
 * http://www.w3.org/TR/html4/struct/global.html#h-7.5.2 specifies what makes a
 * valid ID attribute in HTML. This function:
 *
 * - Ensure an ID starts with an alpha character by optionally adding an 'n'.
 * - Replaces any character except A-Z, numbers, and underscores with dashes.
 * - Converts entire string to lowercase.
 *
 * @param $string
 * 	The string
 * @return
 * 	The converted string
 */
function ebicompliance_id_safe($string) {
  // Replace with dashes anything that isn't A-Z, numbers, dashes, or underscores.
  $string = strtolower(preg_replace('/[^a-zA-Z0-9_-]+/', '-', $string));
  // If the first character is not a-z, add 'n' in front.
  if (!ctype_lower($string{0})) { // Don't use ctype_alpha since its locale aware.
    $string = 'id'. $string;
  }
  return $string;
}


/**
 * Implements hook_html_head_alter
 *
 * replace the meta content-type tag for Drupal 7
*/
function ebicompliance_html_head_alter(&$head_elements) {
	$head_elements['system_meta_content_type']['#attributes'] = array(
    'charset' => 'utf-8'
  );
}



/**
 * set active trail for (sub)domain and (sub)site in global_nav menu
 * the active trail will be based on the *if($link['title']* value
 * add a case for your sub- domain/site
 */
function ebicompliance_set_subdomain_trail($link,$variables) {
  $class = '';

	// (sub)domain
	switch(ebicompliance_get_subdomain()){
	  case 'intranet':
	  	if($variables['attributes']['id'] == 'global_nav'){
			if($link['title'] == 'About us' || $link['title'] == 'About') {
	  			$class = ' active-trail';
	  		}
	  	}
	  	break;
		case 'local-intranet':
		  if($variables['attributes']['id'] == 'global_nav'){
			  if($link['title'] == 'Services') {
				  $class = ' active-trail';
				}
			}
		  break;
	}

	// (sub)site
	switch(ebicompliance_get_subpath()){
    case 'rdf':
    case 'pdbe':
			if($variables['attributes']['id'] == 'global_nav'){
				// the active trail will be set on the Services top menu item
			  if($link['title'] == 'Services') {
					$class = ' active-trail';
				}
			}
		  break;
	}

	return $class;
}



/**
 * get (sub)domain
 */
function ebicompliance_get_subdomain() {
	$domain = $_SERVER['HTTP_HOST'];
	$domain = str_replace('.ebi.ac.uk', '', $domain);
	$domain_parts = explode('.', $domain);

	if(count($domain_parts)>1){
	$subdomain = $domain_parts[count($domain_parts)-1];
		return $subdomain;
	}
	else {
  	  return '';
	}
}

/**
 * get (base of sub)path under ebi.ac.uk
 */
function ebicompliance_get_subpath() {
	global $base_path;
	$subpath = str_replace("/", "", $base_path);

	return $subpath;
}

/**
 * get ip address of host
 */
function ebicompliance_get_host() {
  return $_SERVER['SERVER_ADDR'];
}
