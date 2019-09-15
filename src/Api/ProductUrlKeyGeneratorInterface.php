<?php
/**
 * @copyright 2019 Marcus Pettersen Irgens
 * @license MIT
 */

declare(strict_types=1);

namespace Marcuspi\ProductUrlKeyGeneration\Api;

use Magento\Catalog\Api\Data\ProductInterface;

/**
 * Classes implementing ProductUrlKeyGeneratorInterface can generate URL keys
 * for products
 */
interface ProductUrlKeyGeneratorInterface
{
    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return string
     */
    public function generateUrlKey(ProductInterface $product): string;
}
