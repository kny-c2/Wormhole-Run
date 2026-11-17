# Wormhole Run

A two-player space-themed Snakes & Ladders game built with PHP. Black holes pull your ship back and wormholes launch it ahead. The first ship to cell 100 wins.

## How to play

1. Register an account and log in.
2. Enter Player 2's name and pick a board: Beginner, Standard, or Expert.
3. Take turns clicking **Roll Dice**. Your ship moves forward by the roll.
   - **Wormholes** launch you ahead.
   - **Black holes** pull you back.
   - **Bump rule:** land on the other player's cell and they get knocked back 10 spaces.
4. The first ship to reach cell 100 wins. Winners go on the Top 10 leaderboard ranked by fewest rolls.

## Files

| File | Purpose |
|---|---|
| index.php | Login (start page) |
| register.php | Create an account |
| logout.php | Ends the session |
| setup.php | Player 2 name + board choice |
| game.php | The game board |
| leaderboard.php | Top 10 scores |
| team.php | Team page |
| header.php / footer.php | Shared nav and footer |
| auth.php | Sends logged-out visitors to login |
| users.json | Saved accounts (hashed passwords) |
| scores.json | Saved wins |
| style.css | All styling |