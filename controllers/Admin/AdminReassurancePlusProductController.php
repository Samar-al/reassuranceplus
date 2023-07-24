<?php
/**
 * AdminReassurancePlusProductController.php
 * @author    Samar Al khalil
 * @copyright Copyright (c) Your Year
 * @license   License (if applicable)
 * @category  Controllers
 * @package   AdminControllers
 * @subpackage ReassurancePlus
 * 
 */
require_once(_PS_MODULE_DIR_.'reassuranceplus/classes/ReassurancePlusProductClass.php');

class AdminReassurancePlusProductController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = ReassurancePlusProductClass::$definition['table'];
        $this->className = ReassurancePlusProductClass::class;
        $this->module = Module::getInstanceByName('reassuranceplus');
        $this->identifier = ReassurancePlusProductClass::$definition['primary'];
        $this->_orderBy = ReassurancePlusProductClass::$definition['primary'];
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
            'id_product' => [
                'title' => $this->l('Identifiant et nom du produit'),
                'filter_key' => 'c!name',
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'havingFilter' => true,
                'callback' => 'getProductIdAndName',  
            ],
        ];

        $this->addRowAction('edit');
        $this->addRowAction('delete');
    }

    public function getProductIdAndName($id_product, $row)
    {
        $product = new Product($id_product, false, Context::getContext()->language->id);
        if (Validate::isLoadedObject($product)) {
            return $id_product . ' - ' . $product->name;
        }

        return '-';
    }


    public function renderForm()
    {
        $products = Product::getProducts($this->context->language->id, 0, 0, 'id_product', 'ASC');

        $productOptions = [];
        foreach ($products as $product) {
            $productOptions[] = [
                'id' => $product['id_product'],
                'name' => $product['name'],
            ];
        }
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
                [
                    'type' => 'select',
                    'label' => 'Associated Product',
                    'name' => 'id_product', // The name of the field in the database
                    'required' => true,
                    'options' => [
                        'query' => $productOptions,
                        'id' => 'id',
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
