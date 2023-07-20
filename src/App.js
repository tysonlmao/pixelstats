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
        made by <a target="_blank" rel="noreferrer" href="https://tysonlmao.dev">tysonlmao.dev</a> •{' '}
        <a rel="noreferrer" target="_blank" href={`https://github.com/tysonlmao/pixelstats/commit/${commitId}`}>
          {commitId}
        </a>
      </p>
    </footer>
  );
}

function App() {
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
    } catch {
      setStats(null);
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
        {!stats ? (
          <div className="search-form text-end">
            <input
              type="text"
              value={uuid}
              onChange={(e) => setUuid(e.target.value)}
              placeholder="Enter UUID"
            />
            <button onClick={getStats} className="btn btn-primary">
              Get Stats
            </button>
          </div>
        ) : (
          <>
            <div className="roboto-mono">
              <div className="quinquefive">
                <h2>{stats.player.displayname}</h2>
                <h3>Joined in {formatFirstLogin(stats.player.firstLogin)}</h3>
              </div>
              <div className="row">
                <div className="col">
                  <hr />
                  <span className="label">Karma</span>
                  <h3 className="mt-1">{formatKarma(stats.player.karma)}</h3>
                  <span className="label">Achievement Points</span>
                  <h3>{stats.player.achievementPoints.toLocaleString()}</h3>
                  {isNaN(stats.player.lastLogin) ? null : (
                    <>
                      <span className="label">Last login</span>
                      <h3>{formatLastLogin(stats.player.lastLogin)}</h3>
                    </>
                  )}
                </div>
              </div>
            </div>
          </>
        )}
      </div>
      <Footer commitId={commitId} />
    </div>
  );
}

export default App;