<?php
/**
 * @copyright 2019 Marcus Pettersen Irgens
 * @license MIT
 */

declare(strict_types=1);

namespace Marcuspi\ProductUrlKeyGeneration\Model;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilderFactory;
use Marcuspi\ProductUrlKeyGeneration\Api\ProductUrlKeyGeneratorInterface;
use Marcuspi\ProductUrlKeyGeneration\Api\SlugifierInterface;

/**
 * Class ProductUrlKeyGenerator
 */
class ProductUrlKeyGenerator implements ProductUrlKeyGeneratorInterface
{
    /**
     * @var \Marcuspi\ProductUrlKeyGeneration\Api\SlugifierInterface
     */
    private $slugifier;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory
     */
    private $urlRewriteCollectionFactory;
    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilderFactory
     */
    private $searchCriteriaBuilderFactory;

    public function __construct(
        SlugifierInterface $slugifier,
        ProductRepositoryInterface $productRepository,
        \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollectionFactory $urlRewriteCollectionFactory,
        SearchCriteriaBuilderFactory $searchCriteriaBuilderFactory
    ) {
        $this->slugifier = $slugifier;
        $this->productRepository = $productRepository;
        $this->urlRewriteCollectionFactory = $urlRewriteCollectionFactory;
        $this->searchCriteriaBuilderFactory = $searchCriteriaBuilderFactory;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return string
     */
    public function generateUrlKey(ProductInterface $product): string
    {
        $rounds = 0;
        do {
            $slug = $this->slugifier->slugify($this->getSlugBase($product, $rounds++));
        } while ($this->slugTaken($slug, $product));

        return $slug;
    }

    /**
     * @param string $slug
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return bool
     */
    private function slugTaken(string $slug, ProductInterface $product)
    {
        $criteria = $this->searchCriteriaBuilderFactory->create()
            ->addFilter(ProductAttributeInterface::CODE_SKU, $product->getSku(), "neq")
            ->addFilter(ProductAttributeInterface::CODE_SEO_FIELD_URL_KEY, $slug, "eq")
            ->create();
        if ($this->productRepository->getList($criteria)->getTotalCount() > 0) {
            return true;
        }

        /** @var \Magento\UrlRewrite\Model\ResourceModel\UrlRewriteCollection $collection */
        $collection = $this->urlRewriteCollectionFactory->create();
        // Could not find any constants for this...
        $select = $collection->getSelect();
        $select->where("request_path LIKE :path");
        $select->orWhere("request_path = :pathFull");
        if ($product->getId() !== null) {
            $select->where("entity_id != :entityId");
            $collection->addBindParam("entityId", $product->getId());
        }
        $collection->addFilter("entity_type", "product");
        $collection->addBindParam("path", sprintf("%%/%s", $slug));
        $collection->addBindParam("pathFull", $slug);
        if ($collection->count() > 0) {
            return true;
        }

        return false;
    }

    /**
     * @param \Magento\Catalog\Api\Data\ProductInterface $product
     * @return string|null
     */
    private function getSlugBase(ProductInterface $product, int $rounds)
    {
        $base = $product->getName() ?? $product->getSku();

        if ($rounds > 0) {
            $base = sprintf("%s %d", $base, $rounds);
        }

        return $base;
    }
}
