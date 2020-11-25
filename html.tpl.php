<?php 
  $is_admin = (strpos($classes, 'role-administrator') !== FALSE || strpos($classes, 'user-1') !== FALSE);
  $is_authenticated = (strpos($classes, 'role-authenticated-user') !== FALSE || strpos($classes, 'user-1') !== FALSE);
  $is_prod = in_array($_SERVER['HTTP_HOST'], array('www.ebi.ac.uk', 'intranet.ebi.ac.uk', 'staff.ebi.ac.uk', 'content.ebi.ac.uk', 'tsc.ebi.ac.uk'), TRUE);

  // rabbit hole for disallowed pages
  if (!$is_authenticated && preg_match('#^/+(group/)#sm', request_uri())) {
    header("HTTP/1.0 404 Not Found");
    exit();
  }

  if (!function_exists('ebicompliance_tidy')) {
    function ebicompliance_tidy($buffer, $is_admin, $is_prod) {
      $local_server = str_replace('.', '\.', $_SERVER['HTTP_HOST']);
      // remove http protcol from: from www.ebi links
  //    $buffer = preg_replace('#(href|src)\s*=\s*(["\'])https?:(//www\.ebi\.ac\.uk)#sm', '$1=$2$3', $buffer); 
  //    $buffer = preg_replace('#(url)\s*\(\s*(["\']?)https?:(//www\.ebi\.ac\.uk)#sm', '$1($2$3', $buffer); 
      // remove protocol and domain from frontier links
      $buffer = preg_replace('#(href|src)\s*=\s*(["\'])(https?:)?//frontier\.ebi\.ac\.uk/?#sm', '$1=$2/', $buffer); 
  //    $buffer = preg_replace('#(url)\s*\(\s*(["\']?)(https?:)?//frontier\.ebi\.ac\.uk/?#sm', '$1($2/', $buffer); 
      // remove http protcol from: from local domain links
      $buffer = preg_replace("#(href|src)\s*=\s*([\"'])https?:(//{$local_server})#sm", '$1=$2$3', $buffer); 
  //    $buffer = preg_replace("#(url)\s*\(\s*([\"']?)https?:(//{$local_server})#sm", '$1($2$3', $buffer); 

      if (!$is_prod) {
        $buffer = str_replace('//www.ebi.ac.uk', '//wwwdev.ebi.ac.uk', $buffer);
      }
  /*
      if (!$is_admin) {
        // remove comments
        $buffer = str_replace('<!--//--><![CDATA[// ><!--', '', $buffer);
        $buffer = str_replace('<!--//--><![CDATA[//><!--', '', $buffer);
        $buffer = str_replace('//--><!]]>', '', $buffer);
        $buffer = preg_replace('#<!--.*-->#Usm', '', $buffer);
        // remove spaces
  //      $buffer = preg_replace('#\s+#sm', ' ', $buffer);
      }
  */
      $buffer = preg_replace('#(local|global)_(nav)#sm', '$1-$2', $buffer);
      $buffer = preg_replace('#(grid)-(\d+)#sm', '$1_$2', $buffer);

      if (strpos($buffer, 'key-not-found-in-xml') !== FALSE) {
        $buffer = ''; // clear original content
        drupal_not_found(); // display not found page
      }

      return $buffer;
    }
  }
?>
<?php

/**
 * @file
 * Default theme implementation to display the basic html structure of a single
 * Drupal page.
 *
 * Variables:
 * - $css: An array of CSS files for the current page.
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or 'rtl'.
 * - $rdf_namespaces: All the RDF namespace prefixes used in the HTML document.
 * - $grddl_profile: A GRDDL profile allowing agents to extract the RDF data.
 * - $head_title: A modified version of the page title, for use in the TITLE
 *   tag.
 * - $head_title_array: (array) An associative array containing the string parts
 *   that were used to generate the $head_title variable, already prepared to be
 *   output as TITLE tag. The key/value pairs may contain one or more of the
 *   following, depending on conditions:
 *   - title: The title of the current page, if any.
 *   - name: The name of the site.
 *   - slogan: The slogan of the site, if any, and if there is no title.
 * - $head: Markup for the HEAD section (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $page_top: Initial markup from any modules that have altered the
 *   page. This variable should always be output first, before all other dynamic
 *   content.
 * - $page: The rendered page content.
 * - $page_bottom: Final closing markup from any modules that have altered the
 *   page. This variable should always be output last, after all other dynamic
 *   content.
 * - $classes String of classes that can be used to style contextually through
 *   CSS.
 *
 * @see template_preprocess()
 * @see template_preprocess_html()
 * @see template_process()
 */
