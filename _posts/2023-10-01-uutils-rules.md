---
title: Uutils Rules: Yeeting GNU Coreutils from my System
pdate: 2023-10-01
author: Luana Neder
layout: post
---

I've spent my Saturday completely replacing the coreutils on my openSUSE Tumbleweed system. After just a few hours, I can say that I've successfully replaced GNU coreutils with [uutils](https://uutils.github.io/) and everything went surprisingly smoothly!

Everything just worked after I installed uutils and deleted coreutils, and I mean it: NVidia drivers, audio, bluetooth, compiz, Steam and all programs I use worked out of the boxMost of this comes from uutils goal: full parity with the GNU coreutils.

This blog post aims to be both an interesting story and a tutorial of some kind for those who want to do the same, so bear with me for this weekend adventure.
 

# Rage Against the GNU: Why?
Maybe you're a Rust fanatic that just want to use anything that is coded on it, or maybe you prefer the multicall binary approach or even the infinitely more permissive license uutils use, but the fact is there are many reasons for me to want to decrease my dependence on GNU software. Getting into too much detail about this is beyond the scope of this post, but GNU and the FSF have become (or always were) a show of horrors of arrogant people and entitlement from its board, devs and fans, going as far as [attacking whoever has a different opinion about licensing](https://github.com/uutils/coreutils/issues/1781) or is making an alternative to their software, and deliberately ignoring the existence of non-GNU open source software and Linux distributions. I'm also pretty sure I remember reading somewhere that GNU was being secretive about documentation for some parts of glibc on an attempt to stop projects like [musl](https://musl.libc.org/). 
I have therefore been trying to heavily reduce my use of GNU and, when possible, GPL software.

# Replacing the Core: How?

Ready? Buckle up!

Note that this section is not intended to be blindly copy and pasted (never copy and paste random commands from the internet), since different distros will expect coreutils to be in different bin folders, shell completions and manpages to be installed on different places and have different package managers with different ways to do the stuff we'll have to do. It should be easy to adapt this to your own distrfo, but make sure to try it out on a virtual machine first to make sure it won't break anything on your distro.

First we need to clone [uutil's GitHub repository](https://github.com/uutils/coreutils/), making sure all tests on GitHub are passing. You may want to get the source from the [latest release](https://github.com/uutils/coreutils/releases/latest) instead in case the tests are not passing or if you want a safer alternative to the latest commits.

````sh
git clone https://github.com/uutils/coreutils && cd coreutils
````

Now we compile uutils with cargo. You'll need a c++ compiler in order to do this, I (unfortunetly) decided to use ```gcc-c++```  since it was the only one I knew without having to do much research. Just install it with zypper. After you've [installed Rust](https://www.rust-lang.org/learn/get-started):
````sh
cargo build --release --features unix
````

Copy uutils to your bin folder

````sh
sudo cp ./target/release/coreutils /usr/bin
````

Before we remove the GNU coreutils it's important that we create a snapshot



# Updating
Since we did not install it from a package manager, we'll have to manually update uutils from time to time.

Make a snapshot with snapper first, then update the cloned repository (making sure all checks are passing). 

Clean the releases with ```cargo clean``` and compile uutils with ```cargo build --release --features unix```, then we need to remove the current coreutils and add the new one on its place. Let's use our newly compiled binary for this:

````sh
sudo ./target/release/coreutils rm -f /usr/bin/coreutils
````

````sh
sudo ./target/release/coreutils cp -f ./target/release/coreutils /usr/bin
````

Now you can reboot your system and make a new snapper snapshot, your system will be using the updated uutils.