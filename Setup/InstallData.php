<?php
namespace Sas\Movies\Setup;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetup
     */
    private $eavSetup;

    public function __construct(EavSetup $eavSetup)
    {
        $this->eavSetup = $eavSetup;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->eavSetup->addAttribute(
            Product::ENTITY,
            'favorite',
            [
                'type' => 'int',
                'label' => 'Favorite',
                'input' => 'text',
                'required' => false,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Product Details',
                'is_unique' => 0,
                'is_required' => 0,
                'is_searchable' => 0,
                'is_visible_in_advanced_search' => 0,
                'is_comparable' => 0,
                'is_filterable' => 0,
                'is_filterable_in_search' => 0,
                'is_used_for_promo_rules' => 0,
                'is_html_allowed_on_front' => 0,
                'is_visible_on_front' => 1,
                'used_in_product_listing' => 1,
                'used_for_sort_by' => 0,
                'user_defined' => true,
            ]
        );

        $setup->endSetup();
    }
}
