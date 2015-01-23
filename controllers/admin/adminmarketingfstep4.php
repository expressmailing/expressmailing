<?php
/**
 * 2014-2015 (c) Axalone France - Express-Mailing
 *
 * This file is a commercial module for Prestashop
 * Do not edit or add to this file if you wish to upgrade PrestaShop or
 * customize PrestaShop for your needs please refer to
 * http://www.express-mailing.com for more information.
 *
 * @author    Axalone France <info@express-mailing.com>
 * @copyright 2014-2015 (c) Axalone France
* @license   http://opensource.org/licenses/GPL-3.0  GNU General Public License, version 3 (GPL-3.0)
 */

include_once 'em_tools.php';

/**
 * Step 4 : CSV column selector
 */
class AdminMarketingFStep4Controller extends ModuleAdminController
{
	private $campaign_id = null;

	public function __construct()
	{
		$this->name = 'adminmarketingfstep4';
		$this->bootstrap = true;
		$this->module = 'expressmailing';
		$this->context = Context::getContext();
		$this->lang = false;
		$this->default_form_language = $this->context->language->id;

		$this->campaign_id = (int)Tools::getValue('campaign_id');

		if (empty($this->campaign_id))
		{
			Tools::redirectAdmin('index.php?controller=AdminMarketing&token='.Tools::getAdminTokenLite('AdminMarketing'));
			exit;
		}

		parent::__construct();
	}

	public function initToolbarTitle()
	{
		parent::initToolbarTitle();
		$this->toolbar_title = Translate::getModuleTranslation('expressmailing', 'Send a fax-mailing', 'adminmarketingfstep1');
	}

	public function renderList()
	{
		$this->getFieldsValues();
		$output = parent::renderForm();

		$display = $this->getTemplatePath().'csv_column_selector.tpl';
		$output .= $this->context->smarty->fetch($display);

		$footer = $this->getTemplatePath().'footer.tpl';
		$output .= $this->context->smarty->fetch($footer);

		return $output;
	}

	private function getFieldsValues()
	{
		$sql = new DbQuery();
		$sql->select('*');
		$sql->from('expressmailing_fax');
		$sql->where('campaign_id = '.$this->campaign_id);
		$result = Db::getInstance()->getRow($sql);
		$file_copy = $result['path_to_import'];

		$preview = array();
		$preview = EMTools::getCSVPreview($file_copy);

		$this->context->smarty->assign('media', 'fax');
		$this->context->smarty->assign('preview', $preview);
		$this->context->smarty->assign('campaign_id', $this->campaign_id);
		$this->context->smarty->assign('next_page', 'AdminMarketingFStep3');
		$this->context->smarty->assign('prev_page', 'AdminMarketingFStep3');

		return true;
	}

}