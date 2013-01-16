<?php

class T3v3AdminMegamenu {
	public static function display () {
		t3v3import('menu/megamenu');
		$input = JFactory::getApplication()->input;
		$menutype = $input->get ('t3menu', 'mainmenu');
		$file = T3V3_TEMPLATE_PATH.'/etc/megamenu.ini';
		$currentconfig = json_decode(@file_get_contents ($file), true);
		$mmconfig = ($currentconfig && isset($currentconfig[$menutype])) ? $currentconfig[$menutype] : array();
		$mmconfig['editmode'] = true;
		$menu = new T3V3MenuMegamenu ($menutype, $mmconfig);
		$buffer = $menu->render(true);
		// replace image path
		$base   = JURI::base(true).'/administrator/';
		$protocols = '[a-zA-Z0-9]+:'; //To check for all unknown protocals (a protocol must contain at least one alpahnumeric fillowed by :
		$regex     = '#(src)="(?!/|' . $protocols . '|\#|\')([^"]*)"#m';
		$buffer    = preg_replace($regex, "$1=\"$base\$2\"", $buffer);
		
		//remove invisibile content	
		$buffer = preg_replace(array( '@<style[^>]*?>.*?</style>@siu', '@<script[^>]*?.*?</script>@siu'), array('', ''), $buffer);

		echo $buffer;
	}

	public static function save () {
		$input = JFactory::getApplication()->input;
		$mmconfig = $input->getString ('config');
		$menutype = $input->get ('menutype', 'mainmenu');
		$file = T3V3_TEMPLATE_PATH.'/etc/megamenu.ini';
		$currentconfig = json_decode(@file_get_contents($file), true);
		if (!$currentconfig) $currentconfig = array();
		$currentconfig[$menutype] = json_decode($mmconfig, true);
		JFile::write ($file, json_encode ($currentconfig));
	}
}
	