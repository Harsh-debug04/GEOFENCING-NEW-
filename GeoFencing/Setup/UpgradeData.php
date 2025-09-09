<?php


namespace GLow\ProductFlow\Setup;

use Magento\Customer\Model\Customer;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;
use Magento\Customer\Api\CustomerMetadataInterface;



class UpgradeData implements UpgradeDataInterface
{

    private $customerSetupFactory;

    public function __construct(
        CustomerSetupFactory $customerSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory,
        \Magento\Catalog\Model\Config $catalogueConfig,
        \Magento\Eav\Api\AttributeManagementInterface $attributeManagement
    ) {
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->_catalogueConfigue = $catalogueConfig;
        $this->_attributeManagement = $attributeManagement;
    }

    /**
     * {@inheritdoc}
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context){

        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory
        ->create(['setup' => $setup]);  


        if (version_compare($context->getVersion(), "1.1.8", "<")) {
            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                'my_custom_amount',
                [
                    'type' => 'decimal',
                    'backend' => '',
                    'frontend' => '',
                    'label' => 'My Custom Amount',
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => false,
                    'required' => false,
                    'user_defined' => true,
                    'default' => null,
                    'searchable' => true,
                    'filterable' => true,
                    'comparable' => true,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'is_wysiwyg_enabled' => false,
                    'apply_to' => 'simple',
                    'group' => 'General'
                ]
            );
            $entityTypeId = $eavSetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetIds = $eavSetup->getAllAttributeSetIds($entityTypeId);
            foreach ($attributeSetIds as $attributeSetId) {
                if ($attributeSetId) {
                    $group_id = $this->_catalogueConfigue->getAttributeGroupId($attributeSetId, 'General');
                    $this->_attributeManagement->assign(
                        'catalog_product',
                        $attributeSetId,
                        $group_id,
                        'my_custom_amount',
                        1
                    );
                }
            }
            $setup->endSetup();   
        }
        
    }
}