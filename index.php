<?php

// Input data
// handles more than one country's calculator, if needed

$countries = [
  'ca' => [
    'sourceFolder' => $folderpath = pathinfo(__FILE__,PATHINFO_DIRNAME) . '/../meeting-cost-calculator-data/ca/',
    'outputFolder' => pathinfo(__FILE__,PATHINFO_DIRNAME) . '/../meeting-cost-calculator-vanilla-hugo/static/js/',
  ]
];

// var_dump($countries);

foreach($countries as $key => $countryData) {


  // Import the organizations directory 

  $organizationsFileHeader = "// Organizations for the Meeting Cost Calculator
// This file is generated automatically.

var app = app || {};

";
  $organizationsFile = $countryData['sourceFolder'] . "organizations.csv";
  $organizationsOutput = [];
  
  if (file_exists($organizationsFile)) {
    // Thanks to
    // http://php.net/manual/en/function.str-getcsv.php#117692
    $csv = array_map('str_getcsv', file($organizationsFile));
    array_walk($csv, function (&$a) use ($csv) {
        $a = array_combine($csv[0], $a);
    });
    array_shift($csv);

    foreach($csv as $item) {
      $organizationsOutput[$item['key']] = $item;
      unset($organizationsOutput[$item['key']]['key']);
    }

    if($organizationsOutput) {

      $outputString = $organizationsFileHeader . "
app.organizations = " . json_encode($organizationsOutput, JSON_PRETTY_PRINT) . ";\n";
      
      $filename = $countryData['outputFolder'] . 'organizations.js';

      if(file_put_contents($filename, $outputString)) {
        echo "\nFile saved successfully to \n$filename\n";
      }
      else {
        echo "\nError saving output file.\n";
      }

    }

  }


  // Import rates of pay

  $ratesFileHeader = "// Rates of Pay for the Meeting Cost Calculator
// This file is generated automatically.

var app = app || {};
app.rates = app.rates || {};

";

  // echo $folderpath . "\n";

  $files = glob($countryData['sourceFolder'] . "rates/*.csv");
  $ratesOutput = [];

  if ($files) {

    foreach($files as $filepath) {

      $fileOutput = [];

      if (file_exists($filepath)) {
        // Thanks to
        // http://php.net/manual/en/function.str-getcsv.php#117692
        $csv = array_map('str_getcsv', file($filepath));
        array_walk($csv, function (&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);

        $organizationKey = pathinfo($filepath, PATHINFO_FILENAME);

        if($csv) {
          foreach($csv as $item) {

            $itemOutput = [];
            foreach($item as $key => $value) {
              $itemOutput[strtolower($key)] = $value;
            }
    
            $ratesOutput[$organizationKey][] = $itemOutput;
    
          }
        }
        else {
          echo "No data for $organizationKey.\n";
        }

    } else {
        echo "Error: could not load $organizationKey CSV data file. \n";
    }



    }

    if($ratesOutput) {

      $outputString = $ratesFileHeader . "
app.rates = " . json_encode($ratesOutput, JSON_PRETTY_PRINT) . ";\n";

      $filename = $countryData['outputFolder'] . 'rates.js';

      if(file_put_contents($filename, $outputString)) {
        echo "\nFile saved successfully to \n$filename\n";
      }
      else {
        echo "\nError saving output file.\n";
      }
    }
    

  }
  else {
    echo "No data files found.\n";
  }


}


exit();

