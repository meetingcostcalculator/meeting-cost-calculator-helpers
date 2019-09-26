<?php

// Import helper to convert existing JSON to CSV format
// run via terminal, e.g. `php import.php`
// and then paste the output into a new CSV file.

$output = "label,min,max,description\n";

// Paste stringified JSON data here
$data = '[{"label":"Level 1","description":"","min":"33740","median":"36005","max":"38270"},{"label":"Level 2","description":"","min":"39410","median":"43700","max":"47990"},{"label":"Level 3","description":"","min":"43320","median":"48015","max":"52710"},{"label":"Level 4","description":"","min":"48530","median":"53780","max":"59030"},{"label":"Level 5","description":"","min":"54340","median":"60215","max":"66090"},{"label":"Level 6","description":"","min":"61380","median":"68025","max":"74670"},{"label":"Level 7","description":"","min":"69350","median":"76855","max":"84360"},{"label":"Level 8","description":"","min":"78800","median":"87335","max":"95870"},{"label":"Level 9","description":"","min":"89400","median":"99110","max":"108820"},{"label":"Level 10","description":"","min":"100210","median":"110440","max":"120670"},{"label":"Level 11","description":"","min":"111620","median":"122785","max":"133950"}]';

$data = json_decode($data, 1);

// var_dump($data);

foreach($data as $entry) {
  $output .= $entry['label'] . "," . $entry['min'] . "," . $entry['max'] . "," . $entry['description'] . "\n";
}

echo $output;
