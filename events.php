
<?php

 // AI Event System


function triggerEvent($playerName, &$position)
{
    // Four possible random events
    $eventTypes = [
        'boost',
        'setback',
        'lose_turn',
        'roll_again'
    ];

    // Randomly select one event
    $event = $eventTypes[array_rand($eventTypes)];

    // Default message
    $message = '';

    // Apply the selected event
    switch ($event) {

        case 'boost':

            $oldPosition = $position;

            $position = min(100, $position + 5);

            $message = "A solar wind boosts $playerName ahead from cell $oldPosition to cell $position!";

            break;

        case 'setback':

            $oldPosition = $position;

            $position = max(1, $position - 5);

            $message = "A cosmic storm pushes $playerName backward from cell $oldPosition to cell $position!";

            break;

        case 'lose_turn':

            $message = "$playerName encounters a gravitational disturbance and will lose their next turn!";

            break;

        case 'roll_again':

            $message = "$playerName discovers a powerful energy field and earns another roll!";

            break;
    }

    // Return information about the event
    return [
        'type' => $event,
        'message' => $message
    ];
}

?>
