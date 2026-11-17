<?php

session_start();

require 'auth.php';
require 'boards.php';
require 'events.php';

// 1. CHECK GAME SESSION

// Make sure the game has been initialized
if (
    !isset($_SESSION['players']) ||
    !isset($_SESSION['positions']) ||
    !isset($_SESSION['difficulty'])
) {
    header('Location: setup.php');
    exit;
}

// Retrieve the selected board
$difficulty = $_SESSION['difficulty'];

if (!isset($boards[$difficulty])) {
    header('Location: setup.php');
    exit;
}

$board = $boards[$difficulty];

// Initialize scores if they do not exist
if (!isset($_SESSION['scores'])) {
    $_SESSION['scores'] = [];
}

// Initialize skipped-turn tracking
if (!isset($_SESSION['skip_turns'])) {
    $_SESSION['skip_turns'] = [false, false];
}

// Current game messages
$message = '';
$diceRoll = null;

// 2. PROCESS DICE ROLL

if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    ($_POST['action'] ?? '') === 'roll'
) {

    // Determine whose turn it is
    $currentPlayer = $_SESSION['turn'];

    $opponent = 1 - $currentPlayer;

    $playerName = $_SESSION['players'][$currentPlayer];

    // Check whether this player must skip a turn
    if ($_SESSION['skip_turns'][$currentPlayer]) {

        $_SESSION['skip_turns'][$currentPlayer] = false;

        $message = "$playerName loses this turn!";

        $_SESSION['turn'] = $opponent;

    } else {

        // Generate a server-side dice roll
        $diceRoll = rand(1, 6);

        // Increase player's roll count
        $_SESSION['rolls'][$currentPlayer]++;

        // Save original position
        $oldPosition = $_SESSION['positions'][$currentPlayer];

        // Move forward, stopping at cell 100
        $newPosition = min(100, $oldPosition + $diceRoll);

        // 3. CHECK WORMHOLES

        if (isset($board['wormholes'][$newPosition])) {

            $destination = $board['wormholes'][$newPosition];

            $message = "$playerName discovered a wormhole! Cell $newPosition to cell $destination.";

            $newPosition = $destination;

        }

        // 4. CHECK BLACK HOLES

        elseif (isset($board['black_holes'][$newPosition])) {

            $destination = $board['black_holes'][$newPosition];

            $message = "$playerName fell into a black hole! Cell $newPosition to cell $destination.";

            $newPosition = $destination;

        }

        // Update player's position
        $_SESSION['positions'][$currentPlayer] = $newPosition;

        // 5. CHECK AI EVENT CELLS

        $extraTurn = false;

        if (in_array($newPosition, $board['events'], true)) {

            $eventResult = triggerEvent(
                $playerName,
                $_SESSION['positions'][$currentPlayer]
            );

            // Save event information
            $_SESSION['last_event'] = $eventResult['message'];

            $_SESSION['events_log'][] = [
                'player' => $playerName,
                'cell' => $newPosition,
                'type' => $eventResult['type'],
                'message' => $eventResult['message']
            ];

            $message = $eventResult['message'];

            // Handle extra turn
            if ($eventResult['type'] === 'roll_again') {
                $extraTurn = true;
            }

            // Handle losing the next turn
            if ($eventResult['type'] === 'lose_turn') {
                $_SESSION['skip_turns'][$currentPlayer] = true;
            }

        } else {

            $_SESSION['last_event'] = '';
        }

        // 6. CHECK BUMP RULE

        $currentPosition = $_SESSION['positions'][$currentPlayer];

        $opponentPosition = $_SESSION['positions'][$opponent];

        if (
            $currentPosition === $opponentPosition &&
            $currentPosition > 1
        ) {

            $_SESSION['positions'][$opponent] = max(
                1,
                $opponentPosition - 10
            );

            $message .= " $playerName bumped $playerName's opponent backward 10 spaces.";

        }

        // 7. CHECK FOR WINNER

        if ($_SESSION['positions'][$currentPlayer] >= 100) {

            // Make sure the winning position is exactly 100
            $_SESSION['positions'][$currentPlayer] = 100;

            // Save the winner's information
            $_SESSION['winner'] = $currentPlayer;

            // Create a record of the winning player
            $winnerResult = [
                'name' => $playerName,
                'rolls' => $_SESSION['rolls'][$currentPlayer],
                'difficulty' => $difficulty
            ];

            // Save the winner for the victory popup
            $_SESSION['winner_result'] = $winnerResult;

            // Add the winner to the leaderboard
            $_SESSION['scores'][] = $winnerResult;

            // Redirect to the leaderboard
            header('Location: leaderboard.php');
            exit;

        }

        // 8. SWITCH TURNS

        if (!$extraTurn) {

            $_SESSION['turn'] = $opponent;

        }

    }

}
// 9. GET CURRENT GAME INFORMATION

