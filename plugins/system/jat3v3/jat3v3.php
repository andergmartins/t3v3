<?php
/**
 * $JA#COPYRIGHT$
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Joomla! P3P Header Plugin
 *
 * @package		Joomla.Plugin
 * @subpackage	System.p3p
 */

class plgSystemJaT3v3 extends JPlugin
{
	//function onAfterInitialise(){
	function onAfterRoute(){
		include_once dirname(__FILE__) . '/includes/core/defines.php';
		$template = $this->detect();

		if($template){
			define ('T3V3_TEMPLATE', $template);
			define ('T3V3_TEMPLATE_URL', JURI::root(true).'/templates/'.T3V3_TEMPLATE);
			define ('T3V3_TEMPLATE_PATH', JPATH_ROOT . '/templates/' . T3V3_TEMPLATE);
			define ('T3V3_TEMPLATE_REL', 'templates/' . T3V3_TEMPLATE);

			$input = JFactory::getApplication()->input;

			if($input->getCmd('themer', 0)){
				define('T3V3_THEMER', 1);
			}

			if($input->getCmd('t3lock', '')){
				JFactory::getSession()->set('T3v3.t3lock', $input->getCmd('t3lock', ''));
				$input->set('t3lock', null);
			}
			
			include_once dirname(__FILE__) . '/includes/core/t3v3.php';
			
			if (!defined('T3V3')){
				throw new Exception(JText::_('T3V3_MSG_PACKAGE_DAMAGED'));
			}
			
			// capture for tm=1 => show theme magic
			if ($input->getCmd('tm') == 1) {
				$input->set('t3action', 'theme');
				$input->set('t3task', 'thememagic');
			}
			// excute action by T3v3
			if ($action = $input->getCmd ('t3action')) {
				t3v3import ('core/action');
				T3V3Action::run ($action);
			}
		}
	}
	
	function onBeforeRender(){
		if($this->detect()){
			$japp = JFactory::getApplication();
			if($japp->isAdmin()){

				$t3v3app = T3v3::getApp();
				$t3v3app->addAssets();
			} else {
				$params = $japp->getTemplate(true)->params;
				if(defined('T3V3_THEMER') && $params->get('themermode', 0)){
					t3v3import('admin/theme');
					T3v3AdminTheme::addAssets();
				}
			}
		}
	}
	
	function onBeforeCompileHead () {
		$app = JFactory::getApplication();
		if($this->detect() && !$app->isAdmin()){
			// call update head for replace css to less if in devmode
			$t3v3app = T3v3::getApp();
			if($t3v3app){
				$t3v3app->updateHead();
			}
		}
	}

	function onAfterRender ()
	{
		$japp = JFactory::getApplication();
		if($japp->isAdmin()){
			if($this->detect()){
				$t3v3app = T3v3::getApp();
				$t3v3app->render();
			}
		}
	}
	
	/**
     * Add JA Extended menu parameter in administrator
     *
     * @param   JForm   $form   The form to be altered.
     * @param   array   $data   The associated data for the form
     *
     * @return  null
     */
	function onContentPrepareForm($form, $data)
	{
		// extra option for menu item
		/*if ($form->getName() == 'com_menus.item') {
			$this->loadLanguage();
			JForm::addFormPath(T3V3_PATH . DIRECTORY_SEPARATOR . 'params');
			$form->loadFile('megaitem', false);

			$jversion = new JVersion;
			if(!$jversion->isCompatible('3.0')){
				$jdoc = JFactory::getDocument();
				$jdoc->addScript(T3V3_ADMIN_URL . '/admin/js/jquery-1.8.0.min.js');
				$jdoc->addScript(T3V3_ADMIN_URL . '/admin/js/jquery.noconflict.js');
			}

		} else 
		*/
		if($this->detect() && $form->getName() == 'com_templates.style'){
			$this->loadLanguage();
			JForm::addFormPath(T3V3_PATH . DIRECTORY_SEPARATOR . 'params');
			$form->loadFile('template', false);
		}
	}
	
