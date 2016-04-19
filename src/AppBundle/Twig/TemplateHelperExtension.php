<?php

namespace AppBundle\Twig;

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
     * Constructor.
     *
     * @param Translator $translator
     * @param EntityManager $entityManager entity manager
     */
    public function __construct(
        Translator $translator,
        EntityManager $entityManager
    ) {
        $this->translator = $translator;
        $this->em = $entityManager;
    }

    /**
     * Get functions.
     *
     * @return array
     */
    public function getFunctions(): array {
        return [
            new \Twig_SimpleFunction('bool', [$this, 'boolFunction'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('merge', [$this, 'mergeFunction'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('languagesCount', [$this, 'languagesCountFunction'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('isArray', [$this, 'isArrayFunction'], ['is_safe' => ['html']]),
        ];
    }

    /**
     * Get filters.
     *
     * @return array
     */
    public function getFilters(): array {
        return [
            new \Twig_SimpleFilter('substr', [$this, 'substrFilter']),
        ];
    }

    /**
     * Decide if input is array or not.
     *
     * @param mixed $input
     * @return bool
     */
    public function isArrayFunction($input): bool {
        return is_array($input);
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
     * Substring filter.
     *
     * @param string $string
     * @param int $start
     * @param int $length
     * @return string
     */
    public function substrFilter(string $string, int $start, int $length): string {
        return substr($string, $start, $length);
    }

    /**
     * Merge values of specified entity attributes into a single string. Values are delimited by specified delimiter.
     *
     * @param string $delimiter delimiter
     * @return string
     */
    public function mergeFunction(array $values, string $delimiter = ', '): string {
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
