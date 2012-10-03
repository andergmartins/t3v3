<?php
/**
 * $JA#COPYRIGHT$
 */

// No direct access
defined('_JEXEC') or die;
?>
<?php

	// width configuration
	$layout = json_decode('{
      "default" : [ "span6"         , "span6"             ],
      "wide"  	: [],
      "xtablet" : [],
      "tablet"  : []
    }');

	$style = 'JAxhtml';
  
  $pos = preg_split('/\s*,\s*/', $data);
  // check if there's any modules
  if (!$this->countModules (implode (' or ', $pos))) return;
  // check if number of module positions less than the blocks
  if (count($pos) < 2) return;

	$col = 0;
?>

<!-- SPOTLIGHT -->
<div class="row">
  <div class="<?php echo $this->getClass($layout, $col) ?>" <?php echo $this->getData ($layout, $col++) ?>>
      <jdoc:include type="modules" name="<?php $this->_p($pos[0]) ?>" style="<?php echo $style ?>" />
  </div>
  <div class="<?php echo $this->getClass($layout, $col) ?>" <?php echo $this->getData ($layout, $col++) ?>>
      <jdoc:include type="modules" name="<?php $this->_p($pos[1]) ?>" style="<?php echo $style ?>" />
  </div>
</div>
<!-- SPOTLIGHT -->