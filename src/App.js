import './App.css';
import axios from 'axios';
import { useState, useEffect } from 'react';
import Nav from "./components/nav";

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
      <p>
        <a href="https://discord.gg/SQD7yvuZ23">Join the discord</a>
      </p>
      <br />
    </footer>
  );
}

function App() {
  const [username, setUsername] = useState("");
  const [stats, setStats] = useState(null);
  const [commitId, setCommitId] = useState('');

  const API_KEY = "f6164dd4-4aba-4082-b499-0b104ec0673c";
  const ASHCON_API_URL = `https://api.ashcon.app/mojang/v2/user/${username}`;
  const HYPIXEL_API_URL = `https://api.hypixel.net/player?key=${API_KEY}`;

  const getStats = async () => {
    try {
      console.log("CONTACTING ASHCON API PLEASE WAIT");
      const ashconResponse = await axios.get(ASHCON_API_URL);
      const uuid = ashconResponse.data.uuid;
      console.log(`searching for ${uuid}`);
      console.log("CONTACTING HYPIXEL API");
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
      console.log(response);
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
          <Nav />
          <header className="custom-header text-uppercase text-center">
            <h2>Stats</h2>
          </header>
        </div>
      </div>
      <div className="container stats">
        {!stats ? (
          <div className="search-form text-end">
            <div className="input-group">
              <input
                type="text"
                className="form-control"
                value={username}
                onChange={(e) => setUsername(e.target.value)}
                placeholder="Enter username"
              />
              <button onClick={getStats} className="btn btn-primary" type="button">
                <i className="bi bi-search"></i>
              </button>
            </div>
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
              {/* 
                {Math.round((x / y) * 100) / 100}
                when you need to calculate fkdr or wlr
              */}
              <>
                {/* 
                  start of bedwars section
                */}
                <div>
                  <div>
                    <hr />
                    <h2>Bedwars</h2>
                    <span className="label">Coins</span>
                    <h3>{stats.player.stats.Bedwars.coins.toLocaleString()}</h3>
                    <span className="label">Winstreak</span>
                    <h3>{stats.player.stats.Bedwars.winstreak || "Unknown"}</h3>
                    <span className="label">Level</span>
                    <h3>???</h3>
                  </div>

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
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">Overall</th>
                          <td>{stats.player.stats.Bedwars.final_kills_bedwars}</td>
                          <td>{stats.player.stats.Bedwars.final_deaths_bedwars}</td>
                          <td>{Math.round((stats.player.stats.Bedwars.final_kills_bedwars / stats.player.stats.Bedwars.final_deaths_bedwars) * 100) / 100}</td>
                          <td>{stats.player.stats.Bedwars.wins_bedwars}</td>
                          <td>{stats.player.stats.Bedwars.losses_bedwars}</td>
                          <td>{Math.round((stats.player.stats.Bedwars.wins_bedwars / stats.player.stats.Bedwars.losses_bedwars) * 100) / 100}</td>
                        </tr>
                        <tr>
                          <th scope="row">Solo</th>
                          <td>{stats.player.stats.Bedwars.eight_one_final_kills_bedwars}</td>
                          <td>{stats.player.stats.Bedwars.eight_one_final_deaths_bedwars}</td>
                          <td>{Math.round((stats.player.stats.Bedwars.eight_one_final_kills_bedwars / stats.player.stats.Bedwars.eight_one_final_deaths_bedwars) * 100) / 100}</td>
                          <td>{stats.player.stats.Bedwars.eight_one_wins_bedwars}</td>
                          <td>{stats.player.stats.Bedwars.eight_one_losses_bedwars}</td>
                          <td>{Math.round((stats.player.stats.Bedwars.eight_one_wins_bedwars / stats.player.stats.Bedwars.eight_one_losses_bedwars) * 100) / 100}</td>
                        </tr>
                        <tr>
                          <th scope="row">Doubles</th>
                          <td>{stats.player.stats.Bedwars.eight_two_final_kills_bedwars}</td>
                          <td>{stats.player.stats.Bedwars.eight_two_final_deaths_bedwars}</td>
                          <td>{Math.round((stats.player.stats.Bedwars.eight_two_final_kills_bedwars / stats.player.stats.Bedwars.eight_two_final_deaths_bedwars) * 100) / 100}</td>
                          <td>{stats.player.stats.Bedwars.eight_two_wins_bedwars}</td>
                          <td>{stats.player.stats.Bedwars.eight_two_losses_bedwars}</td>
                          <td>{Math.round((stats.player.stats.Bedwars.eight_two_wins_bedwars / stats.player.stats.Bedwars.eight_two_losses_bedwars) * 100) / 100}</td>
                        </tr>
                        <tr>
                          <th scope="row">Threes</th>
                          <td>{stats.player.stats.Bedwars.four_three_final_kills_bedwars}</td>
                          <td>{stats.player.stats.Bedwars.four_three_final_deaths_bedwars}</td>
                          <td>{Math.round((stats.player.stats.Bedwars.four_three_final_kills_bedwars / stats.player.stats.Bedwars.four_three_final_deaths_bedwars) * 100) / 100}</td>
                          <td>{stats.player.stats.Bedwars.four_three_wins_bedwars}</td>
                          <td>{stats.player.stats.Bedwars.four_three_losses_bedwars}</td>
                          <td>{Math.round((stats.player.stats.Bedwars.four_three_wins_bedwars / stats.player.stats.Bedwars.four_three_losses_bedwars) * 100) / 100}</td>
                        </tr>
                        <tr>
                          <th scope="row">Fours</th>
                          <td>{stats.player.stats.Bedwars.four_four_final_kills_bedwars}</td>
                          <td>{stats.player.stats.Bedwars.four_four_final_deaths_bedwars}</td>
                          <td>{Math.round((stats.player.stats.Bedwars.four_four_final_kills_bedwars / stats.player.stats.Bedwars.four_four_final_deaths_bedwars) * 100) / 100}</td>
                          <td>{stats.player.stats.Bedwars.four_four_wins_bedwars}</td>
                          <td>{stats.player.stats.Bedwars.four_four_losses_bedwars}</td>
                          <td>{Math.round((stats.player.stats.Bedwars.four_four_wins_bedwars / stats.player.stats.Bedwars.four_four_losses_bedwars) * 100) / 100}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
                {/* 
                  start of the duels section
                */}
                <div>
                  <hr />
                  <h2>Duels</h2>
                  <span className="label">Coins</span>
                  <h3>{stats.player.stats.Duels.coins.toLocaleString()}</h3>
                  <span className="label">Winstreak</span>
                  <h3>{stats.player.stats.Duels.current_overall_winstreak || "Unknown"}</h3>
                  <div className="pixel-tables">
                    <table className="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Wins</th>
                          <th scope="col">Losses</th>
                          <th scope="col">WLR</th>
                          <th scope="col">Kills</th>
                          <th scope="col">Deaths</th>
                          <th scope="col">KDR</th>
                          <th scope="col">Deaths</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <th scope="row">Overall</th>
                          <th>{stats.player.stats.Duels.wins}</th>
                          <th>{stats.player.stats.Duels.losses}</th>
                        </tr>
                      </tbody>
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