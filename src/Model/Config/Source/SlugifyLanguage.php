<?php
/**
 * @copyright 2019 Marcus Pettersen Irgens
 * @license MIT
 */

declare(strict_types=1);

namespace Marcuspi\ProductUrlKeyGeneration\Model\Config\Source;

/**
 * Class SlugifyLanguage
 */
class SlugifyLanguage implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Magento\Framework\Locale\ListsInterface
     */
    private $localeList;

    public function __construct(
        \Magento\Framework\Locale\ListsInterface $localeList
    )
    {
        $this->localeList = $localeList;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        $options = [];
        $options[] = [
            "value" => "use_store",
            "label" => __("Use Store Language")
        ];

        $locales = $this->localeList->getOptionLocales();
        return array_merge($options, $locales);
    }
}
