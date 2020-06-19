<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Peteleco\Movie\Api;

use Magento\Framework\Api\MetadataServiceInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Peteleco\Movie\Api\Data\AttributeMetadataInterface;

/**
 * Interface for getting attributes metadata. Note that this interface should not be used directly, use its children.
 * @api
 * @since 100.0.2
 */
interface MetadataInterface extends MetadataServiceInterface
{
    /**
     * Retrieve all attributes filtered by form code
     *
     * @param string $formCode
     * @return AttributeMetadataInterface[]
     * @throws LocalizedException
     */
    public function getAttributes($formCode);

    /**
     * Retrieve attribute metadata.
     *
     * @param string $attributeCode
     * @return AttributeMetadataInterface
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function getAttributeMetadata($attributeCode);

    /**
     * Get all attribute metadata.
     *
     * @return AttributeMetadataInterface[]
     * @throws LocalizedException
     */
    public function getAllAttributesMetadata();

    /**
     *  Get custom attributes metadata for the given data interface.
     *
     * @param string $dataInterfaceName
     * @return AttributeMetadataInterface[]
     * @throws LocalizedException
     */
    public function getCustomAttributesMetadata($dataInterfaceName = '');
}
