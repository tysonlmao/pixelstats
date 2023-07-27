import os
import requests
import json

def track_players():
    with open('../data/players.json', 'r') as json_file:
        data = json_file.read()
        data_fixed = json.loads(data)
        print(data_fixed)
track_players()