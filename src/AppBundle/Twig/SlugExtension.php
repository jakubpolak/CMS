<?php

namespace AppBundle\Twig;


use AppBundle\Entity\Entity;
use AppBundle\Helper\ReflectionHelper;
use AppBundle\Service\Exception\ServiceException;
use AppBundle\Service\LanguageService;
use AppBundle\Service\SlugService;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Slug extension.
 * 
 * @author Jakub Polák, Jana Poláková
 */
class SlugExtension extends \Twig_Extension {
    /**
     * @var SlugService
     */
    private $slugService;

    /**
     * @var LanguageService
     */
    private $languageService;

    /**
     * Constructor.
     *
     * @param SlugService $slugService slug service
     * @param LanguageService $languageService language service
     */
    public function __construct(SlugService $slugService, LanguageService $languageService) {
        $this->slugService = $slugService;
        $this->languageService = $languageService;
    }

    /**
     * Get functions.
     * 
     * @return array     
     */
    public function getFunctions() : array {
        return [
            new \Twig_SimpleFunction(
                'slugOrId',
                [$this, 'getSlugContentOrEntityIdFunction'],
                ['is_safe' => ['html']]
            ),
        ];
    }
    
    /**
     * Get slug content or entity id.
     * 
     * @param Entity $entity entity 
     * @param string $locale locale
     * @return string|int
     */
    public function getSlugContentOrEntityIdFunction(Entity $entity, string $locale) {
        try {
            return $this->slugService->getByEntityAndLocale($entity, $locale)->getContent();
        } catch (NoResultException $e) {
            return ReflectionHelper::getClassName($entity) . '-' . $entity->getId();
        }
    }

    /**
     * Get name.
     * 
     * @return string
     */
    public function getName() : string {
        return 'slug_extension';
    }
}
