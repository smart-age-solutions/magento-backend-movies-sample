/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Ui/js/lib/view/utils/async',
    'uiRegistry',
    'underscore',
    'Magento_Ui/js/form/components/insert-listing'
], function ($, registry, _, InsertListing) {
    'use strict';

    return InsertListing.extend({
        defaults: {
            formProvider: '',
            modules: {
                form: '${ $.formProvider }',
                modal: '${ $.parentName }'
            },
            productType: ''
        },

        /**
         * Render attribute
         */
        render: function () {
            this._super();
        },
    });
});
