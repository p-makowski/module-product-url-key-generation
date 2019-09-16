<?php
/**
 * @copyright 2019 Marcus Pettersen Irgens
 * @license MIT
 */

declare(strict_types=1);

namespace Marcuspi\ProductUrlKeyGeneration\Model;

use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyFactory;
use Marcuspi\ProductUrlKeyGeneration\Api\SlugifierInterface;

/**
 * Class Slugifier
 */
class Slugifier implements SlugifierInterface
{
    const ENGLISH_LOCALES = ['en_US', 'en_IE', 'en_NZ', 'en_GB', 'en_AU', 'en_CA'];

    /**
     * @var \Cocur\Slugify\SlugifyFactory
     */
    private $slugifyFactory;

    /**
     * @var Slugify|null
     */
    private $slugify;

    /**
     * @var \Marcuspi\ProductUrlKeyGeneration\Helper\Config
     */
    private $configHelper;

    /**
     * @var \Magento\Framework\Locale\ListsInterface
     */
    private $localeList;

    /**
     * @var \Magento\Framework\Locale\Resolver
     */
    private $localeResolver;

    public function __construct(
        SlugifyFactory $slugifyFactory,
        \Marcuspi\ProductUrlKeyGeneration\Helper\Config $configHelper,
        \Magento\Framework\Locale\ListsInterface $localeList,
        \Magento\Framework\Locale\Resolver $localeResolver
    ) {
        $this->slugifyFactory = $slugifyFactory;
        $this->configHelper = $configHelper;
        $this->localeList = $localeList;
        $this->localeResolver = $localeResolver;
    }

    /**
     * @return \Cocur\Slugify\Slugify
     */
    private function getSlugifier(): Slugify
    {
        if (null === $this->slugify) {
            $this->createSlugifier();
        }

        return $this->slugify;
    }

    /**
     * @param string $string
     * @return string
     */
    public function slugify(string $string): string
    {
        return $this->getSlugifier()->slugify($string);
    }

    /**
     *
     */
    private function createSlugifier()
    {
        $this->slugify = $this->slugifyFactory->create([
            "options" => [
                "rulesets" => $this->getRulesets()
            ]
        ]);
    }

    /**
     * @return array
     */
    private function getRulesets()
    {
        $lang = $this->configHelper->getSlugifyLanguage();

        if ($lang == $this->configHelper::SLUGIFY_LANGUAGE_USE_STORE) {
            $lang = $this->localeResolver->getLocale();
        }

        $ruleset = $this->getSlugifyRulesetForLocale($lang);

        return $ruleset;
    }

    private function getSlugifyRulesetForLocale($lang): array
    {
        $map = [
            "ar_DZ" => "arabic",
            "ar_EG" => "arabic",
            "ar_KW" => "arabic",
            "ar_MA" => "arabic",
            "ar_SA" => "arabic",
            "hy_AM" => "armenian",
            "az_Latn_AZ" => "azerbaijani",
            "bg_BG" => "bulgarian",
            "my_MM" => "burmese",
            "zh_Hant_HK" => "chinese",
            "zh_Hans_CN" => "chinese",
            "zh_Hant_TW" => "chinese",
            "hr_HR" => "croatian",
            "cs_CZ" => "czech",
            "da_DK" => "danish",
            "eo" => "esperanto",
            "et_EE" => "estonian",
            "fi_FI" => "finnish",
            "fr_BE" => "french",
            "fr_CA" => "french",
            "fr_FR" => "french",
            "fr_LU" => "french",
            "fr_CH" => "french",
            "ka_GE" => "georgian",
            "de_LU" => "german",
            "de_CH" => "german",
            "de_DE" => "german",
            "de_AT" => "austrian",
            "el_GR" => "greek",
            "hi_IN" => "hindi",
            "hu_HU" => "hungarian",
            "it_IT" => "italian",
            "it_CH" => "italian",
            "lv_LV" => "latvian",
            "lt_LT" => "lithuanian",
            "mk_MK" => "macedonian",
            "nb_NO" => "norwegian",
            "nn_NO" => "norwegian",
            "fa_IR" => "persian",
            "pl_PL" => "polish",
            "pt_BR" => "portuguese-brazil",
            "pt_PT" => "portuguese-brazil",
            "ro_RO" => "romanian",
            "ru_RU" => "russian",
            "sr_Cyrl_RS" => "serbian",
            "sv_FI" => "swedish",
            "sv_SE" => "swedish",
            "tr_TR" => "turkish",
            "tk_TM" => "turkmen",
            "uk_UA" => "ukrainian",
            "vi_VN" => "vietnamese",
        ];

        if (array_key_exists($lang, $map)) {
            return ['default', $map[$lang]];
        }

        return ["default"];
    }
}
