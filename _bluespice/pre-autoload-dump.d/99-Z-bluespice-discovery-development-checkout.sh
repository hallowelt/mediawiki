#!/bin/bash

GREEN='\033[0;32m'
RED='\033[0;31m'
PURPLE='\033[0;35m'
NC='\033[0m'

printf "\n${PURPLE}Clone branch Components-2 for development of BlueSpiceDiscovery${NC}\n\n";
cd vendor/mwstake;
rm -rf mediawiki-component-commonuserinterface;
git clone -b Components-2 --depth=1 https://github.com/hallowelt/mwstake-mediawiki-component-commonuserinterface mediawiki-component-commonuserinterface;
cd mediawiki-component-commonuserinterface;
rm -rf .git;