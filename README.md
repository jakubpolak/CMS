# CMS

Built with Symfony 3 and PHP 7.

# Development Guide

## Pagination

**Controller**

```
public function indexAction(int $page): array {
    $resultsPerPage = $this->get('service_container')->getParameter('results_per_page');
    $filter = $this->get('app.service.filter');

    return [
        'articles' => $filter->getPagination($page - 1, $resultsPerPage, Article::class),
        'pagesCount' => $filter->getPagesCount(Article::class, $resultsPerPage),
        'currentPage' => $page - 1
    ];
}
```

**View**

```
{% include 'AppBundle:admin/layout:pagination.html.twig' with {'pagesCount': pagesCount, 'currentPage': currentPage, 'route': 'admin_article_index'} %}
```