	function onExtensionAfterSave($option, $data){
		if($this->detect() && $option == 'com_templates.style' && !empty($data->id)){
			//get new params value
			$japp = JFactory::getApplication();
			$params = new JRegistry;
			$params->loadString($data->params);
			$oparams = $japp->getUserState('oparams');

			//check for changed params
			$pchanged = array();
			foreach($oparams as $oparam){
				if($params->get($oparam['name']) != $oparam['value']){
					$pchanged[] = $oparam['name'];
				}
			}

			//if we have any changed, we will update to global
			if(count($pchanged)){

				//get all other styles that have the same template
				$db = JFactory::getDBO();
				$query = $db->getQuery(true);
				$query
					->select('*')
					->from('#__template_styles')
					->where('template=' . $db->quote($data->template));

				$db->setQuery($query);
				$themes = $db->loadObjectList();
				
				//update all global parameters
				foreach($themes as $theme){
					$registry = new JRegistry;
					$registry->loadString($theme->params);

					foreach($pchanged as $pname){
						$registry->set($pname, $params->get($pname)); //overwrite with new value
					}

					$query = $db->getQuery(true);
					$query
						->update('#__template_styles')
						->set('params =' . $db->quote($registry->toString()))
						->where('id =' . (int)$theme->id)
						->where('id <>' . (int)$data->id);

					$db->setQuery($query);
					$db->query();
				}
			}
		}
	}

	function detect()
	{
		static $t3v3;

		if (!isset($t3v3)) {
			$t3v3 = false; // set false
			$app = JFactory::getApplication();
			$input = JFactory::getApplication()->input;
			// get template name
			$tplname = '';
			if ($app->isAdmin()) {
				// if not login, do nothing
				$user = JFactory::getUser();
				if (!$user->id){
					return false;
				}

				if($tplname = $input->getCmd('t3template', '')){

				} else if($input->getCmd('option') == 'com_templates' && 
					(preg_match('/style\./', $input->getCmd('task')) || $input->getCmd('view') == 'style' || $input->getCmd('view') == 'template')
					){
					$db = JFactory::getDBO();
					$query = $db->getQuery(true);
					$id = $input->getInt('id');

					//when in POST the view parameter does not set
					if ($input->getCmd('view') == 'template') {						
						$query
						->select('element')
						->from('#__extensions')
						->where('extension_id='.(int)$id . ' AND type=' . $db->quote('template'));
					} else {
						$query
						->select('template')
						->from('#__template_styles')
						->where('id='.(int)$id);
					}

					$db->setQuery($query);
					$tplname = $db->loadResult();
				}

			} else {
				$tplname = $app->getTemplate(false);
			}

			if ($tplname) {				
					// parse xml
				$filePath = JPath::clean(JPATH_ROOT.'/templates/'.$tplname.'/templateDetails.xml');
				if (is_file ($filePath)) {
					$xml = JInstaller::parseXMLInstallFile($filePath);
					if (strtolower($xml['group']) == 'ja_t3v3') {
						$t3v3 = $tplname;
					}
				}
			}
		}
		return $t3v3;
	}

    /**
     * Implement event onRenderModule to include the module chrome provide by T3
     * This event is fired by overriding ModuleHelper class
     * Return false for continueing render module
     *
     * @param   object  &$module   A module object.
     * @param   array   $attribs   An array of attributes for the module (probably from the XML).
     *
     * @return  bool
     */
    function onRenderModule (&$module, $attribs)
    {
    	static $chromed = false;
        // Detect layout path in T3 themes
    	if ($this->detect()) {
            // Chrome for module
    		if (!$chromed) {
    			$chromed = true;
                // We don't need chrome multi times
    			$chromePath = T3V3Path::getPath('html/modules.php');
    			if (file_exists($chromePath)) {
    				include_once $chromePath;
    			}
    		}
    	}
    	return false;
    }

    /**
     * Implement event onGetLayoutPath to return the layout which override by T3 & T3 templates
     * This event is fired by overriding ModuleHelper class
     * Return path to layout if found, false if not
     *
     * @param   string  $module  The name of the module
     * @param   string  $layout  The name of the module layout. If alternative
     *                           layout, in the form template:filename.
     *
     * @return  null
     */
    function onGetLayoutPath($module, $layout)
    {
        // Detect layout path in T3 themes
    	if ($this->detect()) {
    		$tPath = T3V3Path::getPath('html/' . $module . '/' . $layout . '.php');
    		if ($tPath)
    			return $tPath;
    	}
    	return false;
    }	
}
