<?php
/**
 * @copyright 2019 Marcus Pettersen Irgens
 * @license MIT
 */

declare(strict_types=1);

namespace Marcuspi\ProductUrlKeyGeneration\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;

/**
 * Class Config
 */
class Config extends AbstractHelper
{
    public const SLUGIFY_LANGUAGE_XML_PATH = "catalog/seo/slug_language";
    public const SLUGIFY_LANGUAGE_USE_STORE = "use_store";

    /**
     * @param string $scopeType
     * @param string|null $scopeCode
     * @return mixed
     */
    public function getSlugifyLanguage(
        string $scopeType = ScopeConfigInterface::SCOPE_TYPE_DEFAULT,
        ?string $scopeCode = null
    ) {
        $lang = $this->scopeConfig->getValue(self::SLUGIFY_LANGUAGE_XML_PATH, $scopeType, $scopeCode);

        if (($lang === null) || ($lang === null)) {
            return self::SLUGIFY_LANGUAGE_USE_STORE;
        }

        return $lang;
    }
}
