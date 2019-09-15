<?php
/**
 * @copyright 2019 Marcus Pettersen Irgens
 * @license MIT
 */

declare(strict_types=1);

namespace Marcuspi\ProductUrlKeyGeneration\Api;

/**
 * A slugifier can take a string and output a slugified version of said string
 */
interface SlugifierInterface
{
    /**
     * @param string $string
     * @return string
     */
    public function slugify(string $string): string;
}
