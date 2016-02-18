<?php

namespace AppBundle\Twig;

/**
 * Translation helper extension.
 *
 * @author Jakub Polák, Jana Poláková
 */
class TranslationHelperExtension extends \Twig_Extension {
    /**
     * Get functions.
     *
     * @return array
     */
    public function getFunctions(): array {
        return [];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(): string {
        return 'translationHelper_extension';
    }
}
