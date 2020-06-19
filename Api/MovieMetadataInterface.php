<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Peteleco\Movie\Api;

/**
 * Interface for retrieval information about customer attributes metadata.
 * @api
 * @since 100.0.2
 */
interface MovieMetadataInterface extends MetadataInterface
{
    const ATTRIBUTE_SET_ID_CUSTOMER = 1;

    const ENTITY_TYPE_MOVIE = 'movie';

    const DATA_INTERFACE_NAME = \Peteleco\Movie\Api\Data\MovieInterface::class;
}
