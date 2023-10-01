---
title: "Uutils Rules: Yeeting GNU Coreutils from my System"
pdate: 2023-10-01
author: Luana Neder
layout: post
---

I've spent my Saturday completely replacing the coreutils on my openSUSE Tumbleweed Linux system. After just a few hours, I can say that I've successfully replaced GNU coreutils with [uutils](https://uutils.github.io/) and everything went surprisingly smoothly!

Everything worked smoothly after I installed uutils and removed coreutils. I mean it: NVIDIA drivers, audio, Bluetooth, Compiz, Steam, and all the programs I use worked out of the box. Most of this success can be attributed to uutils' goal of achieving full parity with the GNU coreutils.

This blog post aims to be both an interesting story and a tutorial of some kind for those who want to do the same, so bear with me for this weekend adventure.
 

# Rage Against the GNU: Why?
Maybe you're a Rust fanatic that just want to use anything that is coded on it, or maybe you prefer the multicall binary approach or even the infinitely more permissive license uutils use, but the fact is there are many reasons for me to want to decrease my dependence on GNU software. Getting into too much detail about this is beyond the scope of this post, but GNU and the FSF have become (or always were) a show of horrors of arrogant people and entitlement from its board, devs and fans, going as far as [attacking whoever has a different opinion about licensing](https://github.com/uutils/coreutils/issues/1781) or is making an alternative to their software, and deliberately ignoring the existence of non-GNU open source software and Linux distributions. 
<!-- I'm also pretty sure I remember reading somewhere that GNU was being secretive about documentation for some parts of glibc on an attempt to stop projects like [musl](https://musl.libc.org/). -->
I have therefore been trying to heavily reduce my use of GNU and, when possible, GPL software.

# Replacing the Core: How?

Ready? Buckle up!

Note that this section is not intended to be blindly copy and pasted (never copy and paste random commands from the internet), since different distros will expect coreutils to be in different bin folders, shell completions and manpages to be installed on different places and have different package managers with different ways to do the stuff we'll have to do. It should be easy to adapt this to your own distro, but make sure to try it out on a virtual machine first to make sure it won't break anything you use. Remember that this has the potential to break your system and that you may follow these steps at your own risk!

First we need to clone [uutil's GitHub repository](https://github.com/uutils/coreutils/), making sure all tests on GitHub are passing. You may want to get the source from the [latest release](https://github.com/uutils/coreutils/releases/latest) instead in case the tests are not passing or if you want a safer alternative to the latest commits.

````sh
git clone https://github.com/uutils/coreutils && cd coreutils
````

Now we compile uutils with cargo. You'll need a c++ compiler in order to do this, I (unfortunately) decided to use ```gcc-c++```  since it was the only one I knew without having to do much research. Just install it with zypper. After you've [installed Rust](https://www.rust-lang.org/learn/get-started):
````sh
cargo build --release --features unix
````

Copy uutils to your bin folder

````sh
sudo cp ./target/release/coreutils /usr/bin
````

Before we remove the GNU coreutils it's important that we create a snapshot we can go back to if something goes wrong. Luckly, openSUSE Tumbleweed comes with snapper preconfigured so all we have to do is:

````sh
sudo snapper create --description 'before-uutils' --userdata important=yes -t pre
````

Now run ```sudo snapper list``` and write down the number of the snapshot you just created. The output should be something like this:
````
177  | pre    |       | sáb 30 set 2023 13:53:48 | root    |   656,00 KiB |         | before-uutils         | important=yes
````

So my number here is 177. Now I've decided to link our uutils before uninstalling gnu coreutils just so we don't use gnu coreutils to uninstall itself. The difference here is that we'll have to do this once again after uninstalling it since rpm will delete our links too thinking they're part of GNU.

In order to do this, I made a ```uutils-installer.sh``` script that does this automatically (this and all scripts on this post are available for download [on a GitHub repo](https://github.com/LuNeder/uutils-coreutils-replacer) so you don't have to make them yourself):

````sh
commandsuu="[ arch b2sum b3sum base32 base64 basename basenc cat chgrp chmod chown chroot cksum comm cp csplit cut date dd df dir dircolors dirname du echo env expand expr factor false fmt fold groups hashsum head hostid hostname id install join kill link ln logname ls md5sum mkdir mkfifo mknod mktemp more mv nice nl nohup nproc numfmt od paste pathchk pinky pr printenv printf ptx pwd readlink realpath relpath rm rmdir seq sha1sum sha224sum sha256sum sha3-224sum sha3-256sum sha3-384sum sha3-512sum sha384sum sha3sum sha512sum shake128sum shake256sum shred shuf sleep sort split stat stdbuf stty sum sync tac tail tee test timeout touch tr true truncate tsort tty uname unexpand uniq unlink uptime users vdir wc who whoami yes"

for i in $commandsuu; do
    coreutils rm -f /usr/bin/$i
    coreutils ln -s /usr/bin/coreutils /usr/bin/$i
done

echo done
````

I've run it with ```sudo sh ../uutils-coreutils-replacer/uutils-installer.sh``` and now we can finally yeet GNU Coreutils from your system with:

````sh
sudo rpm -e --allmatches --nodeps coreutils
````

Rpm might spit some command not found errors here, but you can ignore those since it just means it worked. Right now our system does not have any coreutils linked on the expected places, so it's super important that we run our  ```uutils-installer.sh``` script once again:

````sh
sudo sh ../uutils-coreutils-replacer/uutils-installer.sh
````

Right now you can reboot your system to make sure it's using our currently installed uutils and not trying to use what we just removed.

Now, our package manager (in my case zypper) will know that we have uninstalled gnu coreutils but won't know that we installed something to substitute it. It will try to reinstall coreutils every time you try to update or install something, so we need to install an empty metapackage with a big version number to stop zypper from doing this (just addlocking isn't enough right now because it'll still complain about missing dependencies). How to create that package yourself is beyond the scope of this post, but [the .rpm I made is available to download on the aforementioned GitHub repo](https://github.com/LuNeder/uutils-coreutils-replacer) together with the needed files to generate it yourself if so you wish. Install it with:

````sh
sudo zypper in ./coreutils-9999.99.99-0.noarch.rpm
````

Now we can addlock coreutils so zypper doesn't try to "update" our metapackage:
````sh
sudo zypper addlock coreutils
sudo zypper addlock coreutils-single
````

At this point we just need to compile the shell completions and manpages for uutils. I also made scripts for this, whick can be downloaded from the [GitHub repo for this post](https://github.com/LuNeder/uutils-coreutils-replacer):

```zsh-completions.sh```:
````sh
commandsuu="coreutils base32 base64 basename basenc cat cksum comm cp csplit cut date dd df dir dircolors dirname du echo env expand expr factor false fmt fold hashsum md5sum sha1sum sha224sum sha256sum sha384sum sha512sum sha3sum sha3-224sum sha3-256sum sha3-384sum sha3-512sum shake128sum shake256sum b2sum b3sum head join link ln ls mkdir mktemp more mv nl numfmt od paste pr printenv printf ptx pwd readlink realpath relpath rm rmdir seq shred shuf sleep sort split sum tac tail tee touch tr true truncate tsort unexpand uniq unlink test vdir wc yes"

for i in $commandsuu; do
    cargo run completion $i bash > /usr/share/bash-completion/completions/$i
    cargo run completion $i zsh > /usr/share/zsh/site-functions/_$i
done
```` 


```manpage.sh ```:
````sh
commandsuu="coreutils base32 base64 basename basenc cat cksum comm cp csplit cut date dd df dir dircolors dirname du echo env expand expr factor false fmt fold hashsum md5sum sha1sum sha224sum sha256sum sha384sum sha512sum sha3sum sha3-224sum sha3-256sum sha3-384sum sha3-512sum shake128sum shake256sum b2sum b3sum head join link ln ls mkdir mktemp more mv nl numfmt od paste pr printenv printf ptx pwd readlink realpath relpath rm rmdir seq shred shuf sleep sort split sum tac tail tee touch tr true truncate tsort unexpand uniq unlink test [ vdir wc yes"

for i in $commandsuu; do
    cargo run manpage $i > /usr/local/man/man1/$i.1
done
```` 

These scripts need to be run as root, but they use cargo. Make sure to ```su root``` and then [install Rust](https://www.rust-lang.org/learn/get-started) again on your root account and ```source "$HOME/.cargo/env"```. From there, you can run both scripts and you should be done:

````sh
../uutils-coreutils-replacer/zsh-completions.sh
````

````sh
../uutils-coreutils-replacer/manpage.sh
````

Now, make another snapper snapshot (substitute "[NUMBER]" with the snapshot number you wrote down earlier, in my case 177) :

````sh
sudo snapper create --description 'after-uutils' --userdata important=yes -t post --pre-number [NUMBER]
````

_(Here a fun fact is that I spent a few hours trying to fix an 'invisible lightdm' problem with folks on the uutils discord... just for me to find out that it was actually not a problem and I just forgot I had a second monitor connected and lightdm was showing up on it 🤦‍♀️)_

Once I rebooted, I proceeded to test stuff to make sure nothing had been broken: I launched Steam and Cyberpunk 2077 to make sure NVidia drivers, audio, proton, etc was all working, and indeed it was! I also tried out bluetooth, my compiz stuff, firefox and everything else I usually use and I couldn't find a single thing that was not working! Nice!

And you're done! Now you can proudly state that your system is not GNU whenever a GNU fanclubber comes in with their "GNU/Linux" copypasta.<span style="font-style:italic;font-size:10px">
    (well, technically there's still glibc but shhh that's a talk for another day - once Steam can work under [musl](https://musl.libc.org/))
</span>


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

# Conclusion
That's all for today, folks. I hope you liked our little weekend adventure, feel free to share your comments on [Mastodon]() if you'd like! 



Special thanks to uutils devs, folks on the uutils discord, openSUSE community, GELOS community and to openSUSE for creating the awesomest Linux distro (which hopefully can come with uutils by default some day now that we know everything works fine with it).