<?php
/**
 * AdminReassurancePlusCategoryController.php
 *  @author    Samar Al khalil
 *  @copyright Copyright (c) Your Year
 *  @license   License (if applicable)
 *  @category  Controllers
 *  
 */
require_once(_PS_MODULE_DIR_.'reassuranceplus/classes/ReassurancePlusCategoryClass.php');

class AdminReassurancePlusCategoryController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = ReassurancePlusCategoryClass::$definition['table'];
        $this->className = ReassurancePlusCategoryClass::class;
        $this->module = Module::getInstanceByName('reassuranceplus');
        $this->identifier = ReassurancePlusCategoryClass::$definition['primary'];
        $this->_orderBy = ReassurancePlusCategoryClass::$definition['primary'];
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
                'title' => $this->l('Titre'),
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
            'id_category' => [
                'title' => $this->l('Id et nom de la categorie'),
                'filter_key' => 'c!name',
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'havingFilter' => true,
                'callback' => 'getCategoryIdAndName'
            ],
        ];

        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function getCategoryIdAndName($id_category, $row)
    {
        $category = new Category($id_category, Context::getContext()->language->id);
        if (Validate::isLoadedObject($category)) {
            return $id_category . ' - ' . $category->name;
        }

        return '-';
    }


    public function renderForm()
    {
        $categories = Category::getCategories(Context::getContext()->language->id, false, false);
        $categoryOptions = [];
        foreach ($categories as $category) {
            $categoryOptions[] = [
                'id_option' => $category['id_category'],
                'name' => $category['name'],
            ];
        }
        $this->fields_form = [
            'legend' => [
                'title' => 'Reassurance Information',
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
                [
                    'type' => 'select',
                    'label' => 'Category',
                    'name' => 'id_category',
                    'required' => true,
                    'options' => [
                        'query' => $categoryOptions,
                        'id' => 'id_option',
                        'name' => 'name',
                    ],
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


    public function postProcess()
    {
       
        if (Tools::isSubmit('submit')) {
            // Handle image upload
            if ($_FILES['image']['tmp_name']) {
                if (!$this->processImageUpload()) {
                    $this->errors[] = $this->l('Error uploading the image.');
                }
            }
            // Save reassurance item
            if (empty($this->errors)) {
                parent::postProcess();
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
