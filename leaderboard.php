<?php
session_start();
require 'auth.php';

// Retrieve saved scores
$scores = $_SESSION['scores'] ?? [];

// Sort by fewest rolls first
usort($scores, function ($a, $b) {
    return $a['rolls'] <=> $b['rolls'];
});

// Keep only the top 10
$top = array_slice($scores, 0, 10);

// Retrieve the latest winner for the popup
$winnerResult = $_SESSION['winner_result'] ?? null;

// Prevent the popup from appearing again after refresh
unset($_SESSION['winner_result']);

include 'header.php';
?>
<?php if ($winnerResult !== null): ?>

    <div class="winner-overlay">

        <div class="winner-popup">

            <div class="winner-trophy">🏆</div>

            <h1>MISSION COMPLETE!</h1>

            <h2>
                <?php echo htmlspecialchars($winnerResult['name']); ?>
                WINS!
            </h2>

            <p class="winner-subtitle">
                Congratulations! You conquered the galaxy!
            </p>

            <div class="winner-stats">

                <p>
                    <strong>Winning Player:</strong>
                    <?php echo htmlspecialchars($winnerResult['name']); ?>
                </p>

                <p>
                    <strong>Total Rolls:</strong>
                    <?php echo (int) $winnerResult['rolls']; ?>
                </p>

                <p>
                    <strong>Board:</strong>
                    <?php echo htmlspecialchars(ucfirst($winnerResult['difficulty'])); ?>
                </p>

                <p>
                    <strong>Final Position:</strong>
                    100 / 100
                </p>

            </div>

            <a href="leaderboard.php" class="button">
                View Leaderboard
            </a>

            <a href="setup.php" class="button">
                Play Again
            </a>

        </div>

    </div>

<?php endif; ?>


<main>
  <h1>Top 10 Leaderboard</h1>

  <?php if (count($top) === 0): ?>
    <p>No games finished yet. Go play one!</p>
  <?php else: ?>
    <table>
      <tr>
        <th>#</th>
        <th>Player</th>
        <th>Rolls</th>
      </tr>
      <?php foreach ($top as $i => $score): ?>
        <tr>
          <td><?php echo $i + 1; ?></td>
          <td><?php echo htmlspecialchars($score['name']); ?></td>
          <td><?php echo $score['rolls']; ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  <?php endif; ?>

  <p>
    <a href="setup.php" class="button">Play again</a>
    <a href="logout.php" class="button">Logout</a>
  </p>
</main>

<?php include 'footer.php'; ?>