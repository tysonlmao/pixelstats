#!/bin/bash
# handy dandy command to make things worky
# chmod +x deploy.sh

# Stash any local changes (optional, to avoid conflicts)
git stash

# Pull the latest changes from the GitHub repository
git pull

# Restart the Flask server
pkill -f server.py  # Kills the current running Flask app process
python3 server.py &  # Restarts the Flask app in the background
