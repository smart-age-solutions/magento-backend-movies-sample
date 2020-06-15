<?php

namespace Lima\Movie\Service;

use Magento\Catalog\Model\Product;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;

/**
 * Class ImportImageService
 * @package Lima\Movie\Service
 */
class ImportImageService
{
    /**
     * @var DirectoryList
     */
	protected $directoryList;

    /**
     * @var File
     */
	protected $file;

    /**
     * ImportImageService constructor.
     * @param DirectoryList $directoryList
     * @param File $file
     */
	public function __construct(
		DirectoryList $directoryList,
		File $file
	) {
		$this->directoryList = $directoryList;
		$this->file = $file;
	}

    /**
     * @param $product
     * @param $imageUrl
     * @param bool $visible
     * @param array $imageType
     * @return bool|string
     * @throws \Exception
     */
	public function execute($product, $imageUrl, $visible = false, $imageType = [])
	{
		$tmpDir = $this->getMediaDirTmpDir();
		$this->file->checkAndCreateFolder($tmpDir);
		$newFileName = $tmpDir . baseName($imageUrl);
		$result = $this->file->read($imageUrl, $newFileName);
		if ($result) {
			$product->addImageToMediaGallery($newFileName, $imageType, true, $visible);
		}

		return $result;
	}

    /**
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     */
	protected function getMediaDirTmpDir()
	{
		return $this->directoryList->getPath(DirectoryList::MEDIA) . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR;
	}
}
