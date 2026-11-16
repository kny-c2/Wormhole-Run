<?php
session_start();
require 'auth.php';

$error = '';
$player2 = '';
$board = 'standard';   // Standard is picked by default

// After Player 2 Gets Chosen, on first load it skips
if (isset($_POST['player2'])) {
    $player2 = trim($_POST['player2']);
    $board = $_POST['board'] ?? '';

    if ($player2 === '') {
        $error = 'Please enter a name for Player 2.';
    } elseif (!in_array($board, ['beginner', 'standard', 'expert'])) {
        $error = 'Please choose a board.';
    } else {
        // Start a new game this is the "ticket" Devin's game.php reads
        $_SESSION['players']    = [$_SESSION['user'], $player2];
        $_SESSION['difficulty'] = $board;
        $_SESSION['positions']  = [0, 0];
        $_SESSION['turn']       = 0;
        $_SESSION['rolls']      = [0, 0];
        $_SESSION['last_event'] = '';
        $_SESSION['events_log'] = [];

        // Redirect to game.php w/ all the filled out info and exit this file
        header('Location: game.php');
        exit;
    }
}

include 'header.php';
?>

<main>
  <h1>Set up your game</h1>
  <p>Player 1: <?php echo htmlspecialchars($_SESSION['user']); ?></p>

  <?php if ($error !== ''): ?>
    <p class="error"><?php echo $error; ?></p>
  <?php endif; ?>

  <form method="post" action="setup.php">
    <label for="player2">Player 2's name</label>
    <input type="text" id="player2" name="player2"
           value="<?php echo htmlspecialchars($player2); ?>">

    <fieldset>
      <legend>Choose a board</legend>

      <label>
        <input type="radio" name="board" value="beginner"
          <?php if ($board === 'beginner') echo 'checked'; ?>>
        Beginner
      </label>

      <label>
        <input type="radio" name="board" value="standard"
          <?php if ($board === 'standard') echo 'checked'; ?>>
        Standard
      </label>

      <label>
        <input type="radio" name="board" value="expert"
          <?php if ($board === 'expert') echo 'checked'; ?>>
        Expert
      </label>
    </fieldset>

    <button type="submit">Start game</button>
  </form>
</main>

<?php include 'footer.php'; ?>