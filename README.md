# CMS

Simple and customizable content management system for Wordpress like websites.

Built with Symfony 3 and PHP 7.

# Development Guide

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





