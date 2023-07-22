export interface PlayerData {
  player: {
    displayname: string;
    karma: number;
    networkExp: number;
    achievementPoints: number;
    firstLogin: number;
    stats: {
      Bedwars: {
        wins_bedwars: number;
        final_kills_bedwars: number;
        final_deaths_bedwars: number;
        beds_broken_bedwars: number;
        beds_lost_bedwars: number;
        kills_bedwars: number;
        deaths_bedwars: number;
        losses_bedwars: number;
        Experience: number;
        coins: number;
        // solos
        eight_one_final_kills_bedwars: number;
        eight_one_final_deaths_bedwars: number;
        eight_one_wins_bedwars: number;
        eight_one_losses_bedwars: number;
        // doubles
        eight_two_final_kills_bedwars: number;
        eight_two_final_deaths_bedwars: number;
        eight_two_wins_bedwars: number;
        eight_two_losses_bedwars: number;
        // threes
        four_three_final_kills_bedwars: number;
        four_three_final_deaths_bedwars: number;
        four_three_wins_bedwars: number;
        four_three_losses_bedwars: number;
        // fours
        four_four_final_kills_bedwars: number;
        four_four_final_deaths_bedwars: number;
        four_four_wins_bedwars: number;
        four_four_losses_bedwars: number;
      };
      Duels: {
        wins: number;
        losses: number;
        kills: number;
        deaths: number;
        best_overall_winstreak: number;
        current_overall_winstreak: number;
        coins: number;
      };
      SkyWars: {
        wins: number;
        losses: number;
        kills: number;
        lastMode: string;
        win_streak: number;
        coins: number;
      };
    };
  };
}
