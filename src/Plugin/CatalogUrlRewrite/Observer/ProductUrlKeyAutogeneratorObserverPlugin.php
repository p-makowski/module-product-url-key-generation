<?php
/**
 * @copyright 2019 Marcus Pettersen Irgens
 * @license MIT
 */

declare(strict_types=1);

namespace Marcuspi\ProductUrlKeyGeneration\Plugin\CatalogUrlRewrite\Observer;

/**
 * Class ProductUrlKeyAutogeneratorObserverPlugin
 */
class ProductUrlKeyAutogeneratorObserverPlugin
{
    /**
     * @var \Marcuspi\ProductUrlKeyGeneration\Model\ProductUrlKeyGenerator
     */
    private $productUrlKeyGenerator;

    public function __construct(
        \Marcuspi\ProductUrlKeyGeneration\Model\ProductUrlKeyGenerator $productUrlKeyGenerator
    ) {
        $this->productUrlKeyGenerator = $productUrlKeyGenerator;
    }

    public function aroundExecute(
        \Magento\CatalogUrlRewrite\Observer\ProductUrlKeyAutogeneratorObserver $subject,
        callable $proceed,
        \Magento\Framework\Event\Observer $observer
    ): void {
        /** @var \Magento\Catalog\Api\Data\ProductInterface|null $product */
        $product = $observer->getData("product");
        $newKey = (null !== $product) && (null === $product->getData("url_key"));
        if ($newKey) {
            $slug = $this->productUrlKeyGenerator->generateUrlKey($product);

            $product->setData("url_key", $slug);
            return;
        }

        $proceed($observer);
    }
}
