---
author: Luana Neder
title: Blog
description: My blog
collection_src: posts
layout: blog
---


<ul>
  {% for post in site.posts %}
    <li>
      <a href="{{ post.url }}" <!-- class="btn"--> >{{ post.title }} ({{ post.pdate }})</a>
    </li>
  {% endfor %}
</ul>
