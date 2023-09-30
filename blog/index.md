---
title: Blog
collection_src: posts
layout: blog
---


<ul>
  {% for post in site.posts %}
    <li>
      <a href="{{ post.url }}" class="btn">{{ post.title }} ({{ post.date }})</a>
    </li>
  {% endfor %}
</ul>
