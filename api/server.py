import os
import requests
import json
from datetime import datetime
from flask import Flask, request
import random

hypixel_api_key = os.environ.get('HYPIXEL_API_KEY')

def get_hypixel_data(uuid):
    url = f'https://api.hypixel.net/player?key={hypixel_api_key}&uuid={uuid}'
    raw_data = requests.get(url)
    json_data = raw_data.json()

    ip = request.remote_addr
    print(f"{ip} Fetched data for {json_data['player']['displayname']}")

    if raw_data.status_code != 200:
        print(f"Failed to retrieve data for UUID {uuid}. Error {raw_data.status_code}.")
        return

    current_time = datetime.now()
    timestamp = current_time.strftime("%Y-%m-%d %H:%M:%S")
    return json_data

app = Flask(__name__)

@app.route('/api/hypixel', methods=['GET'])
def main_route():
    uuid = request.args.get('uuid')
    data = get_hypixel_data(uuid)
    return data

@app.route('/tea', methods=['GET'])
def teapot(): 
    return {"status": "im a teapot"}, 418

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
    if request.headers.get('X-GitHub-Event') == 'push':
        # Call the deployment script to update the code and restart the server
        subprocess.Popen(['./deploy.sh'])
        print("Webhook recieved! 200")
    return 'Webhook received!', 200

if __name__ == '__main__':
    app.run(port=80)
