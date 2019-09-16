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

        if ($product === null) {
            $proceed($observer);
            return;
        }

        if (!$this->hasUrlKey($product)) {
            $slug = $this->productUrlKeyGenerator->generateUrlKey($product);

            $product->setCustomAttribute("url_key", $slug);
            return;
        }

        $proceed($observer);
    }

    private function hasUrlKey(\Magento\Catalog\Api\Data\ProductInterface $product)
    {
        if ($product->getCustomAttribute("url_key") === null) {
            return false;
        }

        $value = $product->getCustomAttribute("url_key")->getValue();
        if (null === $value) {
            return true;
        }

        if (is_string($value)) {
            return strlen(trim($value)) === 0;
        }

        return true;
    }
}
