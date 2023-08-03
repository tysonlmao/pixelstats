import os
import requests
import json
import subprocess

def track_players():
    with open('players.json', 'r') as json_file:
        data = json_file.read()
        # todo
        # handle if file is empty/does not exist
        print(data_fixed)
track_players()

def check_for_updates():
    result = subprocess.run(['git', 'rev-parse', 'HEAD'], capture_output=True, text=True)
    current_commit_id = result.stdout.strip()

    with open('last_commit_id.txt', 'r') as file:
        last_commit_id = file.read().strip()

    if current_commit_id != last_commit_id:
        with open('last_commit_id.txt', 'w') as file: 
            file.write(current_commit_id)
        
    subprocess.run(['../deploy.sh'], capture_output=True, text=True)

if __name__ == "__main__":
    check_for_updates()