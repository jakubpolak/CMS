<?php

namespace AppBundle\Twig;

use Symfony\Component\Translation\Translator;

/**
 * Template helper extension.
 *
 * @author Jakub Polák
 */
class TemplateHelperExtension extends \Twig_Extension {
    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var string
     */
    private $translatorDomain;

    /**
     * Constructor.
     *
     * @param Translator $translator
     * @param string $translatorDomain
     */
    public function __construct(Translator $translator, string $translatorDomain = null) {
        $this->translator = $translator;
        $this->translatorDomain = $translatorDomain;
    }

    /**
     * Get functions.
     *
     * @return array
     */
    public function getFunctions(): array {
        return [
            new \Twig_SimpleFunction('bool', [$this, 'boolFunction'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Convert boolean value to it's string representation.
     *
     * @param bool $bool
     * @param string $domain
     * @return string
     */
    public function boolFunction(bool $bool, string $domain = null): string {
        return $bool
            ? $this->translator->trans('áno', [], $domain)
            : $this->translator->trans('nie', [], $domain)
        ;
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'templateHelper_extension';
    }
}