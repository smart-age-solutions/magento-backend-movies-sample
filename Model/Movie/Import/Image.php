<?php
namespace Sas\Movies\Model\Movie\Import;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Product as ProductResourse;
use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Magento\Catalog\Model\ResourceModel\Product\Gallery;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Filesystem\Io\File;
use Magento\Store\Model\StoreManagerInterface;

class Image
{
    /**
     * Url prefix for image download
     */
    const URL_PREFIX = 'https://image.tmdb.org/t/p/w400/';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var ProductCollectionFactory
     */
    private $productCollectionFactory;

    /**
     * @var Product
     */
    private $product;

    /**
     * @var ProductResourse
     */
    private $productResource;

    /**
     * @var Gallery
     */
    private $gallery;

    /**
     * @var ProductAttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var \Magento\Catalog\Api\Data\ProductAttributeInterface
     */
    protected $attribute;

    /**
     * @var \Magento\Framework\EntityManager\EntityMetadata
     */
    protected $metadata;

    /**
     * @var array
     */
    private $attributeCodes = ['image', 'small_image', 'thumbnail'];
    /**
     * @var DirectoryList
     */
    private $directoryList;
    /**
     * @var File
     */
    private $file;

    public function __construct(
        MetadataPool $metadataPool,
        DirectoryList $directoryList,
        File $file,
        StoreManagerInterface $storeManager,
        ProductCollectionFactory $productCollectionFactory,
        ProductResourse $productResource,
        Gallery $gallery,
        ProductAttributeRepositoryInterface $attributeRepository
    ) {
        $this->metadata = $metadataPool->getMetadata(ProductInterface::class);
        $this->storeManager = $storeManager;
        $this->productCollectionFactory = $productCollectionFactory;
        $this->attributeRepository = $attributeRepository;
        $this->productResource = $productResource;
        $this->gallery = $gallery;
        $this->directoryList = $directoryList;
        $this->file = $file;
    }

    public function import(\Magento\Catalog\Model\Product $product, string $fileName): bool
    {
        $this->product = $product;

        $imageList = [$fileName];

        $newImages = $this->getImagesNotInDatabase($imageList);

        return $this->saveGallery($newImages);
    }

    private function getImagesNotInDatabase(array $imageList)
    {
        $connection = $this->gallery->getConnection();
        $linkField = $this->metadata->getLinkField();

        $select = $this->gallery
            ->createBatchBaseSelect(0, $this->getAttribute()->getAttributeId())
            ->where(
                $connection->quoteInto('value IN (?)', array_map([$this, 'getFinalFileName'], $imageList))
                . ' AND ' .
                $connection->quoteInto('entity.' . $linkField . ' = ?', $this->product->getData($linkField))
            );

        $imagesInDatabase = $connection->fetchPairs($select);

        return array_filter($imageList, function ($imageFileName) use ($imagesInDatabase) {
            return !in_array($this->getFinalFileName($imageFileName), $imagesInDatabase);
        });
    }

    private function saveGallery(array $imageList): bool
    {
        $attrCode = $this->getAttribute()->getAttributeCode();

        $mediaDir = $this->getMediaDir();
        $this->file->checkAndCreateFolder($mediaDir);

        foreach ($imageList as $fileName) {
            if (!strlen($fileName)) {
                continue;
            }

            $mediaGalleryData = $this->product->getData($attrCode);
            $position = 0;
            if (!is_array($mediaGalleryData)) {
                $mediaGalleryData = ['images' => []];
            }

            foreach ($mediaGalleryData['images'] as &$image) {
                if (isset($image['position']) && $image['position'] > $position) {
                    $position = $image['position'];
                }
            }

            $newFileName = $mediaDir . DIRECTORY_SEPARATOR . baseName($fileName);
            $result = $this->file->read(self::URL_PREFIX . $fileName, $newFileName);

            if ($result) {
                $position++;
                $mediaGalleryData['images'][] = [
                    'file' => $this->getFinalFileName($fileName),
                    'position' => $position,
                    'label' => '',
                    'disabled' => 0,
                ];

                $this->product->setData($attrCode, $mediaGalleryData);

                $this->processNewAndExistingImages($mediaGalleryData['images']);
            }

        }

        $this->setMainImage($imageList);

        try {
            foreach ($this->attributeCodes as $attributeCode) {
                $this->productResource->saveAttribute($this->product, $attributeCode);
            }
            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }

    protected function processNewAndExistingImages(array $images): void
    {
        foreach ($images as &$image) {
            if (empty($image['removed'])) {
                $data = $this->processNewImage($image);

                // Add per store labels, position, disabled
                $data['value_id'] = $image['value_id'];
                $data['label'] = isset($image['label']) ? $image['label'] : '';
                $data['position'] = isset($image['position']) ? (int)$image['position'] : 0;
                $data['disabled'] = isset($image['disabled']) ? (int)$image['disabled'] : 0;
                $data['store_id'] = (int)$this->product->getStoreId();

                $data[$this->metadata->getLinkField()] = (int)$this->product->getData($this->metadata->getLinkField());

                $this->gallery->insertGalleryValueInStore($data);
            }
        }
    }

    protected function processNewImage(array &$image): array
    {
        $data = [];

        $data['value'] = $image['file'];
        $data['attribute_id'] = $this->getAttribute()->getAttributeId();

        if (!empty($image['media_type'])) {
            $data['media_type'] = $image['media_type'];
        }

        $image['value_id'] = $this->gallery->insertGallery($data);

        $this->gallery->bindValueToEntity(
            $image['value_id'],
            $this->product->getData($this->metadata->getLinkField())
        );

        return $data;
    }

    private function setMainImage(array $imageList = null): bool
    {
        if (!count($imageList)) {
            return false;
        }

        $firstImage = reset($imageList);

        return $this->setMediaAttributeValues($this->getFinalFileName($firstImage));
    }

    private function setMediaAttributeValues(string $filePath): bool
    {
        foreach ($this->attributeCodes as $mediaAttribute) {
            if ($this->product->getData($mediaAttribute) !== $filePath) {
                $this->product->setData($mediaAttribute, $filePath);
            }
        }

        return true;
    }

    private function getAttribute(): \Magento\Catalog\Api\Data\ProductAttributeInterface
    {
        if (!$this->attribute) {
            $this->attribute = $this->attributeRepository->get('media_gallery');
        }

        return $this->attribute;
    }

    protected function getMediaDir()
    {
        $arrayPath = [
            $this->directoryList->getPath(DirectoryList::MEDIA),
            'catalog',
            'product',
            'movie',
        ];

        return implode(DIRECTORY_SEPARATOR, $arrayPath);
    }

    private function getFinalFileName(string $fileName): string
    {
        return '/movie/' . baseName($fileName);
    }
}