?><!doctype html>
<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->
<!-- Consider adding an manifest.appcache: h5bp.com/d/Offline -->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>

  <!-- Use the .htaccess and remove these lines to avoid edge case issues.
       More info: h5bp.com/b/378 -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<!-- START: default Drupal Head Metatags -->
  <?php print $head; ?>
<!-- END: default Drupal Head Metatags -->

  <title><?php print($head_title); ?></title>
  <meta name="author" content="EMBL-EBI"><!-- Your [project-name] here -->

  <!-- Mobile viewport optimized: j.mp/bplateviewport -->
  <meta name="viewport" content="width=device-width,initial-scale=1">

  <!-- Place favicon.ico and apple-touch-icon.png in the root directory: mathiasbynens.be/notes/touch-icons -->
  <link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">

  <!-- CSS: implied media=all -->
  <!-- CSS concatenated and minified via ant build script-->
  <link type="text/css" rel="stylesheet" href="//<?php echo $is_prod?'www':'wwwdev'; ?>.ebi.ac.uk/web_guidelines/css/compliance/develop/boilerplate-style.css">
  <?php print preg_replace('#https?:#Usm', '', $styles); ?>
  <link type="text/css" rel="stylesheet" href="//<?php echo $is_prod?'www':'wwwdev'; ?>.ebi.ac.uk/web_guidelines/css/compliance/mini/ebi-fluid-embl-noboilerplate.css" type="text/css" media="screen">
  <?php if (ebicompliance_get_subpath() === 'pdbe'): ?>
    <link rel="stylesheet" href="//<?php echo $is_prod?'www':'wwwdev'; ?>.ebi.ac.uk/web_guidelines/css/compliance/develop/pdbe-green-colours.css" type="text/css" media="screen">
    <link type="text/css" rel="stylesheet" href="/pdbe/css/pdbe_main.css" media="all" />
  <?php endif; ?>
  <!-- end CSS-->

  <!-- All JavaScript at the bottom, except for Modernizr / Respond.
       Modernizr enables HTML5 elements & feature detects; Respond is a polyfill for min/max-width CSS3 Media Queries
       For optimal performance, use a custom Modernizr build: www.modernizr.com/download/ -->

  <!-- Full build -->
  <!-- <script src="//<?php echo $is_prod?'www':'wwwdev'; ?>.ebi.ac.uk/web_guidelines/js/libs/modernizr.minified.2.1.6.js"></script> -->

  <!-- custom build (lacks most of the "advanced" HTML5 support -->
  <script type="text/javascript" src="//<?php echo $is_prod?'www':'wwwdev'; ?>.ebi.ac.uk/web_guidelines/js/libs/modernizr.custom.49274.js"></script>
  <?php print (ebicompliance_tidy($scripts, $is_admin, $is_prod)); ?>
  <script type="text/javascript">jQuery(document).ready(function($){$('a img').each(function(id){$(this.parentNode).addClass('no-underline')})});</script>
</head>

<body class="<?php print($classes) ?>">

<!-- page_top -->
    <?php print(ebicompliance_tidy($page_top, $is_admin, $is_prod)); ?>
<!-- page -->
    <?php print(ebicompliance_tidy($page, $is_admin, $is_prod)); ?>
<!-- page_bottom -->
    <?php print(ebicompliance_tidy($page_bottom, $is_admin, $is_prod)); ?>
    <?php if (ebicompliance_get_subpath() === 'pdbe'): ?>
      <script type="text/javascript" src="/pdbe/js/pdbe_main.js" defer></script>
    <?php endif; ?>
</body>
</html>
