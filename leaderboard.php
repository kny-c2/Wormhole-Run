<?php
session_start();
require 'auth.php';

// Read every score from scores.json
$scores = json_decode(file_get_contents('scores.json'), true);

// Sort by fewest rolls first
usort($scores, function ($a, $b) {
    return $a['rolls'] - $b['rolls'];
});

// Keep only the top 10
$top = array_slice($scores, 0, 10);

include 'header.php';
?>

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
    <a href="setup.php">Play again</a>
    <a href="logout.php">Logout</a>
  </p>
</main>

<?php include 'footer.php'; ?>