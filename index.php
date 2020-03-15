<!DOCTYPE html>
<html>
  <head>
    <title>Coronavirus Cases in the U.S., Oregon and Pennsylvania</title>
    <style>
      .rounded {
        border-radius: 15px;
        background: #FFFFFF;
        color: #7D7D7D;
        padding: 10px;
        width: 75%;
        height: auto;
        margin: auto;
      }

      body {
        background-color: #7D7D7D;
        color: #FFFFFF;
        font-size: 32px;
        text-align: center;
        margin-padding: 200px;
        <!--font-family: comic sans ms;-->
        font-family: Helvetica, Sans-Serif;
      }

      a:link {
        color: #7D7D7D;
        text-decoration:none;
      }

      a:hover {
        color: #D3D3D3;
      }
  </style>

<script type="text/javascript">
function AlertIt(source_name) {
var answer = confirm ("Please click on OK to continue.")
if (answer)
window.location="http://www.continue.com";
}
</script>

<script type="text/javascript">
function confirmationAlert(source_name, source_url) {
 var answer = confirm ("Source of the data is extracted from " + source_name + "\n\nPlease click on OK to visit the source site.");
 if (answer)
    window.location = source_url;
}
</script>

  </head>
  <body>


<?php
// Sat Mar 14 17:11:35 EDT 2020
// Written by Simon Chu
// PHP web scraper to track the coronavirus situation in the United States
// Source websites:
//     -US cases stats, trends and people that are recovered:
//        https://www.worldometers.info/coronavirus/country/us/
//     -US cases:
//        https://www.cdc.gov/coronavirus/2019-ncov/cases-in-us.html
//     -Pennsylvania cases:
//        https://www.health.pa.gov/topics/disease/Pages/Coronavirus.aspx
//     -Oregon cases:
//        https://www.oregon.gov/oha/PH/DISEASESCONDITIONS/DISEASESAZ/Pages/emerging-respiratory-infections.aspx
//     -US, Oregon and Pennsylvania cases:
//        https://www.cnn.com/2020/03/03/health/us-coronavirus-cases-state-by-state/index.html

require "backend.php";

$a1 = parseCNN();
//print_r ($a1);

$a2 = parseWorldometers();
//print_r ($a2);

$a3 = parsePAgov();
//print_r ($a3);

$a4 = parseCDC();
//print_r($a4);

$a5 = parseORgov();
//print_r($a5);

$array_all = Array($a1, $a2, $a3, $a4, $a5);

genHTML("US", "United States", $array_all);
genHTML("OR", "Oregon", $array_all);
genHTML("PA", "Pennsylvania", $array_all);

// function to generate HTML for different regions
function genHTML($regionAbbreviation, $regionName, $array_all) {
  echo "<div class=\"rounded\">\n";
  echo "<h1>" . $regionName . "</h1>\n";


  $latestInfectedArray = checkLatest($array_all, $regionAbbreviation . "_infected");
  echo "<h2>Coronavirus Cases</h2>\n";
  echo "<h3><a href=\"javascript:confirmationAlert('" . $latestInfectedArray["source_name"] . "','" . $latestInfectedArray["source_url"] . "');\">" . number_format($latestInfectedArray["amount"]) . "</a></h3>\n";


  $latestDeathArray = checkLatest($array_all, $regionAbbreviation . "_death");
  echo "<h2>Deaths</h2>\n";
  echo "<h3><a href=\"javascript:confirmationAlert('" . $latestDeathArray["source_name"] . "','" . $latestDeathArray["source_url"] . "');\">" . number_format($latestDeathArray["amount"]) . "</a></h3>\n";


  // separate check for the US of the number of people recovered
  if ($regionAbbreviation == "US") {
    $latestRecoveredArray = checkLatest($array_all, $regionAbbreviation . "_recovered");
    echo "<h2>Recovered</h2>\n";
    echo "<h3><a href=\"javascript:confirmationAlert('" . $latestRecoveredArray["source_name"] . "','" . $latestRecoveredArray["source_url"] . "');\">" . number_format($latestRecoveredArray["amount"]) . "</a></h3>\n";
  }

  echo "</div>\n";
  echo "<br>\n";
  echo "<br>\n";
}

// display an confirmation alert
//function confirmationAlert($source_name, $source_url) {
//  echo "<script type='text/javascript'>alert('Source of the data is extracted from $source_name');</script>";
//}

?>

  </body>
</html>
