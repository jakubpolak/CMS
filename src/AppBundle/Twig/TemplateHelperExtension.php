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
     * Merge values of specified entity attributes into a single string. Values are delimited by specified delimiter.
     *
     * @param array $entities entities
     * @param string $attributeName attribute name
     * @param string $delimiter delimiter
     * @return string
     */
    public function mergeFunction(array $entities, string $attributeName, string $delimiter = ', '): string {
        $result = '';
        $methodName = 'get' . ucfirst($attributeName);

        foreach ($entities as $entity) {
            $result .= $entity->{$methodName}();
            $result .= $delimiter;
        }

        return substr($result, 0, strlen($result) - 2);
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