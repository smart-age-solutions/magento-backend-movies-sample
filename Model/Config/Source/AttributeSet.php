<?php

namespace Lima\Movie\Model\Config\Source;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory as EntityCollectionFactory;
use Magento\Eav\Model\ResourceModel\Entity\Type\CollectionFactory as EntityTypeCollectionFactory;

/**
 * Class AttributeSet
 * @package Lima\Movie\Model\Config\Source
 */
class AttributeSet implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var EntityCollectionFactory
     */
    protected $_entityCollectionFactory;

    /**
     * @var EntityTypeCollectionFactory
     */
    protected $_entityTypeCollectionFactory;

    /**
     * AttributeSet constructor.
     * @param EntityCollectionFactory $entityCollectionFactory
     * @param EntityTypeCollectionFactory $entityTypeCollectionFactory
     */
    public function __construct(
        EntityCollectionFactory $entityCollectionFactory,
        EntityTypeCollectionFactory $entityTypeCollectionFactory
    ) {
        $this->_entityCollectionFactory = $entityCollectionFactory;
        $this->_entityTypeCollectionFactory = $entityTypeCollectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $entityTypeItem = $this->_entityTypeCollectionFactory
            ->create()
            ->addFieldToFilter('entity_type_code', ['eq' => \Magento\Catalog\Model\Product::ENTITY])
            ->getFirstItem();

        $collection = $this->_entityCollectionFactory->create();
        $collection->addFieldToFilter('entity_type_id', ['eq' => $entityTypeItem->getEntityTypeId()]);

        $attributeSets = [];

        foreach ($collection as $key => $item)
        {
            $attributeSets[] = [
                'label' => $item->getAttributeSetName(),
                'value' => $item->getAttributeSetId()
            ];
        }

        return $attributeSets;
    }
}
