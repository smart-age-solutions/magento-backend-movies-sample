<?php
namespace Sas\Movies\Ui\DataProvider\Product\Form\Modifier;

use Magento\Framework\UrlInterface;
use Magento\Ui\Component;

class ApiModal extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;


    public function __construct(
        UrlInterface $urlBuilder
    ) {
        $this->urlBuilder = $urlBuilder;
    }

    public function modifyMeta(array $meta)
    {
        $meta = $this->customizeMoviesModal($meta);
        $meta = $this->customizeMoviesGrid($meta);

        return $meta;
    }

    private function customizeMoviesModal(array $meta)
    {
        $meta['import_from_api_modal']['arguments']['data']['config'] = [
            'isTemplate' => false,
            'componentType' => Component\Modal::NAME,
            'dataScope' => '',
            'provider' => 'product_form.product_form_data_source',
            'imports' => [
                'state' => '!index=movies_add_form:responseStatus'
            ],
            'options' => [
                'title' => __('Import Movie From API'),
                'buttons' => [
                    [
                        'text' => 'Cancel',
                        'actions' => [
                            [
                                'targetName' => '${ $.name }',
                                'actionName' => 'actionCancel'
                            ]
                        ]
                    ],
                ],
            ],
        ];

        return $meta;
    }

    private function customizeMoviesGrid(array $meta)
    {
        $meta['import_from_api_modal']['children']['movies_grid'] = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'component' => 'Sas_Movies/js/components/movies-insert-listing',
                        'componentType' => Component\Container::NAME,
                        'autoRender' => false,
                        'dataScope' => 'movies_grid',
                        'externalProvider' => 'movies_grid.movies_grid_data_source',
                        'selectionsProvider' => '${ $.ns }.${ $.ns }.movies_columns.ids',
                        'ns' => 'movies_grid',
                        'render_url' => $this->urlBuilder->getUrl('mui/index/render'),
                        'immediateUpdateBySelection' => true,
                        'behaviourType' => 'edit',
                        'externalFilterMode' => true,
                        'dataLinks' => ['imports' => false, 'exports' => true],
                        'formProvider' => 'ns = ${ $.namespace }, index = product_form',
                        'loading' => false,
                    ],
                ],
            ]
        ];
        return $meta;
    }

    public function modifyData(array $data)
    {
        return $data;
    }
}
