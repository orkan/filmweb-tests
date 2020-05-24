# filmweb-tests
PHPUnit tests for filmweb-api

## GitHub Pages 
Your site is ready to be published at https://orkan.github.io/filmweb-tests/. 

### post_url's
* `{% post_url 2020-05-24-how-to-write-a-blog %}` Is available at: {% post_url 2020-05-24-how-to-write-a-blog %}
* `{% post_url /about/about %}` [The About page is here]({% post_url /about/about %})
* More info is at: https://jekyllrb.com/docs/liquid/tags/#linking-to-posts

### List of tags
Permalink: https://jekyllrb.com/docs/posts/#tags-and-categories

{% for tag in site.tags %}
  <h3>{{ tag[0] }}</h3>
  <ul>
    {% for post in tag[1] %}
      <li><a href="{{ post.url }}">{{ post.title }}</a></li>
    {% endfor %}
  </ul>
{% endfor %}

### Custom data file: _data/members.yml
Permalink: https://jekyllrb.com/docs/datafiles/#example-list-of-members

<ul>
{% for member in site.data.members %}
  <li>
    <a href="https://github.com/{{ member.github }}">
      {{ member.name }}
    </a>
  </li>
{% endfor %}
</ul>




## Basic usage of fimweb-api
```php
<?php
use Orkan\Filmweb\Filmweb;
use Orkan\Filmweb\Api\Method\isLoggedUser;

// Login to Filmweb
$filmweb = new Filmweb( $login, $password );
$api = $filmweb->getApi();

// Get user info
$api->call( 'isLoggedUser' );
$user = $api->getData();
$userId = $user[ isLoggedUser::USER_ID ];

// Get a list of voted films
$api->call( 'getUserFilmVotes', array( $userId ) );
$films = $api->getData();

// ...

print_r( $films );
```
