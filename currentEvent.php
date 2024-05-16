<?php
include 'events.php';

// Find the current or next event
$current_event = null;
$current_date = date('Y-m-d');
foreach ($rally_events as $event) {
    if ($event['startDate'] <= $current_date && $event['endDate'] >= $current_date) {
        $current_event = $event; // running event
        break;
    } elseif ($event['startDate'] > $current_date) {
        $current_event = $event; // upcoming event
        break;
    }
}
//Display the current or next event
echo '<div id="current-event">';
echo '<h2>Active Event: ' . $current_event['event_name'] . '</h2>';
echo '<p>Start Date: ' . $current_event['startDate'] . '</p>';
echo '<p>End Date: ' . $current_event['endDate'] . '</p>';
echo '<img src="' . $current_event['image_link'] . '" alt="' . $current_event['event_name'] . '">';
echo '</div>';
?>