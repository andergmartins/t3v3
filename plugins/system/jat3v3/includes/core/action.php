<?php
/**
 * $JA#COPYRIGHT$
 */

// No direct access
defined('_JEXEC') or die();
/**
 * T3V3Less class compile less
 *
 * @package T3V3
 */
class T3V3Action extends JObject
{
	static public function run ($action) {
		if (method_exists('T3V3Action', $action)) {
			T3V3Action::$action();
		}
	}

	static public function lessc () {
		$path = JRequest::getString ('s');
		t3v3import ('core/less');
		$t3less = new T3V3Less;
		$css = $t3less->getCss($path);

		header("Content-Type: text/css");
		header("Content-length: ".strlen($css));
		echo $css;
		exit;
	}

	public static function lesscall(){
		JFactory::getLanguage()->load(T3V3_PLUGIN, JPATH_ADMINISTRATOR);

		t3v3import ('core/less');
		
		$result = array();
		try{
			T3V3Less::compileAll();
			$result['successful'] = JText::_('T3V3_THEME_COMPILE_SUCCESS');
		}catch(Exception $e){
			$result['error'] = sprintf(JText::_('T3V3_THEME_COMPILE_FAILED'), $e->getMessage());
		}
		
		die(json_encode($result));
	}

	public static function theme(){
		
		JFactory::getLanguage()->load(T3V3_PLUGIN, JPATH_ADMINISTRATOR);
		JFactory::getLanguage()->load('tpl_' . T3V3_TEMPLATE, JPATH_SITE);

		if(!defined('T3V3')) {
			die(json_encode(array(
				'error' => JText::_('T3V3_THEME_PLUGIN_NOT_READY')
			)));
		}

		$user = JFactory::getUser();
		$action = JRequest::getCmd('t3task', '');

		if ($action != 'thememagic' && !$user->authorise('core.manage', 'com_templates')) {
		    die(json_encode(array(
				'error' => JText::_('T3V3_THEME_NO_PERMISSION')
			)));
		}

		
		if(empty($action)){
			die(json_encode(array(
				'error' => JText::_('T3V3_THEME_UNKNOW_ACTION')
			)));
		}

		t3v3import('core/theme');
		
		if(method_exists('ThemeHelper', $action)){
			ThemeHelper::$action(T3V3_TEMPLATE_PATH . '/assets');	
		} else {
			die(json_encode(array(
				'error' => JText::_('T3V3_THEME_UNKNOW_ACTION')
			)));
		}
	}

	public static function positions(){
		JFactory::getLanguage()->load(T3V3_PLUGIN, JPATH_ADMINISTRATOR);

		$japp = JFactory::getApplication();
		if(!$japp->isAdmin()){
			$tpl = $japp->getTemplate(true);
		} else {

			$tplid = JRequest::getCmd('view') == 'style' ? JRequest::getCmd('id', 0) : false;
			if(!$tplid){
				die(json_encode(array(
					'error' => JText::_('T3V3_THEME_UNKNOW_ACTION')
					)));
			}

			$cache = JFactory::getCache('com_templates', '');
			if (!$templates = $cache->get('jat3tpl')) {
				// Load styles
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select('id, home, template, s.params');
				$query->from('#__template_styles as s');
				$query->where('s.client_id = 0');
				$query->where('e.enabled = 1');
				$query->leftJoin('#__extensions as e ON e.element=s.template AND e.type='.$db->quote('template').' AND e.client_id=s.client_id');

				$db->setQuery($query);
				$templates = $db->loadObjectList('id');
				foreach($templates as &$template) {
					$registry = new JRegistry;
					$registry->loadString($template->params);
					$template->params = $registry;
				}
				$cache->store($templates, 'tjat3tpl');
			}

			if (isset($templates[$tplid])) {
				$tpl = $templates[$tplid];
			}
			else {
				$tpl = $templates[0];
			}
		}

		$t3v3 = T3v3::getSite($tpl);
		$layout = JRequest::getCmd('layout', 'default');
		ob_start();
		$t3v3->loadLayout ($layout);
		ob_clean();
		die(json_encode($t3v3->getPositions()));
	}

	static public function unittest () {
		$app = JFactory::getApplication();
		$tpl = $app->getTemplate(true);
		$t3v3 = T3V3::getApp($tpl);
		$layout = JRequest::getCmd('layout', 'default');
		ob_start();
		$t3v3->loadLayout ($layout);
		ob_clean();
		echo "Positions for layout [$layout]: <br />";
		var_dump ($t3v3->getPositions());
		exit;
	}	
}