import requests
import json
from datetime import datetime
from flask import Flask, request, jsonify
from tinydb import TinyDB, Query
import time
import threading
import random
import os

print("server is up!")
config_file_path = 'config.json'
players = TinyDB('players.json')

with open(config_file_path, 'r') as config_file:
    config_data = json.load(config_file)
    hypixel_api_key = config_data.get('HYPIXEL_API_KEY')

# Check if the key is present
if not hypixel_api_key:
    raise ValueError("Hypixel API key is missing in config.json")


def get_hypixel_data(username):
    # Contact the Ashcon API to get the UUID from the username
    ashcon_url = f'https://api.ashcon.app/mojang/v2/user/{username}'
    ashcon_data = requests.get(ashcon_url)
    ashcon_json = ashcon_data.json()

    if ashcon_data.status_code != 200:
        print(f"Failed to retrieve UUID for username {username}. Error {ashcon_data.status_code}.")
        return

    uuid = ashcon_json.get('uuid')
    if uuid is None:
        print(f"UUID not found for username {username}.")
        return
    # Use the obtained UUID to fetch data from the Hypixel API
    hypixel_url = f'https://api.hypixel.net/player?key={hypixel_api_key}&uuid={uuid}'
    raw_data = requests.get(hypixel_url)
    json_data = raw_data.json()

    ip = request.remote_addr
    print(f"{ip} Fetched data for {json_data.get('player', {}).get('displayname')}")
    Player = Query()
    existing_player = players.get(Player.player.uuid == uuid)

    if raw_data.status_code != 200:
        print(f"Failed to retrieve data for UUID {uuid}. Error {raw_data.status_code}.")
        return

    if existing_player is not None:
        print(f"Player already exists: {json_data['player']['displayname']}")
        return json_data

    # Create an entry in the players database
    current_time = datetime.now()
    timestamp = current_time.strftime("%Y-%m-%d %H:%M:%S")

    players.insert({'player': {
        "uuid": uuid,
        "timestamp": timestamp
    }})

    return json_data


app = Flask(__name__)

@app.route('/update', methods=['POST'])
def handle_github_webhook():
    try:
        data = request.json
        ref = data['ref']  # This will contain the branch name, e.g., "refs/heads/main" or "refs/heads/beta"

        if ref == "refs/heads/production":
            # Handle updates for the production branch
            os.system("cd /var/www/pixelstats && git stash && git pull")

        elif ref == "refs/heads/beta":
            # Handle updates for the beta branch
            os.system("cd /var/www/pixelstats-beta && git stash && git pull")

        return "Webhook received and processed successfully", 200
    except Exception as e:
        print("Error processing GitHub webhook:", str(e))
        return "Failed to process the webhook", 500


# send requests to this address as a proxy server
@app.route('/requests', methods=['GET'])
def main_route():
    uuid = request.args.get('uuid')
    data = get_hypixel_data(uuid)
    return data

    

@app.route('/tea', methods=['GET'])
def teapot(): 
    return {"status": "I\'m a teapot"}, 418

@app.route('/', methods=['GET'])
def why():
    quotes = [
    "Fail fast, fail cheaply",
    "Become lazy",
    "Read the fucking manual",
    "Target the low hanging fruit",
    "Be part of the solution",
    "Do the simple things",
    "Start simple, get complex",
    "Don't ice an uncooked cake",
    "Less haste, more speed"
  ]
    return {"message": random.choice(quotes)};

@app.route('/api/players', methods=['GET'])
def get_players():
    try:
        with open('players.json', 'r') as json_file:
            data = json_file.read()
            data_fixed = json.loads(data)
            pretty_json_string = json.dumps(data_fixed, indent=4)
        return jsonify(json.loads(pretty_json_string))
    except FileNotFoundError:
        return jsonify({"error": "players.json not found"}), 404

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=8073)

    interval_seconds = 120
    update_thread = threading.Thread(target=run_check_for_updates_interval, args=(interval_seconds,))
    update_thread.daemon = True  # Set the thread as a daemon to terminate when the main thread exits
    update_thread.start()

    while True:
        # Your main application logic or server code goes here
        time.sleep(1)
