<?php

namespace AppBundle\Twig;

use AppBundle\Service\SettingsService;
use Doctrine\ORM\EntityManager;
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
     * @var EntityManager
     */
    private $em;

    /**
     * @var SettingsService
     */
    private $settingsService;

    /**
     * Constructor.
     *
     * @param Translator $translator
     * @param EntityManager $entityManager entity manager
     * @param SettingsService $settingsService settings service
     */
    public function __construct(
        Translator $translator,
        EntityManager $entityManager,
        SettingsService $settingsService
    ) {
        $this->translator = $translator;
        $this->em = $entityManager;
        $this->settingsService = $settingsService;
    }

    /**
     * Get functions.
     *
     * @return array
     */
    public function getFunctions() : array {
        return [
            new \Twig_SimpleFunction('bool', [$this, 'boolFunction'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('merge', [$this, 'mergeFunction'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('languagesCount', [$this, 'languagesCountFunction'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('showAdvancedMenu', [$this, 'showAdvancedMenuFunction'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get filters.
     *
     * @return array
     */
    public function getFilters() : array {
        return [
            new \Twig_SimpleFilter('substr', [$this, 'substrFilter']),
        ];
    }

    /**
     * Show advanced menu function.
     * 
     * @return bool true if advanced menu is shown, false otherwise.
     */
    public function showAdvancedMenuFunction() : bool {
        return $this->settingsService->getSettings()->getIsAdvancedMenuShown();
    }

    /**
     * Convert boolean value to it's string representation.
     *
     * @param bool $bool
     * @param string $domain
     * @return string
     */
    public function boolFunction(bool $bool, string $domain = null) : string {
        return $bool
            ? $this->translator->trans('áno', [], $domain)
            : $this->translator->trans('nie', [], $domain)
        ;
    }

    /**
     * Substring filter.
     *
     * @param string $string
     * @param int $start
     * @param int $length
     * @return string
     */
    public function substrFilter(string $string, int $start, int $length) : string {
        return substr($string, $start, $length);
    }

    /**
     * Merge values of specified entity attributes into a single string. Values are delimited by specified delimiter.
     *
     * @param array $values values to be merged into a string
     * @param string $delimiter delimiter to delimit values being merged into a string
     * @return string
     */
    public function mergeFunction(array $values, string $delimiter = ', ') : string {
        $result = '';

        foreach ($values as $key => $value) {
            $result .= $value;
            $result .= $delimiter;
        }

        return substr($result, 0, -2);
    }

    /**
     * Return count of languages.
     *
     * @return int
     */
    public function languagesCountFunction(): int {
        return $this->em->getRepository('AppBundle:Language')->getCount();
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(): string {
        return 'templateHelper_extension';
    }
}
