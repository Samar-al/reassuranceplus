<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

 if(!defined('_PS_VERSION_')) {
    exit;
}

class ReassurancePlus extends Module
{
    public function __construct()
    {
        $this->name ='reassuranceplus';

        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'samar Alkhalil';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '1.6',
            'max' => _PS_VERSION_
        ];

        parent::__construct();

        $this->bootstrap = true;
        $this->displayName = $this->l('Reassusrance Plus');

        $this->description = $this->l('Afficher des éléments de reassusrance sur l\'accueil, la page produit et catégorie');
        $this->confirmUninstall = $this->l('Êtes-vous sur de vouloir supprimer ce module');
    }

    public function install()
    {
    if (!parent::install() ||
        !$this->registerHook('displayContentWrapperTop') ||
        !$this->registerHook('displayContentWrapperBottom') ||
        !$this->registerHook('displayShoppingCartFooter') ||
        !$this->registerHook('LeftColumn') ||
        !$this->registerHook('displayHeaderCategory') ||
        !$this->registerHook('displayReassurance') ||
        !$this->createTable() ||
        !$this->createTableReassuranceCategory() ||
        !$this->createTableReassuranceProduct() ||
        !$this->installTab('AdminReassurancePlusHome', 'Home Reassurance', 'IMPROVE') ||
        !$this->installTab('AdminReassurancePlusCategory', 'Reassurance Categories', 'IMPROVE') ||
        !$this->installTab('AdminReassurancePlusProduct', 'Reassurance Products', 'IMPROVE')
        
    ) {
        return false;
    }

        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall() ||
            !$this->unregisterHook('displayContentWrapperTop') ||
            !$this->unregisterHook('displayContentWrapperBottom') ||
            !$this->unregisterHook('displayShoppingCartFooter') ||
            !$this->unregisterHook('LeftColumn') ||
            !$this->unregisterHook('displayHeaderCategory') ||
            !$this->unregisterHook('displayReassurance') ||
            !$this->removeTable() ||
            !$this->removeTableReassuranceCategory() ||
            !$this->removeTableReassuranceProduct() ||
            !$this->uninstallTab('AdminReassurancePlusHome') ||
            !$this->uninstallTab('AdminReassurancePlusCategory') ||
            !$this->uninstallTab('AdminReassurancePlusProduct')
        ) {
            return false;
        }

        return true;
    }

    public function enable($force_all = false)
    {
        return parent::enable($force_all)
            && $this->installTab('AdminReassurancePlusHome', 'Home Reassurance', 'IMPROVE')
            && $this->installTab('AdminReassurancePlusCategory', 'Reassurance Categories', 'IMPROVE')
            && $this->installTab('AdminReassurancePlusProduct', 'Reassurance Products', 'IMPROVE');   
        
    }

    public function disable($force_all = false){
        return parent::disable($force_all)
            && $this->uninstallTab('AdminReassurancePlusHome')
            && $this->uninstallTab('AdminReassurancePlusCategory')
            && $this->uninstallTab('AdminReassurancePlusProduct');    
        
    }

    public function getContent()
    {
        
    }

    public function createTable()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'reassurance_plus` (
            `id_reassurance` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT NOT NULL,
            `image` VARCHAR(255) NOT NULL,
            PRIMARY KEY (`id_reassurance`)
        )');   
    }

    public function removeTable(){
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'reassurance_plus`');
    }

    public function createTableReassuranceCategory()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'reassurance_category` (
            `id_reassurance` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT NOT NULL,
            `image` VARCHAR(255) NOT NULL,
            `id_category` INT UNSIGNED NOT NULL,
            PRIMARY KEY (`id_reassurance`)
        )');
    }

    public function removeTableReassuranceCategory(){
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'reassurance_category`');
    }

    public function createTableReassuranceProduct()
    {
        return Db::getInstance()->execute('CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'reassurance_product` (
            `id_reassurance` INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT NOT NULL,
            `image` VARCHAR(255) NOT NULL,
            `id_product` INT UNSIGNED NOT NULL,
            PRIMARY KEY (`id_reassurance`)
        )');
    }
    public function removeTableReassuranceProduct(){
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `'._DB_PREFIX_.'reassurance_product`');
    }

    private function installTab($className, $tabName, $tabParentName){
        $tabId = (int) Tab::getIdFromClassName($className);
        if (!$tabId) {
            $tabId = null;
        }

        $tab = new Tab($tabId);
        $tab->active = 1;
        $tab->class_name = $className;
        // Only since 1.7.7, you can define a route name
        $tab->name = array();
        foreach (Language::getLanguages() as $lang) {
            $tab->name[$lang['id_lang']] = $this->trans($tabName, array(), 'Modules.MyModule.Admin', $lang['locale']);
        }
        $tab->id_parent = (int) Tab::getIdFromClassName($tabParentName);
        $tab->module = $this->name;

        return $tab->save();
    }

    private function uninstallTab($className){
        $tabId = (int) Tab::getIdFromClassName($className);
        if (!$tabId) {
            return true;
        }

        $tab = new Tab($tabId);

        return $tab->delete();
    }

    public function getReassuranceItems()
    {
        $reassuranceItems = [];
        $items = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'reassurance_plus`');

        foreach ($items as $item) {
            $reassuranceItems[] = [
                'image' => $item['image'],
                'title' => $item['title'],
                'description' => $item['description'],
            ];
        }

        return $reassuranceItems;
    }

    public static function getReassuranceBlocksByCategoryId($id_category)
    {
        $sql = new DbQuery();
        $sql->select('*')
            ->from('reassurance_category')
            ->where('id_category = ' . (int)$id_category);

        return Db::getInstance()->executeS($sql);
    }

    public static function getReassuranceByProductId($id_product){
        $sql = new DbQuery();
        $sql->select('*')
            ->from('reassurance_product')
            ->where('id_product = ' . (int)$id_product);

        return Db::getInstance()->executeS($sql);
    }

    public function hookDisplayContentWrapperTop($params)
    {
        if ($this->context->controller->php_self === 'index') {
            
            $reassuranceItems = $this->getReassuranceItems();
            $this->context->smarty->assign('reassuranceItems', $reassuranceItems);
    
            return $this->display(__FILE__, 'views/templates/hooks/reassuranceplus.tpl');
        }
        return '';
    }

    public function hookDisplayContentWrapperBottom()
    {
        if ($this->context->controller->php_self === 'index') {
        
            $reassuranceItems = $this->getReassuranceItems();
            $this->context->smarty->assign('reassuranceItems', $reassuranceItems);
    
            return $this->display(__FILE__, 'views/templates/hooks/reassuranceplus.tpl');
        }
        return '';
    }

    public function hookDisplayShoppingCartFooter()
    {
        $reassuranceItems = $this->getReassuranceItems();
        $this->context->smarty->assign('reassuranceItems', $reassuranceItems);

        return $this->display(__FILE__, 'views/templates/hooks/reassuranceplus.tpl');
    }

    public function hookDisplayLeftColumn(){
        $id_category = (int)Tools::getValue('id_category');
        $reassurance_blocks = $this->getReassuranceBlocksByCategoryId($id_category);
       
        $tplFile = _PS_MODULE_DIR_ . 'reassuranceplus/views/templates/hooks/carousel_left_column.tpl';
        $tpl = $this->context->smarty->createTemplate($tplFile);
        $tpl->assign([
            'reassuranceItems' => $reassurance_blocks,
        ]);
        return $tpl->fetch();
    }

    public function hookDisplayHeaderCategory(){
        $id_category = (int)Tools::getValue('id_category');
        $reassurance_blocks = $this->getReassuranceBlocksByCategoryId($id_category);
       
        $tplFile = _PS_MODULE_DIR_ . 'reassuranceplus/views/templates/hooks/reassuranceplus.tpl';
        $tpl = $this->context->smarty->createTemplate($tplFile);
        $tpl->assign([
            'reassuranceItems' => $reassurance_blocks,
        ]);
        return $tpl->fetch();
    }

    public function hookDisplayReassurance(){
        if ($this->context->controller->php_self == 'product') {
            
            $productId =(int)Tools::getValue('id_product');
            $reassuranceItems = $this->getReassuranceByProductId($productId); 
            $tplFile = _PS_MODULE_DIR_ . 'reassuranceplus/views/templates/hooks/reassuranceplus.tpl';
            $tpl = $this->context->smarty->createTemplate($tplFile);
            $tpl->assign([
                'reassuranceItems' => $reassuranceItems,
            ]);
            return $tpl->fetch();
        }
    }
}