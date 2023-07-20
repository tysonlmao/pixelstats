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
  const [username, setUsername] = useState("");
  const [stats, setStats] = useState(null);
  const [commitId, setCommitId] = useState('');

  const API_KEY = "8dcd98de-b58f-4f7a-a54f-a42e1084f326";
  const ASHCON_API_URL = `https://api.ashcon.app/mojang/v2/user/${username}`;
  const HYPIXEL_API_URL = `https://api.hypixel.net/player?key=${API_KEY}`;

  const getStats = async () => {
    try {
      const ashconResponse = await axios.get(ASHCON_API_URL);
      const uuid = ashconResponse.data.uuid;

      const hypixelResponse = await axios.get(`${HYPIXEL_API_URL}&uuid=${uuid}`);
      if (hypixelResponse.data.success) {
        const data = hypixelResponse.data;
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
              value={username}
              onChange={(e) => setUsername(e.target.value)}
              placeholder="Enter username"
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
                  {isNaN(stats.player.firstLogin) ? null : (
                    <>
                      <span className="label">First login</span>
                      <h3>{formatLastLogin(stats.player.firstLogin)}</h3>
                    </>
                  )}
                  {isNaN(stats.player.lastLogin) ? null : (
                    <>
                      <span className="label">Last login</span>
                      <h3>{formatLastLogin(stats.player.lastLogin)}</h3>
                    </>
                  )}

                </div>
              </div>
              <>
                <hr />
                <div>
                  <h3>Bedwars</h3>
                  <span className="label">Coins</span>
                  <h3>{(stats.player.stats.Bedwars.coins).toLocaleString()}</h3>
                  <span className="label">Winstreak</span>
                  <h3>{(stats.player.stats.Bedwars.winstreak) | "Unknown"}</h3>
                  <span className="label">Level</span>
                  <h3>???</h3>
                  {/* 
                    to calculate WLR or KDR use this is an example
                    {Math.round(x / y * 100) / 100}
                  */}
                  <div className="pixel-tables">
                    <table className="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Final Kills</th>
                          <th scope="col">Deaths</th>
                          <th scope="col">KDR</th>
                          <th scope="col">Wins</th>
                          <th scope="col">Losses</th>
                          <th scope="col">WLR</th>
                        </tr>
                        <tr>
                          <th scope="col">Overall</th>
                          <th scope="col">{stats.player.stats.Bedwars.final_kills_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.final_deaths_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.final_kills_bedwars / stats.player.stats.Bedwars.final_deaths_bedwars * 100) / 100}</th>
                          <th scope="col">{stats.player.stats.Bedwars.wins_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.losses_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.wins_bedwars / stats.player.stats.Bedwars.losses_bedwars * 100) / 100}</th>
                        </tr>
                        <tr>
                          <th scope="col">Solo</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_one_final_kills_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_one_final_deaths_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.eight_one_final_kills_bedwars / stats.player.stats.Bedwars.eight_one_final_deaths_bedwars * 100) / 100}</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_one_wins_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_one_losses_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.eight_one_wins_bedwars / stats.player.stats.Bedwars.eight_one_losses_bedwars * 100) / 100}</th>
                        </tr>
                        <tr>
                          <th scope="col">Solo</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_one_final_kills_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_one_final_deaths_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.eight_one_final_kills_bedwars / stats.player.stats.Bedwars.eight_one_final_deaths_bedwars * 100) / 100}</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_one_wins_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_one_losses_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.eight_one_wins_bedwars / stats.player.stats.Bedwars.eight_one_losses_bedwars * 100) / 100}</th>
                        </tr>
                        <tr>
                          <th scope="col">Doubles</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_two_final_kills_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_two_final_deaths_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.eight_two_final_kills_bedwars / stats.player.stats.Bedwars.eight_two_final_deaths_bedwars * 100) / 100}</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_two_wins_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.eight_two_losses_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.eight_two_wins_bedwars / stats.player.stats.Bedwars.eight_one_losses_bedwars * 100) / 100}</th>
                        </tr>
                        <tr>
                          <th scope="col">Threes</th>
                          <th scope="col">{stats.player.stats.Bedwars.four_three_final_kills_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.four_three_final_deaths_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.four_three_final_kills_bedwars / stats.player.stats.Bedwars.four_three_final_deaths_bedwars * 100) / 100}</th>
                          <th scope="col">{stats.player.stats.Bedwars.four_three_wins_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.four_three_losses_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.four_three_wins_bedwars / stats.player.stats.Bedwars.eight_one_losses_bedwars * 100) / 100}</th>
                        </tr>
                        <tr>
                          <th scope="col">Fours</th>
                          <th scope="col">{stats.player.stats.Bedwars.four_four_final_kills_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.four_four_final_deaths_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.four_four_final_kills_bedwars / stats.player.stats.Bedwars.four_four_final_deaths_bedwars * 100) / 100}</th>
                          <th scope="col">{stats.player.stats.Bedwars.four_four_wins_bedwars}</th>
                          <th scope="col">{stats.player.stats.Bedwars.four_four_losses_bedwars}</th>
                          <th scope="col">{Math.round(stats.player.stats.Bedwars.four_four_wins_bedwars / stats.player.stats.Bedwars.eight_one_losses_bedwars * 100) / 100}</th>
                        </tr>
                      </thead>
                    </table>
                  </div>
                </div>
              </>
            </div>
          </>
        )}
      </div>
      <Footer commitId={commitId} />
    </div>
  );
}

export default App;
