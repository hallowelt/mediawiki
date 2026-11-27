#!/bin/bash
# Helper script to load the installer into the proper directory
# Copyright: 2021
# License: GPLv3

GREEN='\033[0;32m'
RED='\033[0;31m'
PURPLE='\033[0;35m'
NC='\033[0m'

BRANCH=$(git branch | cut -f 2 -d " ")
BRANCH=${BRANCH%%[[:space:]]}
BRANCH=${BRANCH##[[:space:]]}
BRANCH="${BRANCH//[$'\t\r\n ']}"

if [ "$BRANCH" == "6.0.x" ]
then
	BRANCH="REL1_47-$BRANCH"
fi

printf "\n${PURPLE}Fetching installer: ${NC}"

if ! [ -d "mw-config/overrides/.git" ]
then
	rm -rf mw-config/overrides
	git clone -b $BRANCH --depth 1 https://github.com/wikimedia/bluespice-mw-config-overrides.git mw-config/overrides
else
	git -C mw-config/overrides/ pull
fi

# Check if the git command was successful
if [ $? -ne 0 ]
then
	printf "${RED}[ FAILED ]${NC}\n"
	# Fallback to REL1_47 for any branch (including REL1_47-6.0.x)
	rm -rf mw-config/overrides
	git clone -b REL1_47 --depth 1 https://github.com/wikimedia/bluespice-mw-config-overrides.git mw-config/overrides
fi

if [ $? -ne 0 ]
then
	printf "${RED}[ EXCEPTION FAILED ]${NC}\n"
	exit 1
fi

printf "${GREEN}[ DONE ]${NC}\n"
