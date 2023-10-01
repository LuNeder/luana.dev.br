---
title: Uutils Rules: Yeeting GNU Coreutils from my System
pdate: 2023-10-01
author: Luana Neder
layout: post
---

I've spent my Saturday completely replacing the coreutils on my openSUSE Tumbleweed system. After just a few hours, I can say that I've successfully replaced GNU coreutils with [uutils](https://uutils.github.io/) and everything went surprisingly smoothly!

Everything just worked after I installed uutils and deleted coreutils, and I mean it. Most of this comes from uutils goal: full parity with the GNU coreutils.

This blog post aims to be both an interesting story and a tutorial of some kind for those who want to do the same 
 

# Rage Against the GNU: Why?
Maybe you're a Rust fanatic that just want to use anything that is coded on it, or maybe you prefer the multicall binary approach or even the infinitely more permissive license uutils use, but the fact is there are many reasons for me to want to decrease my dependence on GNU software. GNU and the FSF have become (or always were) a show of horrors of arrogant people and entitlement from its board, devs and fans, going as far as [attacking whoever has a different opinion about licensing](https://github.com/uutils/coreutils/issues/1781) or is making an alternative to their software, and deliberately ignoring the existence of non-GNU open source software. I'm also pretty sure I remember reading somewhere that GNU was being secretive about documentation for some parts of glibc on an attempt to stop projects like [musl](https://musl.libc.org/).
I have therefore been trying to heavily reduce my use of GNU and, when possible, GPL software.

# Replacing the Core: How?