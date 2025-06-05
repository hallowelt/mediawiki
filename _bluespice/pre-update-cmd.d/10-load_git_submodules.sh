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
# 	https://gerrit.wikimedia.org/r/mediawiki/extensions/CiteThisPage -> https://github.com/wikimedia/mediawiki-extensions-CiteThisPage
# 	https://gerrit.wikimedia.org/r/mediawiki/skins/Vector -> https://git@github.com/wikimedia/mediawiki-skins-Vector
# 	https://gerrit.wikimedia.org/r/mediawiki/vendor -> https://git@github.com/wikimedia/mediawiki-vendor

sed -i 's|https://gerrit.wikimedia.org/r/mediawiki/|https://github.com/wikimedia/mediawiki-|g' .gitmodules
sed -i 's|mediawiki-extensions/|mediawiki-extensions-|g' .gitmodules
sed -i 's|mediawiki-skins/|mediawiki-skins-|g' .gitmodules

git submodule init
# Only clone with depth 1
submodules=$(git config --file .gitmodules --get-regexp path | awk '{ print $2 }')
for submodule in $submodules; do
	git submodule update --depth 1 -- $submodule
done

# Special handling for submodule in Extension:VisualEditor
sed -i 's|https://gerrit.wikimedia.org/r/VisualEditor/VisualEditor|https://github.com/wikimedia/VisualEditor|g' extensions/VisualEditor/.gitmodules
cd extensions/VisualEditor
git submodule update --init --depth 1 lib/ve
cd -

rm -rf vendor
mv vendor_by_composer vendor
