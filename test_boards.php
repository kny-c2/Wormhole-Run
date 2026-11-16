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

?>