<?php
/// Function to format the updated time
function formatUpdateTime($updated_at) {
    // Convert the updated time to a Unix timestamp
    $updated_time = strtotime($updated_at);
    // Get the current time
    $current_time = time();
    // Calculate the time difference in seconds
    $time_difference = $current_time - $updated_time;
    
    // Calculate time difference in hours
    $hours_difference = floor($time_difference / (60 * 60));

    // Calculate time difference in days
    $days_difference = floor($time_difference / (60 * 60 * 24));

    // Calculate time difference in weeks
    $weeks_difference = floor($time_difference / (60 * 60 * 24 * 7));

    // Check if it's a new day
    $is_new_day = date("Y-m-d", $updated_time) != date("Y-m-d", $current_time);

    // Format the updated time based on the time difference
    if ($hours_difference < 5 && !$is_new_day) {
        // Less than 5 hours and not a new day: Display only the time
        return date("h:i A", $updated_time);
    } elseif ($days_difference == 0) {
        // Today: Display "Today" and the time
        return "Today " . date("h:i A", $updated_time);
    } elseif ($days_difference == 1 && $is_new_day) {
        // Yesterday: Display "Yesterday" and the time
        return "Yesterday " . date("h:i A", $updated_time);
    } elseif ($weeks_difference < 1) {
        // Within the past week: Display the day of the week and the time
        return date("l h:i A", $updated_time);
    } else {
        // More than a week ago: Display the month and the date
        return date("F j, Y", $updated_time);
    }
}
?>
