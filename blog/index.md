---
title: Blog
collection_src: posts
layout: blog
---

# {{ page.title }}

<ul>
  {% for post in site.posts %}
    <li>
      <a href="{{ post.url }}">{{ post.title }}</a>
    </li>
  {% endfor %}
</ul>