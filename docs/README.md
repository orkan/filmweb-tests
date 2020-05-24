# filmweb-tests
PHPUnit tests for filmweb-api

## GitHub Pages 
Your site is ready to be published at https://orkan.github.io/filmweb-tests/. 

### List of posts
Permalink: https://jekyllrb.com/docs/posts/#displaying-an-index-of-posts

<ul>
  {% for post in site.posts %}
    <li>
      <a href="{{ post.url }}">{{ post.title }}</a>
    </li>
  {% endfor %}
</ul>

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
