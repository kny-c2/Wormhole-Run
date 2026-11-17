
<?php

session_start();

require 'auth.php';

include 'header.php';

?>

<main>

    <h1>Wormhole Run</h1>

    <h2>Game Information</h2>

    <p>
        Player 1:
        <?php echo htmlspecialchars($_SESSION['players'][0]); ?>
    </p>

    <p>
        Player 2:
        <?php echo htmlspecialchars($_SESSION['players'][1]); ?>
    </p>

    <p>
        Difficulty:
        <?php echo htmlspecialchars($_SESSION['difficulty']); ?>
    </p>

    <p>
        Player 1 Position:
        <?php echo $_SESSION['positions'][0]; ?>
    </p>

    <p>
        Player 2 Position:
        <?php echo $_SESSION['positions'][1]; ?>
    </p>

    <p>
        Current Turn:
        <?php echo htmlspecialchars(
            $_SESSION['players'][$_SESSION['turn']]
        ); ?>
    </p>

</main>

<?php include 'footer.php'; ?>
