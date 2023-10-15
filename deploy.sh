#!/bin/bash

# Variables
LOCAL_PATH="."
REMOTE_USER="lyonglac"
REMOTE_HOST="51.68.11.192"
REMOTE_PATH="/homez.906/lyonglac/www3"

git fetch --all
git reset --hard origin/main

# Synchronisation avec rsync
#rsync -avzp -vvv --chmod=Dug=rwx,Do=,Fug=rw,Fo= --delete --exclude='.env' $LOCAL_PATH $REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH
rsync -avzp -vvv --no-perms --delete --exclude='.env' --exclude='node_modules' $LOCAL_PATH $REMOTE_USER@$REMOTE_HOST:$REMOTE_PATH

echo "Déploiement terminé."
