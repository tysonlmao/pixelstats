import './App.css';
import axios from 'axios';
import { useState, useEffect } from 'react';

function formatKarma(karma) {
  const million = 1000000;
  if (karma >= million) {
    const formattedKarma = (karma / million).toFixed(1);
    return `${formattedKarma}M`;
  }
  return karma.toLocaleString();
}

function formatLastLogin(lastLogin) {
  if (isNaN(lastLogin)) {
    return "Unknown";
  }

  const date = new Date(lastLogin);
  const year = date.getFullYear().toString();
  const month = (date.getMonth() + 1).toString().padStart(2, '0');
  const day = date.getDate().toString().padStart(2, '0');
  const hours = date.getHours().toString().padStart(2, '0');
  const minutes = date.getMinutes().toString().padStart(2, '0');
  return `${year}/${month}/${day} ${hours}:${minutes}`;
}

function formatFirstLogin(firstLogin) {
  if (isNaN(firstLogin)) {
    return "Unknown";
  }

  const date = new Date(firstLogin);
  const year = date.getFullYear().toString();
  return year;
}

function Footer({ commitId }) {
  return (
    <footer className="text-center mt-5">
      <p>
        made by <a href="https://tysonlmao.dev">tysonlmao.dev</a> •{' '}
        <a href="https://github.com/tysonlmao/pixelstats">{commitId}</a>
      </p>
    </footer>
  );
}

export default function App() {
  const [username, setUsername] = useState("");
  const [uuid, setUuid] = useState("");
  const [stats, setStats] = useState(null);
  const [commitId, setCommitId] = useState('');

  const API_KEY = "8dcd98de-b58f-4f7a-a54f-a42e1084f326";
  const API_URL = `https://api.hypixel.net/player?uuid=${uuid}&key=${API_KEY}`;

  const getStats = async () => {
    try {
      const res = await axios.get(API_URL);
      if (res.data.success) {
        const data = res.data;
        console.log(data);
        setStats(data);
      } else {
        setStats(null);
      }
    } catch (error) {
      console.log(error);
      setStats(null);
    }
  };

  const getUUID = async () => {
    try {
      const response = await axios.get(`https://api.mojang.com/users/profiles/minecraft/${encodeURIComponent(username)}`);
      if (response.data && response.data.id) {
        setUuid(response.data.id);
      } else {
        setUuid("");
      }
    } catch (error) {
      console.log(error);
      setUuid("");
    }
  };

  const handleSearch = () => {
    if (username) {
      getUUID();
      getStats();
    }
  };

  const fetchCommitId = async () => {
    try {
      const response = await axios.get(
        'https://api.github.com/repos/tysonlmao/pixelstats/commits'
      );
      if (response.data.length > 0) {
        const latestCommit = response.data[0];
        setCommitId(latestCommit.sha.substring(0, 7));
      }
    } catch (error) {
      console.log(error);
    }
  };

  useEffect(() => {
    fetchCommitId();
  }, []);

  return (
    <div className="App">
      <div className="nav">
        <div className="container">
          <header className="custom-header text-uppercase text-center">
            <h2>Stats</h2>
          </header>
        </div>
      </div>
      <div className="container stats">
        {!uuid && (
          <div className="search-form text-end">
            <input
              type="text"
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              placeholder="Enter username"
            />
            <button onClick={handleSearch} className="btn btn-primary">
              Get Stats
            </button>
          </div>
        )}
        {uuid && !stats ? (
          <div className="loading-message">
            <p>Loading stats...</p>
          </div>
        ) : null}
        {stats ? (
          <div className="row">
            <div className="col-md">
              <h2>{stats.player.displayname}</h2>
              <h3>Joined in {formatFirstLogin(stats.player.firstLogin)}</h3>
            </div>
            <div className="col-md text-md-end">
              <h3>{formatKarma(stats.player.karma)} KARMA</h3>
              <h3>{stats.player.achievementPoints} AP</h3>
              {isNaN(stats.player.lastLogin) ? null : (
                <h3>{formatLastLogin(stats.player.lastLogin)} last login</h3>
              )}
            </div>
          </div>
        ) : null}
      </div>
      <Footer commitId={commitId} />
    </div>
  );
}

