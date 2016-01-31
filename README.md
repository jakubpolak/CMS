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





