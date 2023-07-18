const express = require('express');
const axios = require('axios');
require('dotenv').config();

const app = express();
const API_KEY = process.env.API_KEY;

app.get('/api/player/:uuid', async (req, res) => {
    try {
        const { uuid } = req.params;
        const API_URL = `https://api.hypixel.net/player?uuid=${uuid}&key=${API_KEY}`;

        const response = await axios.get(API_URL);
        const data = response.data;

        res.json(data);
    } catch (error) {
        console.error('Error fetching player data:', error);
        res.status(500).json({ error: 'Internal server error' });
    }
});

app.listen(5001, () => {
    console.log('Backend server running on port 5000');
});
