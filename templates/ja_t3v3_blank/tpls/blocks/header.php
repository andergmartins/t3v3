<?php
/**
 * @package   T3 Blank
 * @copyright Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$sitename = $this->params->get('sitename') ? $this->params->get('sitename') : JFactory::getConfig()->get('sitename');
$slogan = $this->params->get('slogan');
$logo_type = $this->params->get('logotype', 'text');
?>

<!-- HEADER -->
<header id="ja-header" class="container ja-header">
  <div class="row">
    <div class="span12">

      <!-- LOGO -->
      <div class="logo logo-<?php echo $logo_type ?>">
        <h1>
          <a href="index.php" title="<?php echo strip_tags($sitename) ?>">
            <span><?php echo $sitename ?></span>
          </a>
          <small class="site-slogan hidden-phone"><?php echo $slogan ?></small>
        </h1>
      </div>
      <!-- //LOGO -->
     
      <?php if ($this->countModules('head-search')) : ?>
      <!-- HEAD SEARCH -->
      <div class="head-search">
        <jdoc:include type="modules" name="<?php $this->_p('head-search') ?>" style="raw" />
      </div>
      <!-- //HEAD SEARCH -->
      <?php endif ?>

    </div>
  </div>
</header>
<!-- //HEADER -->
