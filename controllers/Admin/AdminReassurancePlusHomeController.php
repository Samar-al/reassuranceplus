<?php
/**
 * AdminReassurancePlusHomeController.php
 *
 * @author    Samar Al khalil
 * @copyright Copyright (c) Your Year
 * @license   License (if applicable)
 * @category  Controllers
 * @package   AdminControllers
 * @subpackage ReassurancePlus
 */
require_once(_PS_MODULE_DIR_.'reassuranceplus/classes/ReassurancePlusClass.php');

class AdminReassurancePlusHomeController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = ReassurancePlusClass::$definition['table'];
        $this->className = ReassurancePlusClass::class;
        $this->module = Module::getInstanceByName('reassuranceplus');
        $this->identifier = ReassurancePlusClass::$definition['primary'];
        $this->_orderBy = ReassurancePlusClass::$definition['primary'];
        $this->lang = false;
        $this->allow_export = true;
        $this->context = Context::getContext();
       
       

       

        parent::__construct();

        $this->fields_list = [
            'id_reassurance' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'title' => [
                'title' => $this->l('Title'),
                'filter_key' => 'a!title',
            ],
            'description' => [
                'title' => $this->l('Description'),
                'filter_key' => 'a!description',
            ],
            'image' => [
                'title' => $this->l('Image'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
       ];

        
        $this->addRowAction('view');
        $this->addRowAction('edit');
        $this->addRowAction('delete');

    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' =>'Reassurance Information',
                'icon' => 'icon-cog',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => 'Titre',
                    'name' => 'title',
                    'required' => true,
                    'lang' => false,
                ],
                [
                    'type' => 'textarea',
                    'label' => 'Description',
                    'name' => 'description',
                    'required' => true,
                    'lang' => false,
                    'max_length' => 100,
                    'autoload_rte' => true,
                ],
                [
                    'type' => 'file',
                    'label' => 'Image',
                    'name' => 'image',
                    'required' => true,
                ],
            ],
            'submit' => [
                'title' => 'Save',
                'name' => 'submit',
                'class' => 'btn btn-warning',
           ],
           'form' => [
            'enctype' => 'multipart/form-data', // Set enctype to enable file uploads
        ],
        ];

        return parent::renderForm();
    }

    public function renderView()
    {
        $sql = new DbQuery();
        $sql->select('*')
            ->from($this->table);
        $data = Db::getInstance()->executeS($sql);
        $tplFile = _PS_MODULE_DIR_.'reassuranceplus/views/templates/admin/view.tpl';
        $tpl = $this->context->smarty->createTemplate($tplFile);
        $tpl->assign([
            'data' => $data[0],
            'backUrl' => $this->context->link->getAdminLink('AdminReassurancePlusHome'),
            
        ]);
        return $tpl->fetch();
    }

    public function postProcess()
    {
       
        if (Tools::isSubmit('submit')) {
           
            // Handle form submission
            if ($this->processImageUpload()) {
                // Save reassurance item to the database
                if ($this->className && ($id = (int) Tools::getValue($this->identifier))) {
                    $reassurance = new $this->className($id);
                    if (Validate::isLoadedObject($reassurance)) {
                        $this->copyFromPost($reassurance, $this->table);
                        $reassurance->update();
                    }
                } else {
                    $reassurance = new ReassurancePlusClass();
                    $this->copyFromPost($reassurance, $this->table);
                    $reassurance->add();
                }
    
                // Redirect to the list page after saving
                $this->redirect_after = self::$currentIndex . '&token=' . $this->token;
            } else {
                // Handle errors if the image upload failed
                $this->errors[] = $this->l('Error uploading the image.');
            }
        }
    
    }
    

    protected function processImageUpload()
    {

        $uploadDir = _PS_MODULE_DIR_ . 'reassuranceplus/views/img/images/';
        $fileName = 'reassurance_' . md5(uniqid()) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    
        $targetFile = $uploadDir . $fileName;
    
        $allowedExtensions = array('jpg', 'jpeg', 'png');
        if (!in_array(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION), $allowedExtensions)) {
            $this->errors[] = $this->l('Invalid file format. Allowed formats are jpg, jpeg, and png.');
            return false;
        }
    
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            return false;
        }
    
        $_POST['image'] = $fileName;
        return true;

       
      
    }

   
}
