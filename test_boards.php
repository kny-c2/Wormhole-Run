<?php

require 'boards.php';

echo "<h1>Wormhole Run Board Test</h1>";

foreach ($boards as $difficulty => $board) {

    echo "<h2>" . ucfirst($difficulty) . "</h2>";

    echo "<h3>Wormholes</h3>";

    foreach ($board['wormholes'] as $start => $end) {
        echo "Cell $start → Cell $end<br>";
    }

    echo "<h3>Black Holes</h3>";

    foreach ($board['black_holes'] as $start => $end) {
        echo "Cell $start → Cell $end<br>";
    }

    echo "<h3>Event Cells</h3>";

    foreach ($board['events'] as $cell) {
        echo "Event at Cell $cell<br>";
    }

}
echo "<h2>Board Validation</h2>";

foreach ($boards as $difficulty => $board) {

    $wormholeStarts = array_keys($board['wormholes']);
    $blackHoleStarts = array_keys($board['black_holes']);

    $allSpecialStarts = array_merge(
        $wormholeStarts,
        $blackHoleStarts
    );

    $duplicates = array_diff_assoc(
        $allSpecialStarts,
        array_unique($allSpecialStarts)
    );

    if (empty($duplicates)) {
        echo ucfirst($difficulty) . ": No overlapping special cells.<br>";
    } else {
        echo ucfirst($difficulty) . ": Overlapping cells found!<br>";
    }
}
?>