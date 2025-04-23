#!/bin/sh
# Helper script to load git submodules for some extensions
# call example: ./load-git-submodules
# gerrit username will be asked and save if unknown
# Author: Leonid Verhovskij <l.verhovskij@gmail.com>
# Copyright: 2017
# License: GPLv3

mv vendor vendor_by_composer

# We want to clone submodules from github, not from Wikimedia gerrit
# Examples:
# 	https://gerrit.wikimedia.org/r/mediawiki/extensions/CiteThisPage -> git@github.com:wikimedia/mediawiki-extensions-CiteThisPage
# 	https://gerrit.wikimedia.org/r/mediawiki/skins/Vector -> git@github.com:wikimedia/mediawiki-skins-Vector
# 	https://gerrit.wikimedia.org/r/mediawiki/vendor -> git@github.com:wikimedia/mediawiki-vendor

sed -i 's|https://gerrit.wikimedia.org/r/mediawiki/|git@github.com:wikimedia/mediawiki-|g' .gitmodules
sed -i 's|mediawiki-extensions/|mediawiki-extensions-|g' .gitmodules
sed -i 's|mediawiki-skins/|mediawiki-skins-|g' .gitmodules

git submodule update --init
rm -rf vendor
mv vendor_by_composer vendor
