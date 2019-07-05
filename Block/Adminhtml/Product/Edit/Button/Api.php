<?php
namespace Sas\Movies\Block\Adminhtml\Product\Edit\Button;

class Api extends \Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic
{
    public function getButtonData()
    {
        if ($this->getProduct()->getTypeId() !== 'movie' || $this->getProduct()->getId()) {
            return [];
        }

        return [
            'label' => __('Import from API'),
            'class' => 'action-secondary',
            'data_attribute' => [
                'mage-init' => [
                    'Magento_Ui/js/form/button-adapter' => [
                        'actions' => [
                            [
                                'targetName' => 'product_form.product_form.import_from_api_modal',
                                'actionName' => 'toggleModal'
                            ],
                            [
                                'targetName' => 'product_form.product_form.import_from_api_modal.movies_grid',
                                'actionName' => 'render'
                            ]
                        ]
                    ]
                ]
            ],
            'on_click' => '',
            'sort_order' => 10
        ];
    }
}
