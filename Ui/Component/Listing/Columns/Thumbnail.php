<?php
namespace Sas\Movies\Ui\Component\Listing\Columns;

class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    const NAME = 'thumbnail';

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $item[$fieldName . '_src'] = $item['thumbnail'];
                $item[$fieldName . '_alt'] = $item['title'];
            }
        }

        return $dataSource;
    }
}
