import os
import requests
import json
from datetime import datetime
from flask import Flask, request

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

@app.route('/api/', methods=['GET'])
def main_route():
    uuid = request.args.get('uuid')
    data = get_hypixel_data(uuid)
    return data

@app.route('/', methods=['GET'])
def why():
    return {'message': 'why are you here'}

if __name__ == '__main__':
    app.run(port=3000)
