# CMS

Simple and customizable content management system for Wordpress like websites.

Released under GNU General Public License.

Built with Symfony 3 and PHP 7.

# License

Copyright (C) 2016  Jakub Polák, Jana Poláková

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see [http://www.gnu.org/licenses](http://www.gnu.org/licenses).


# Development Guide

## Translation

* In order to translate text loaded from database use `|trans({}, 'i18n')` filter.
* In order to translate text in administration, use `|trans({}, 'admin')` filter.
* Otherwise use `|trans` filter.

## Pagination

**Controller**

Change `Article::class` for your entity class.

```
/**
 * @Route("/{page}/articles", defaults={"page" = 1}, name="admin_article_index")
 * @Template("@App/admin/article/index.html.twig")
 * @Method("GET")
 */
public function indexAction(int $page): array {
    $resultsPerPage = $this->get('service_container')->getParameter('results_per_page');
    $filter = $this->get('app.service.filter');

    return [
        'articles' => $filter->getPagination(Article::class, $page, $resultsPerPage),
        'pagesCount' => $filter->getPagesCount(Article::class, $resultsPerPage),
        'currentPage' => $page
    ];
}
```

**View**

Change `admin_article_index` for you route name and include the following snippet in your view.

```
{% include 'AppBundle:admin/layout:pagination.html.twig' with {'pagesCount': pagesCount, 'currentPage': currentPage, 'route': 'admin_article_index'} %}
```

## Slugs

### Usage



### Enable for a new entity

In order to enable slugs for a new entity it is needed to update your Model, SlugService, Controller and it's view and admin's layout.html.twig.

#### Model

Update `Slug` entity with new `const` and `ManyToOne` relation to a new entity.

```
class Slug implements Entity {
    const MENU = 'Menu';
    const ARTICLE = 'Article';
    // ...
    const My_ENTITY = 'MyEntity';
    
    // ...
    
    /**
     * @var MyEntity
     *
     * @ORM\ManyToOne(targetEntity="MyEntity", inversedBy="slugs")
     * @ORM\JoinColumn(referencedColumnName="id", name="my_entity_id")
     */
    private $myEntity;
    
    // ...
    
    public function getMyEntity() : MyEntity {
        return $this->myEntity;
    }
    
    public function setMyEntity(MyEntity $myEntity) : self {
        $this->myEntity = $myEntity;
        return $this;
    }
    
    // ...
}
```

Update your entity with:

```
class MyEntity implements Entity {
    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Slug", mappedBy="article")
     */
    private $slugs;

    // ...

    public function __construct() {
        $this->slugs = new ArrayCollection();
    }

    // ...
    
    public function getSlugs() : Collection {
        return $this->slugs;
    }

    public function setSlugs(Collection $slugs) : self {
        $this->slugs = $slugs;

        return $this;
    }

    public function addSlug(Slug $slug) : self {
        $this->slugs->add($slug);
        $slug->setArticle($this);

        return $this;
    }

    public function removeSlug(Slug $slug) : self {
        $this->slugs->remove($slug);

        return $this;
    }
    
    // ...
}
```

#### SlugService

Update `SlugService#getByEntityAndLocale(Entity $entity, string $locale)` method.

Update `SlugService` attribute `slugTypes`.


#### Controller and it's view

Update your Controller with: 

```
class MyController extends Controller {
    // ...
    
    public function update(MyEntity $myEntity) {
        // ...
    
        return [
            // ...
            'myEntity' => $myEntity,
            'slugs' => $myEntity->getSlugs(),
        ];
    }

    public function updateProcess(MyEntity $myEntity, Request $request) {
        // ...
    
        return [
            // ...
            'myEntity' => $myEntity,
            'slugs' => $myEntity->getSlugs(),
        ];
    }
    
    // ...
}
```

Replace `MyEntity` and `$myEntity` with name of your entity and yout entity variable:


Update update.html.twig with:

```
{% include '@App/admin/slug/list-of-slugs.html.twig' with {'entityName' : 'myEntityName', 'entityId' : myEntity.id, 'slugs' : slugs} %}

    {% include '@App/admin/slug/new-slug.html.twig' with {'entityName' : 'myEntityName', 'entityId' : myEntity.id } %}
```

Replace `myEntityName` and `myEntity.id` with name of your entity and name of your entity variable passed to view in your controller's update and updateProcess actions.

#### admin's layout.html.twig

In order to display menu as active add `or entityName == 'myEntity'` to `if` condition: 

```
<li>
    <a {% if '...' in currentRoute or entityName == 'myEntity' %} class="active"{% endif %} href="..."><!-- ... --></a>
</li>
```




