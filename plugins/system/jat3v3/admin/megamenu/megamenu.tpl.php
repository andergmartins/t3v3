﻿<?php
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('id, title, module, position');
$query->from('#__modules');
$query->where('published = 1');
$query->where('client_id = 0');
$query->order('title');
$db->setQuery($query);
$modules = $db->loadObjectList();
?>
<?php if(is_file(T3V3_PATH . '/css/megamenu.css')): ?>
  <link rel="stylesheet" href="<?php echo T3V3_URL ?>/css/megamenu.css" type="text/css" />
<?php endif; ?>
<?php if(is_file(T3V3_TEMPLATE_PATH . '/css/megamenu.css')): ?>
  <link rel="stylesheet" href="<?php echo T3V3_TEMPLATE_URL ?>/css/megamenu.css" type="text/css" />
<?php endif; ?>
<?php if(is_file(T3V3_ADMIN_PATH . '/admin/megamenu/css/megamenu.css')): ?>
  <link rel="stylesheet" href="<?php echo T3V3_ADMIN_URL ?>/admin/megamenu/css/megamenu.css" type="text/css" />
<?php endif; ?>
<script type="text/javascript" src="<?php echo T3V3_ADMIN_URL ?>/admin/megamenu/js/megamenu.js"></script>

<div id="megamenu-admin" class="hidden">
  <div class="t3-inline-toolbox clearfix">
    <div class="t3-row t3-row-mega clearfix">
      
      <div id="megamenu-intro" class="pull-left">
        <h3><?php echo JTexT::_('T3V3_NAVIGATION_MM_TOOLBOX') ?></h3>
        <p><?php echo JTexT::_('T3V3_NAVIGATION_MM_TOOLBOX_DESC') ?></p>
      </div>
      
      <div id="megamenu-toolbox">
        <div id="megamenu-toolitem" class="toolbox">
          <h3><?php echo JTexT::_('T3V3_NAVIGATION_MM_ITEM_CONF') ?></h3>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_SUBMENU'), '::', JTexT::_('T3V3_NAVIGATION_MM_SUBMENU_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_SUBMENU') ?></label>
              <fieldset class="radio btn-group toolitem-sub">
                <input type="radio" id="toggleSub0" class="toolbox-toggle" data-action="toggleSub" name="toggleSub" value="0"/>
                <label for="toggleSub0"><?php echo JTexT::_('JNO') ?></label>
                <input type="radio" id="toggleSub1" class="toolbox-toggle" data-action="toggleSub" name="toggleSub" value="1" checked="checked"/>
                <label for="toggleSub1"><?php echo JTexT::_('JYES') ?></label>
              </fieldset>
            </li>
          </ul>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_GROUP'), '::', JTexT::_('T3V3_NAVIGATION_MM_GROUP_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_GROUP') ?></label>
              <fieldset class="radio btn-group toolitem-group">
                <input type="radio" id="toggleGroup0" class="toolbox-toggle" data-action="toggleGroup" name="toggleGroup" value="0"/>
                <label for="toggleGroup0"><?php echo JTexT::_('JNO') ?></label>
                <input type="radio" id="toggleGroup1" class="toolbox-toggle" data-action="toggleGroup" name="toggleGroup" value="1" checked="checked"/>
                <label for="toggleGroup1"><?php echo JTexT::_('JYES') ?></label>
              </fieldset>
            </li>
          </ul>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_POSITIONS'), '::', JTexT::_('T3V3_NAVIGATION_MM_POSITIONS_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_POSITIONS') ?></label>
              <fieldset class="btn-group">
                <a href="" class="btn toolitem-moveleft toolbox-action" data-action="moveItemsLeft" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_MOVE_LEFT') ?>"><i class="icon-arrow-left"></i></a>
                <a href="" class="btn toolitem-moveright toolbox-action" data-action="moveItemsRight" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_MOVE_RIGHT') ?>"><i class="icon-arrow-right"></i></a>
              </fieldset>
            </li>
          </ul>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_EX_CLASS'), '::', JTexT::_('T3V3_NAVIGATION_MM_EX_CLASS_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_EX_CLASS') ?></label>
              <fieldset class="">
                <input type="text" class="toolitem-exclass toolbox-input" name="toolitem-exclass" data-name="class" value="" />
              </fieldset>
            </li>
          </ul>
        </div>

        <div id="megamenu-toolsub" class="toolbox">
          <h3><?php echo JTexT::_('T3V3_NAVIGATION_MM_SUBMNEU_CONF') ?></h3>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_SUBMNEU_GRID'), '::', JTexT::_('T3V3_NAVIGATION_MM_SUBMNEU_GRID_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_SUBMNEU_GRID') ?></label>
              <fieldset class="btn-group">
                <a href="" class="btn toolsub-addrow toolbox-action" data-action="addRow">Add Row</a>
              </fieldset>
            </li>
          </ul>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_SUBMNEU_WIDTH_PX'), '::', JTexT::_('T3V3_NAVIGATION_MM_SUBMNEU_WIDTH_PX_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_SUBMNEU_WIDTH_PX') ?></label>
              <fieldset class="">
                <input type="text" class="toolsub-width toolbox-input input-small" name="toolsub-width" data-name="width" value="" />
              </fieldset>
            </li>
          </ul>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_ALIGN'), '::', JTexT::_('T3V3_NAVIGATION_MM_ALIGN_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_ALIGN') ?></label>
              <fieldset class="toolsub-alignment">
                <div class="btn-group">
                <a class="btn toolsub-align-left toolbox-action" href="#" data-action="alignment" data-align="left" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_ALIGN_LEFT') ?>"><i class="icon-align-left"></i></a>
                <a class="btn toolsub-align-center toolbox-action" href="#" data-action="alignment" data-align="center" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_ALIGN_CENTER') ?>"><i class="icon-align-center"></i></a>
                <a class="btn toolsub-align-right toolbox-action" href="#" data-action="alignment" data-align="right" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_ALIGN_RIGHT') ?>"><i class="icon-align-right"></i></a>
                </div>
              </fieldset>
            </li>
          </ul>          
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_EX_CLASS'), '::', JTexT::_('T3V3_NAVIGATION_MM_EX_CLASS_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_EX_CLASS') ?></label>
              <fieldset class="">
                <input type="text" class="toolsub-exclass toolbox-input" name="toolsub-exclass" data-name="class" value="" />
              </fieldset>
            </li>
          </ul>
        </div>

        <div id="megamenu-toolcol" class="toolbox">
          <h3><?php echo JTexT::_('T3V3_NAVIGATION_MM_COLUMN_CONF') ?></h3>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_SUBMNEU_GRID'), '::', JTexT::_('T3V3_NAVIGATION_MM_SUBMNEU_GRID_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_SUBMNEU_GRID') ?></label>
              <fieldset class="btn-group">
                <a href="" class="btn toolcol-addcol toolbox-action" data-action="addColumn">Add Column</a>
                <a href="" class="btn toolcol-removecol toolbox-action" data-action="removeColumn">Remove Column</a>
              </fieldset>
            </li>
          </ul>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_WIDTH_SPAN'), '::', JTexT::_('T3V3_NAVIGATION_MM_WIDTH_SPAN_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_WIDTH_SPAN') ?></label>
              <fieldset class="">
                <select class="toolcol-width toolbox-input toolbox-select input-mini" name="toolcol-width" data-name="width">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                  <option value="8">8</option>
                  <option value="9">9</option>
                  <option value="10">10</option>
                  <option value="11">11</option>
                  <option value="12">12</option>
                </select>
              </fieldset>
            </li>
          </ul>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_MODULE'), '::', JTexT::_('T3V3_NAVIGATION_MM_MODULE_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_MODULE') ?></label>
              <fieldset class="">
                <select class="toolcol-position toolbox-input toolbox-select" name="toolcol-position" data-name="position">
                  <option value=""><?php echo JTexT::_('T3V3_NAVIGATION_MM_SELECT_MODULE') ?></option>
                  <?php
                  foreach ($modules as $module) {
                    echo "<option value=\"{$module->id}\">{$module->title}</option>\n";
                  }
                  ?>
                </select>
              </fieldset>
            </li>
          </ul>
          <ul>
            <li>
              <label class="hasTip" title="<?php echo JTexT::_('T3V3_NAVIGATION_MM_EX_CLASS'), '::', JTexT::_('T3V3_NAVIGATION_MM_EX_CLASS_DESC') ?>"><?php echo JTexT::_('T3V3_NAVIGATION_MM_EX_CLASS') ?></label>
              <fieldset class="">
                <input type="text" class="toolcol-exclass toolbox-input" name="toolcol-exclass" data-name="class" value="" />
              </fieldset>
            </li>
          </ul>
        </div>    
      </div> 
      
      <div class="toolbox-actions-group">
        <button class="btn btn-success toolbox-action toolbox-saveConfig" data-action="saveConfig"><i class="icon-save"></i><?php echo JTexT::_('T3V3_NAVIGATION_MM_SAVE') ?></button>
        <button class="btn btn-danger toolbox-action toolbox-resetConfig"><i class="icon-undo"></i><?php echo JTexT::_('T3V3_NAVIGATION_MM_RESET') ?></button>
      </div>

    </div>
  </div>

  <div id="megamenu-container" class="navbar clearfix"></div> 
</div>

<script type="text/javascript">
jQuery('#megamenu-admin select').chosen();
</script>
