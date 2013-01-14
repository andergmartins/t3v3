<?php
/**
 * $JA#COPYRIGHT$
 */

/**
 *
 * Admin helper module class
 * @author JoomlArt
 *
 */
class T3v3AdminTheme
{
	/**
	 *
	 * save Profile
	 */
	
	public static function response($data){
		die(json_encode($data));
	}
	
	public static function error($msg){
		return self::response(array('error' => $msg));
	}
	
	public static function save($path)
	{
		$result = array();
		
		if(empty($path)){
			return self::error(JText::_('T3V3_TM_UNKNOWN_THEME'));
		}
		
		$theme = JFactory::getApplication()->input->getCmd('theme');
		$from = JFactory::getApplication()->input->getCmd('from');
		if (!$theme) {
		   return self::error(JText::_('T3V3_TM_INVALID_DATA_TO_SAVE'));
		}

		$file = $path . '/less/themes/' . $theme . '/variables-custom.less';

		if(!class_exists('JRegistryFormatLESS')){
			t3v3import('format/less');
		}
		$variables = new JRegistry();
		$variables->loadObject($_POST);
		
		$data = $variables->toString('LESS');
		$type = 'new';
		if (JFile::exists($file)) {
			@chmod($file, 0777);
			$type = 'overwrite';
		} else {

			if(JFolder::exists($path . '/less/themes/' . $from)){
				if(@JFolder::copy($path . '/less/themes/' . $from, $path . '/less/themes/' . $theme) != true){
					return self::error(JText::_('T3V3_TM_NOT_FOUND'));
				}
			} else if($from == 'base') {
				$dummydata = "";
				@JFile::write($path . '/less/themes/' . $theme . '/template.less', $dummydata);
				@JFile::write($path . '/less/themes/' . $theme . '/variables.less', $dummydata);
				@JFile::write($path . '/less/themes/' . $theme . '/template-responsive.less', $dummydata);
			}
		}
		
		$return = @JFile::write($file, $data);

		if (!$return) {
			return self::error(JText::_('T3V3_TM_OPERATION_FAILED'));
		} else {
			$result['success'] = JText::sprintf('T3V3_TM_SAVE_SUCCESSFULLY', $theme);
			$result['theme'] = $theme;
			$result['type'] = $type;
		}

		//LessHelper::compileForTemplate(T3V3_TEMPLATE_PATH, $theme);
		t3v3import ('core/less');
		T3V3Less::compileAll($theme);
		return self::response($result);
	}

	/**
	 *
	 * Clone Profile
	 */
	public static function duplicate($path)
	{
		$theme = JFactory::getApplication()->input->getCmd('theme');
		$from = JFactory::getApplication()->input->getCmd('from');
		$result = array();
		
		if (empty($theme) || empty($from)) {
			return self::error(JText::_('T3V3_TM_INVALID_DATA_TO_SAVE'));
		}

		$source = $path . '/less/themes/' . $from;
		if (!JFolder::exists($source)) {
			return self::error(JText::sprintf('T3V3_TM_NOT_FOUND', $from));
		}
		
		$dest = $path . '/less/themes/' . $theme;
		if (JFolder::exists($dest)) {
			return self::error(JText::sprintf('T3V3_TM_EXISTED', $theme));
		}

		$result = array();
		if (@JFolder::copy($source, $dest) == true) {
			$result['success'] = JText::_('T3V3_TM_CLONE_SUCCESSFULLY');
			$result['theme'] = $theme;
			$result['reset'] = true;
			$result['type'] = 'duplicate';
		} else {
			return self::error(JText::_('T3V3_TM_OPERATION_FAILED'));
		}
		
		//LessHelper::compileForTemplate(T3V3_TEMPLATE_PATH , $theme);
		t3v3import ('core/less');
		T3V3Less::compileAll($theme);
		return self::response($result);
	}

	/**
	 *
	 * Delete a profile
	 */
	public static function delete($path)
	{
		// Initialize some variables
		$theme = JFactory::getApplication()->input->getCmd('theme');
		$result = array();
		
		if (!$theme) {
			return self::error(JText::_('T3V3_TM_UNKNOWN_THEME'));
		}

		$file = $path . '/less/themes/' . $theme;
		$return = false;
		if (!JFolder::exists($file)) {
			return self::error(JText::sprintf('T3V3_TM_NOT_FOUND', $theme));
		}
		
		$return = @JFolder::delete($file);
		
		if (!$return) {
			return self::error(JText::sprintf('T3V3_TM_DELETE_FAIL', $file));
		} else {
			
			$result['template'] = '0';
			$result['success'] = JText::sprintf('T3V3_TM_DELETE_SUCCESSFULLY', $theme);
			$result['theme'] = $theme;
			$result['type'] = 'delete';
		}

		JFolder::delete($path . '/css/themes/' . $theme);
		return self::response($result);
	}

