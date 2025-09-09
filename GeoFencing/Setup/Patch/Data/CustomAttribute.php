<?php

namespace AgriCart\GeoFencing\Setup\Patch\Data;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CustomAttribute implements DataPatchInterface
{
    private $moduleDataSetup;
    private $eavSetupFactory;

    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function apply()
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->addAttribute('catalog_product', 'Geo_Location', [
            'type'                    => 'varchar',
            'backend'                 => '',
            'frontend'                => '',
            'label'                   => 'Geo Location',
            'input'                   => 'text',
            'class'                   => '',
            'source'                  => '',
            'global'                  => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible'                 => true,
            'required'                => false,
            'user_defined'            => true,
            'default'                 => '',
            'searchable'              => false,
            'filterable'              => false,
            'comparable'              => false,
            'visible_on_front'        => true,
            'used_in_product_listing' => true,
            'unique'                  => false,
            'apply_to'                => ''
        ]);


        $attributeSetId = $eavSetup->getDefaultAttributeSetId(\Magento\Catalog\Model\Product::ENTITY);
        $attributeGroupId = $eavSetup->getDefaultAttributeGroupId(\Magento\Catalog\Model\Product::ENTITY, $attributeSetId);

        $eavSetup->addAttributeToGroup(
            \Magento\Catalog\Model\Product::ENTITY,
            $attributeSetId,
            $attributeGroupId,
            'Geo_Location',
            999
        );
    }

    public static function getDependencies()
    {
        return [];
    }

    public function getAliases()
    {
        return [];
    }
}
