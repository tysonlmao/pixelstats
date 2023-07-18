import './App.css';
import axios from 'axios';
import { useState, useEffect } from 'react';

function App() {
  const [stats, setStats] = useState(null);

  const player = "364c34e6-7bd3-4c4a-8718-de6edca912da";
  const API_KEY = "";
  const API_URL = `https://api.hypixel.net/player?uuid=${player}&key=${API_KEY}`;


  const getStats = async () => {
    try {
      const res = await axios.get(API_URL);

      if (res.data.success) { // Check if the request was successful
        const data = res.data;
        console.log(data);
        setStats(data);
      } else {
        setStats(null);
        // handles the error if there is one
      }
    } catch {
      setStats(null);
    }
  };

  useEffect(() => {
    getStats();
  }, []);

  return (
    <div className="App">
      <div className="nav">
        <div className="container">
          <h2>pixelstats</h2>
        </div>
      </div>
      <div className="container">
        {stats ? (
          <>
            <h2>{stats.player.displayname}</h2>
            <h3>{stats.player.karma.toLocaleString()} karma</h3>
            <h3>{stats.player.achievementPoints} ap</h3>
            <h3>{stats.player.lastLogin} last login</h3>

          </>
        ) : (
          <>
            <p>contacting mission control</p>
            <div class="spinner-border" role="status">
              <span class="visually-hidden">Loading...</span>
            </div>
          </>
        )}
      </div>
    </div>
  );
}

export default App;
