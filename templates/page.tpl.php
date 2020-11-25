<?php if ($is_admin) echo "<!-- start " . basename(__FILE__) . " -->\n"; ?>
<?php
/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * The doctype, html, head and body tags are not in this template. Instead they
 * can be found in the html.tpl.php template in this directory.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 * Regions:
 * - $page['help']: Dynamic help text, mostly for admin pages.
 * - $page['highlighted']: Items for the highlighted content region.
 * - $page['content']: The main content of the current page.
 * - $page['sidebar_first']: Items for the first sidebar.
 * - $page['sidebar_second']: Items for the second sidebar.
 * - $page['header']: Items for the header region.
 * - $page['footer']: Items for the footer region.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 */
?>
  <div id="skip-to">
		<ul>
			<li><a href="#content">Skip to main content</a></li>
			<li><a href="#local-nav">Skip to local navigation</a></li>
			<li><a href="#global-nav">Skip to EBI global navigation menu</a></li>
			<li><a href="#global-nav-expanded">Skip to expanded EBI global navigation menu (includes all sub-sections)</a></li>
		</ul>
	</div>


  <div id="wrapper" class="container_24 <?php print($classes) ?>">
    <header>
    	<div id="global-masthead" class="masthead grid_24">
      <!--This has to be one line and no newline characters-->
			<a href="//www.ebi.ac.uk/" title="Go to the EMBL-EBI homepage"><img src="//www.ebi.ac.uk/web_guidelines/images/logos/EMBL-EBI/EMBL_EBI_Logo_white.png" alt="EMBL European Bioinformatics Institute"></a>

			<nav>
        <?php print theme('links', array(
          'links' => $main_menu,
          'attributes' => array(
            'id' => 'global_nav'
          ),
        )); ?>
			</nav>

		</div>

		<div id="local-masthead" class="masthead grid_24 nomenu">
			<?php if ($page['local_title'] || !$title): ?>
      <div class="<?php echo $page['local_search']?'grid_12 alpha':'grid_24'; ?>" id="local-title">
        <?php print render($page['local_title']); ?>
			</div>
      <?php else: ?>
      <div class="<?php echo $page['local_search']?'grid_12 alpha':'grid_24'; ?>" id="local-title">
      <?php
        // hack to display primary and secondary titles in local_title
        $this_page_title = drupal_get_title();
        $path = (explode('/', drupal_get_path_alias()));
        if (count($path)>1 && $path[0]==='about' && ($service_groups=@file_get_contents('/var/www/drupal/files/ebi.ac.uk/private/data/group-service.regex'))!==FALSE && preg_match("#${service_groups}#", $path[1])===1 ) {
          $primary_page_title = menu_get_item(drupal_get_normal_path($path[0] . '/' . $path[1]));
          $secondary_page_title = menu_get_item(drupal_get_normal_path($path[0] . '/' . $path[1] . '/' . $path[2]));
          if ($secondary_page_title['title'] == '' && $path[2] == 'publications') {
            $secondary_page_title['title'] = 'Publications';
          }
          elseif ($secondary_page_title['title'] == '' && $path[2] == 'services') {
            $secondary_page_title['title'] = 'Services';
          }
          elseif ($secondary_page_title['title'] == '' && $path[2] == 'contact') {
            $secondary_page_title['title'] = 'Contact';
          }
          elseif ($secondary_page_title['title'] == '' && $path[2] == 'members') {
            $secondary_page_title['title'] = 'Members';
          }
          $title = $primary_page_title['title'] . ' <span>' . $secondary_page_title['title'] . '</span>';
        }
        elseif (count($path)>1 && $path[0]==='research' && ($research_groups=@file_get_contents('/var/www/drupal/files/ebi.ac.uk/private/data/group-research.regex'))!==FALSE && preg_match("#${research_groups}#", $path[1])===1 ) {

          $primary_page_title = menu_get_item(drupal_get_normal_path($path[0] . '/' . $path[1]));
          $secondary_page_title = menu_get_item(drupal_get_normal_path($path[0] . '/' . $path[1] . '/' . $path[2]));
          if ($secondary_page_title['title'] == '' && $path[2] == 'publications') {
            $secondary_page_title['title'] = 'Publications';
          }
          elseif ($secondary_page_title['title'] == '' && $path[2] == 'members') {
            $secondary_page_title['title'] = 'Members';
          }
          $title = $primary_page_title['title'] . ' <span>' . $secondary_page_title['title'] . '</span>';
        }
        elseif (count($path)>1) {
          $primary_page_title = menu_get_item(drupal_get_normal_path($path[0]));
          $secondary_page_title = menu_get_item(drupal_get_normal_path($path[0] . '/' . $path[1]));
          $title = $primary_page_title['title'] . ' <span>' . $secondary_page_title['title'] . '</span>';
        }
        ?>
        <h1><?php print render($title); ?></h1>
      </div>
			<?php endif; ?>

			<?php if ($page['local_search']): ?>
			<div class="grid_12 omega" id="local-search">
				<?php print render($page['local_search']); ?>
			</div>
			<?php endif; ?>

			<?php if ($secondary_menu): ?>
      <nav>
				<?php print theme('links', array(
          'links' => $secondary_menu,
          'attributes' => array(
            'id' => 'local_nav',
            'class' => array('grid_24'),
          ),
        )); ?>
			</nav>
      <?php endif; ?>

      <?php if ($page['local_nav']): ?>
      <nav>
  				<?php print render($page['local_nav']); ?>
			</nav>
      <?php endif; ?>

		</div>
    </header>

    <div id="content" role="main" class="grid_24 clearfix">

    <!-- Suggested layout containers -->

	  <?php if ($breadcrumb): ?>
    <nav id="breadcrumb">
		  <p><?php print $breadcrumb; ?></p>
	  </nav>
    <?php endif; ?>


    <?php print $messages; ?>

    <?php if ($page['sidebar_first'] && $page['sidebar_second']): ?>
    <section class="grid_12 push_6">
    <?php elseif ($page['sidebar_first']): ?>
    <section class="grid_18 push_6">
    <?php elseif ($page['sidebar_second']): ?>
    <section class="grid_18 alpha">
    <?php else: ?>
    <section class="grid_24">
    <?php endif; ?>
      <?php print render($title_prefix); ?>
			<?php if ($page['local_title'] && !$is_front && $node->type!=='panel' && is_object($node) && $node->type!=='error_page' && $node->type!=='extinct_page'): ?>
        <h2><?php print render($title); ?></h2>
 			<?php endif; ?>
      <?php if (is_array($secondary_page_title) && isset($secondary_page_title['title']) && $this_page_title != $secondary_page_title['title'] && !$is_front && is_object($node) && $node->type!=='panel' && $node->type!=='error_page' && $node->type!=='extinct_page'): ?>
        <?php // hack to display tertiary title in content ?>
        <h2><?php print render($this_page_title); ?></h2>
      <?php endif; ?>
      <?php print render($title_suffix); ?>
      <?php print render($tabs); ?>
      <?php print render($page['help']); ?>
      <?php print render($action_links); ?>
      <?php print render($page['content']); ?>
      <?php print render($feed_icons); ?>
		</section> 

    <?php if ($page['sidebar_first']): ?>
      <aside class="grid_6 <?php echo $page['sidebar_second']?'pull_12':'pull_18'; ?> alpha">
        <?php print render($page['sidebar_first']); ?>
      </aside>
    <?php endif; ?>

    <?php if ($page['sidebar_second']): ?>
    <aside class="grid_6 omega">
      <?php print render($page['sidebar_second']); ?>
    </aside>
    <?php endif; ?>
		<!-- End suggested layout containers -->

    </div>

    <footer>

  	<!-- Optional local footer (insert citation / project-specific copyright / etc here -->
    <div id="local-footer" class="grid_24 clearfix">
      <?php print render($page['local_footer']); ?>
		</div>
		<!-- End optional local footer -->

		<div id="global-footer" class="grid_24">

			<nav id="global-nav-expanded">

				<div class="grid_4 alpha">
					<h3 class="embl-ebi"><a href="//www.ebi.ac.uk/" title="EMBL-EBI">EMBL-EBI</a></h3>
				</div>

				<div class="grid_4">
					<h3 class="services"><a href="//www.ebi.ac.uk/services">Services</a></h3>
				</div>

				<div class="grid_4">
					<h3 class="research"><a href="//www.ebi.ac.uk/research">Research</a></h3>
				</div>

				<div class="grid_4">
					<h3 class="training"><a href="//www.ebi.ac.uk/training">Training</a></h3>
				</div>

				<div class="grid_4">
					<h3 class="industry"><a href="//www.ebi.ac.uk/industry">Industry</a></h3>
				</div>

				<div class="grid_4 omega">
					<h3 class="about"><a href="//www.ebi.ac.uk/about">About us</a></h3>
				</div>

			</nav>

			<section id="ebi-footer-meta">
				<p class="address">EMBL-EBI, Wellcome Trust Genome Campus, Hinxton, Cambridgeshire, CB10 1SD, UK &nbsp; &nbsp; +44 (0)1223 49 44 44</p>
				<p class="legal">Copyright &copy; EMBL-EBI 2014 | EBI is an Outstation of the <a href="http://www.embl.org">European Molecular Biology Laboratory</a> | <a href="/about/privacy">Privacy</a> | <a href="/about/cookies">Cookies</a> | <a href="/about/terms-of-use">Terms of use</a></p>	
			</section>

		</div>

    </footer>
  </div> <!--! end of #wrapper -->


  <!-- JavaScript at the bottom for fast page loading -->

  <!-- Grab Google CDN's jQuery, with a protocol relative URL; fall back to local if offline -->
  <!--
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
  <script>window.jQuery || document.write('<script src="../js/libs/jquery-1.6.2.min.js"><\/script>')</script>
  -->


  <!-- Your custom JavaScript file scan go here... change names accordingly -->
  <!--
  <script defer="defer" src="//www.ebi.ac.uk/web_guidelines/js/plugins.js"></script>
  <script defer="defer" src="//www.ebi.ac.uk/web_guidelines/js/script.js"></script>
  -->
  <script defer="defer" src="//www.ebi.ac.uk/web_guidelines/js/cookiebanner.js"></script>
  <script defer="defer" src="//www.ebi.ac.uk/web_guidelines/js/foot.js"></script>
  <!-- end scripts-->

  <!-- Google Analytics details... -->
  <!-- Change UA-XXXXX-X to be your site's ID -->
  <!--
  <script>
    window._gaq = [['_setAccount','UAXXXXXXXX1'],['_trackPageview'],['_trackPageLoadTime']];
    Modernizr.load({
      load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'
    });
  </script>
  -->


  <!-- Prompt IE 6 users to install Chrome Frame. Remove this if you want to support IE 6.
       chromium.org/developers/how-tos/chrome-frame-getting-started -->
  <!--[if lt IE 7 ]>
    <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js"></script>
    <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
  <![endif]-->



<?php if ($is_admin) echo "<!-- end " . basename(__FILE__) . " -->\n"; ?>