$currentTurn = $_SESSION['turn'];

$currentName = $_SESSION['players'][$currentTurn];

// 10. DRAW THE GAME BOARD

include 'header.php';

?>

<main class="game-container">

    <h1>Wormhole Run</h1>

    <h2>
        <?php echo htmlspecialchars(ucfirst($difficulty)); ?>
        Board
    </h2>

    <div class="game-layout">

        <!-- LEFT SIDE: 100 CELL GAME BOARD -->

        <div class="game-board-area">

            <section class="game-board">

                <?php for ($row = 0; $row < 10; $row++): ?>

                    <?php for ($col = 0; $col < 10; $col++): ?>

                        <?php

                        // Calculate number from bottom of board
                        $rowFromBottom = 9 - $row;

                        if ($rowFromBottom % 2 === 0) {

                            $cell = ($rowFromBottom * 10) + $col + 1;

                        } else {

                            $cell = ($rowFromBottom * 10) + (10 - $col);

                        }

                        $cellClass = 'board-cell';

                        // Identify special cells
                        if (isset($board['wormholes'][$cell])) {
                            $cellClass .= ' wormhole';
                        }

                        if (isset($board['black_holes'][$cell])) {
                            $cellClass .= ' black-hole';
                        }

                        if (in_array($cell, $board['events'], true)) {
                            $cellClass .= ' event-cell';
                        }

                        if ($cell === 100) {
                            $cellClass .= ' finish-cell';
                        }

                        ?>

                        <div class="<?php echo $cellClass; ?>">

                            <span><?php echo $cell; ?></span>

                            <?php foreach ($_SESSION['positions'] as $index => $position): ?>

                                <?php if ($position === $cell): ?>

                                    <span class="player-token">
                                        P<?php echo $index + 1; ?>
                                    </span>

                                <?php endif; ?>

                            <?php endforeach; ?>

                            <?php if (isset($board['wormholes'][$cell])): ?>
                                <small>W</small>
                            <?php elseif (isset($board['black_holes'][$cell])): ?>
                                <small>B</small>
                            <?php elseif (in_array($cell, $board['events'], true)): ?>
                                <small>E</small>
                            <?php endif; ?>

                        </div>

                    <?php endfor; ?>

                <?php endfor; ?>

            </section>

        </div>


        <!-- RIGHT SIDE: GAME CONTROLS -->

        <div class="game-sidebar" id="game-controls">

            <!-- Player Information -->

            <section class="player-info">

                <h2>Players</h2>

                <?php foreach ($_SESSION['players'] as $index => $name): ?>

                    <div class="player-card">

                        <h3>
                            <?php echo htmlspecialchars($name); ?>
                        </h3>

                        <p>
                            Position:
                            <?php echo $_SESSION['positions'][$index]; ?>
                        </p>

                        <p>
                            Rolls:
                            <?php echo $_SESSION['rolls'][$index]; ?>
                        </p>

                        <?php if ($currentTurn === $index): ?>
                            <strong>Current Turn</strong>
                        <?php endif; ?>

                    </div>

                <?php endforeach; ?>

            </section>


            <!-- Current Turn and Dice -->

            <section class="game-controls">

                <h2>
                    <?php echo htmlspecialchars($currentName); ?>'s Turn
                </h2>

                <?php if ($diceRoll !== null): ?>

                    <h2 class="dice-result">
                        🎲 Dice Roll: <?php echo $diceRoll; ?>
                    </h2>

                <?php endif; ?>

                <!-- Game Message -->

                <?php if ($message !== ''): ?>

                    <p class="game-message">
                        <?php echo htmlspecialchars($message); ?>
                    </p>

                <?php endif; ?>

                <!-- Roll Dice Form -->

                <form method="post" action="game.php#game-controls">

                    <input type="hidden" name="action" value="roll">

                    <button type="submit">
                        🎲 Roll Dice
                    </button>

                </form>

            </section>


            <!-- AI Event History -->

            <section class="event-history">

                <h2>AI Event Log</h2>

                <?php if (empty($_SESSION['events_log'])): ?>

                    <p>No special events yet.</p>

                <?php else: ?>

                    <?php foreach (array_reverse($_SESSION['events_log']) as $event): ?>

                        <p>
                            <?php echo htmlspecialchars($event['message']); ?>
                        </p>

                    <?php endforeach; ?>

                <?php endif; ?>

            </section>

        </div>

    </div>

</main>

<?php include 'footer.php'; ?>
