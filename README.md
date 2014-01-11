tomboy-php
==========

PHP scripts to display notes from Tomboy Notes app (http://wiki.gnome.org/Apps/Tomboy/).

###SETUP

1. First, you need to copy tomboy-php main folder (this repository) tp your web server root (*www* or *htdocs*).
2. Link Tomboy Notes' note folder as folder named 'tomboy' to main folder.

On my laptop (Ubuntu 12.04 + Tomboy Notes v.1.12.0), the notes folder is located on ` ~/.local/share/tomboy ` (*you'll find a bunch of .note files there*).

```bash
$ cd /path/to/tomboy-php
$ ln -s ~/.local/share/tomboy tomboy
```

That's all..

###CREDITS
Scripts mainly taken from https://wiki.gnome.org/Apps/Tomboy/UsageIdeas, I just polish it a bit :)
