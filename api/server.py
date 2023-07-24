import requests
import json
from datetime import datetime
from flask import Flask, request
from tinydb import TinyDB, Query
import random
import os

config_file_path = 'config.json'
players = TinyDB('../database/players.json')

with open(config_file_path, 'r') as config_file:
    config_data = json.load(config_file)
    hypixel_api_key = config_data.get('HYPIXEL_API_KEY')

# Check if the key is present
if not hypixel_api_key:
    raise ValueError("Hypixel API key is missing in config.json")

def get_hypixel_data(uuid):
    url = f'https://api.hypixel.net/player?key={hypixel_api_key}&uuid={uuid}'
    raw_data = requests.get(url)
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

@app.route('/api/webhook', methods=['POST'])
def github_webhook():
    if request.headers.get('X-GitHub-Event') == 'release':
        subprocess.Popen(['./deploy.sh'])
        print("Webhook received! 200")
    return 'Webhook received!', 200

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=80)