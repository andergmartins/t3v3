<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

JFormHelper::loadFieldClass('filelist');

/**
 * Supports an HTML select list of files
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @since       11.1
 */
class JFormFieldJAFileList extends JFormFieldFileList
{

	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since  11.1
	 */
	public $type = 'FileList';

	/**
	 * The initialised state of the document object.
	 *
	 * @var    boolean
	 * @since  1.6
	 */
	protected static $initialised = false;

	/**
	 * Method to get the list of files for the field options.
	 * Specify the target directory with a directory attribute
	 * Attributes allow an exclude mask and stripping of extensions from file name.
	 * Default attribute may optionally be set to null (no file) or -1 (use a default).
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getOptions()
	{
		//$table = JTable::getInstance('Style', 'TemplatesTable', array());
		//$table->load((int) JRequest::getInt('id'));
		// update path to this template 
		$path = (string) $this->element['directory'];
		if (!is_dir($path)) {
			$this->element['directory'] =  T3V3_TEMPLATE_PATH . DIRECTORY_SEPARATOR . $path;
		}

		if($this->element['ajax']):

			$colon = '';
			$langs = array(
				'T3V3_POS_MAINNAV' => JText::_('T3V3_POS_MAINNAV'),
				'T3V3_POS_MAINNAV_DESC' => JText::_('T3V3_POS_MAINNAV_DESC'),
				'T3V3_POS_SIDEBAR-1' => JText::_('T3V3_POS_SIDEBAR-1'),
				'T3V3_POS_SIDEBAR-1_DESC' => JText::_('T3V3_POS_SIDEBAR-1_DESC'),
				'T3V3_POS_SIDEBAR-2' => JText::_('T3V3_POS_SIDEBAR-2'),
				'T3V3_POS_SIDEBAR-2_DESC' => JText::_('T3V3_POS_SIDEBAR-2_DESC'),
				'T3V3_POS_MASTCOL' => JText::_('T3V3_POS_MASTCOL'),
				'T3V3_POS_MASTCOL_DESC' => JText::_('T3V3_POS_MASTCOL_DESC'),
				'T3V3_POS_TOPSL' => JText::_('T3V3_POS_TOPSL'),
				'T3V3_POS_TOPSL_DESC' => JText::_('T3V3_POS_TOPSL_DESC'),
				'T3V3_POS_BOTSL' => JText::_('T3V3_POS_BOTSL'),
				'T3V3_POS_BOTSL_DESC' => JText::_('T3V3_POS_BOTSL_DESC'),
				'T3V3_POS_FOOTNAV' => JText::_('T3V3_POS_FOOTNAV'),
				'T3V3_POS_FOOTNAV_DESC' => JText::_('T3V3_POS_FOOTNAV_DESC'),
				'T3V3_POS_SECTION' => JText::_('T3V3_POS_SECTION'),
				'T3V3_POS_SECTION_DESC' => JText::_('T3V3_POS_SECTION_DESC')
			);
			?>
			<script type="text/javascript">
				jQuery(window).on('load', function(){
					JADepend.addajax('<?php echo $this->name ?>', {
						<?php if ($this->element['url']):
							$colon = ',';
						?>
						url: '<?php echo $this->element['url'] ?>'
						<?php
							 endif;
						?>
						<?php if ($this->element['query']): 
							echo $colon;
							$colon = ',';
						?>
						query: '<?php echo $this->element['query'] ?>'
						<?php
							endif;
						?>
						<?php if ($this->element['langsprefix']): 
							echo $colon;
							$colon = ',';
						?>
						langsprefix: '<?php echo $this->element['langsprefix'] ?>'
						<?php
							endif;
						?>
					});

					JADepend.langs = JADepend.langs || {};
					jQuery.extend(JADepend.langs, <?php echo json_encode($langs) ?>);				
				});
			</script>
			<?php
		endif;
		
 		return parent::getOptions();
	}
}
?>