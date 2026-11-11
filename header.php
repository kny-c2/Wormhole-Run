<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Wormhole Run</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header class="site-header">
    <a href="index.php" class="logo">Wormhole Run</a>
    <nav>
      <?php if (isset($_SESSION['user'])): ?>
        <a href="setup.php">Play</a>
        <a href="leaderboard.php">Leaderboard</a>
        <a href="team.php">Team</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="index.php">Login</a>
        <a href="register.php">Register</a>
        <a href="team.php">Team</a>
      <?php endif; ?>
    </nav>
  </header>