	/**
	 *
	 * Show thememagic form
	 */
	public static function thememagic($path)
	{
		$app = JFactory::getApplication();
		$isadmin = $app->isAdmin();
		$url = $isadmin ? JUri::root(true).'/' : JUri::current();
		$url .= (preg_match('/\?/', $url) ? '&' : '?').'themer=1';
		// show thememagic form

		//todo: Need to optimize here
		$tplparams = JApplication::getInstance('site')->getTemplate(true)->params;

		$jassetspath = T3V3_TEMPLATE_PATH;
		$jathemepath = $jassetspath . '/less/themes';
		if(!class_exists('JRegistryFormatLESS')){
			include_once T3V3_ADMIN_PATH . '/includes/format/less.php';
		}

		$themes = array();
		$jsondata = array();

		//push a default theme
		$tobj = new stdClass();
		$tobj->id = 'base';
		$tobj->title = JText::_('JDEFAULT');
		$themes['base'] = $tobj;
		$varfile = $jassetspath . '/less/variables.less';
		if(file_exists($varfile)){
			$params = new JRegistry;
			$params->loadString(JFile::read($varfile), 'LESS');
			$jsondata['base'] = $params->toArray();
		}

		if (JFolder::exists($jathemepath)) {
			$jathemes = JFolder::folders($jathemepath);
			if (count($jathemes)) {
				foreach ($jathemes as $theme) {
					$varsfile = $jathemepath . '/' . $theme . '/variables-custom.less';
					if(file_exists($varsfile)){

						$tobj = new stdClass();
						$tobj->id = $theme;
						$tobj->title = $theme;

						//check for all less file in theme folder
						$params = false;
						$others = JFolder::files($jathemepath . '/' . $theme, '.less');
						foreach($others as $other){
							//get those developer custom values
							if($other == 'variables.less'){
								$params = new JRegistry;
								$params->loadString(JFile::read($jathemepath . '/' . $theme . '/variables.less'), 'LESS');								
							}

							if($other != 'variables-custom.less'){
								$tobj->$other = true; //JFile::read($jathemepath . '/' . $theme . '/' . $other);
							}
						}

						$cparams = new JRegistry;
						$cparams->loadString(JFile::read($varsfile), 'LESS');
						if($params){
							foreach ($cparams->toArray() as $key => $value) {
								$params->set($key, $value);
							}	
						} else {
							$params = $cparams;
						}

						$themes[$theme] = $tobj;
						$jsondata[$theme] = $params->toArray();
					}
				}
			}
		}

		$langs = array (
			'addTheme' => JText::_('T3V3_TM_ASK_ADD_THEME'),
			'delTheme' => JText::_('T3V3_TM_ASK_DEL_THEME'),
			'correctName' => JText::_('T3V3_TM_ASK_CORRECT_NAME'),
			'themeExist' => JText::_('T3V3_TM_EXISTED'),
			'saveChange' => JText::_('T3V3_TM_ASK_SAVE_CHANGED'),
			'previewError' => JText::_('T3V3_TM_PREVIEW_ERROR'),
			'lblCancel' => JText::_('JCANCEL'),
			'lblOk'	=> JText::_('T3V3_TM_LABEL_OK'),
			'lblNo' => JText::_('JNO'),
			'lblYes' => JText::_('JYES')
		);

		$backurl = JFactory::getURI();
		$backurl->delVar('t3action');
		$backurl->delVar('t3task');

		$form = new JForm('thememagic.themer', array('control' => 'jaform'));
		$form->load(JFile::read(T3V3_PATH . DIRECTORY_SEPARATOR . 'params' . DIRECTORY_SEPARATOR . 'thememagic.xml'));
		$form->loadFile(T3V3_TEMPLATE_PATH . DIRECTORY_SEPARATOR . 'templateDetails.xml', false, '//config');

		$fieldSets = $form->getFieldsets('thememagic');

		include T3V3_ADMIN_PATH.'/admin/thememagic/thememagic.tpl.php';
		
		exit();
	}

	public static function addAssets(){
		$japp = JFactory::getApplication();
		$jdoc = JFactory::getDocument();
		$params = $japp->getTemplate(true)->params;
		if(defined('T3V3_THEMER') && $params->get('themermode', 0)){
			
			$jdoc->addStyleSheet(T3V3_URL.'/css/thememagic.css');
			$jdoc->addScript(T3V3_URL.'/js/thememagic.js');
			
			$theme = $params->get('theme');
			$params = new JRegistry;
			$themeinfo = new stdClass;

			if($theme){
				$themepath = T3V3_TEMPLATE_PATH . '/less/themes/' . $theme;

				if(file_exists($themepath . '/variables-custom.less')){
					if(!class_exists('JRegistryFormatLESS')){
						include_once T3V3_ADMIN_PATH . '/includes/format/less.php';
					}

					//default variables
					$varfile = T3V3_TEMPLATE_PATH . '/less/variables.less';
					if(file_exists($varfile)){
						$params->loadString(JFile::read($varfile), 'LESS');
						
						//get all less files in "theme" folder
						$others = JFolder::files($themepath, '.less');
						foreach($others as $other){
							//get those developer custom values
							if($other == 'variables.less'){
								$devparams = new JRegistry;
								$devparams->loadString(JFile::read($themepath . '/variables.less'), 'LESS');

								//overwrite the default variables
								foreach ($devparams->toArray() as $key => $value) {
									$params->set($key, $value);
								}								
							}

							//ok, we will import it later
							if($other != 'variables-custom.less' && $other != 'variables.less'){
								$themeinfo->$other = true;
							}
						}

						//load custom variables
						$cparams = new JRegistry;
						$cparams->loadString(JFile::read($themepath . '/variables-custom.less'), 'LESS');
						
						//and overwrite those defaults variables
						foreach ($cparams->toArray() as $key => $value) {
							$params->set($key, $value);
						}
					}
				}
			}

			$jdoc->addScriptDeclaration('
				var T3V3Theme = window.T3V3Theme || {};
				T3V3Theme.vars = ' . json_encode($params->toArray()) . ';
				T3V3Theme.others = ' . json_encode($themeinfo) . ';
				T3V3Theme.theme = \'' . $theme . '\';
				T3V3Theme.base = \'' . JURI::base() . '\';
				if(typeof less != \'undefined\'){
					
					//we need to build one - cause the js will have unexpected behavior
					
					if(window.parent.T3V3Theme && window.parent.T3V3Theme.applyLess){
						window.parent.T3V3Theme.applyLess(true);
					} else {
						less.refresh();
					}
				}'
			);
		}
	}
}