<?php
// Sat Mar 14 17:11:35 EDT 2020
// Written by Simon Chu
// PHP web scraper to track the coronavirus situation in the United States
// Source websites:
//     US cases stats, trends and people that are recovered:
//        https://www.worldometers.info/coronavirus/country/us/
//     -US cases:
//        https://www.cdc.gov/coronavirus/2019-ncov/cases-in-us.html
//     -Pennsylvania cases:
//        https://www.health.pa.gov/topics/disease/Pages/Coronavirus.aspx
//     -Oregon cases:
//        https://www.oregon.gov/oha/PH/DISEASESCONDITIONS/DISEASESAZ/Pages/emerging-respiratory-infections.aspx
//     -US, Oregon and Pennsylvania cases:
//        https://www.cnn.com/2020/03/03/health/us-coronavirus-cases-state-by-state/index.html


// to-do
// scrub PA death and Oregon death from government website


// function that parses Oregon Health Department coronavirus cases
// returns an associative array that has the following layout
// Array(
//    "US_infect" =>
//    "US_death" =>
//)
function parseORgov() {
  $ch = curl_init("https://www.oregon.gov/oha/PH/DISEASESCONDITIONS/DISEASESAZ/Pages/emerging-respiratory-infections.aspx");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);

  $OR_infected = "";
  $OR_death = "0";

  // \r\n is for aspx (windows) newline
  $regex = "/<td bgcolor=\"#cff9f3\" style=\"width:15%;height:31px;text-align:center\">(.*)<\/td><\/tr>/";
  //$regex = "/Positive<\/td>\r\n<td bgcolor=\"#cff9f3\" style=\"width:15%;height:31px;text-align:center\">(.*)<sup>\‡/";
  preg_match($regex, $data, $output);
  //print_r ($output);
  $OR_infected = $output[1];
  //$OR_death = $output[2];

  return Array(
    "source_name" => "Oregon Health Authority",
    "source_url" => "https://www.oregon.gov/oha/PH/DISEASESCONDITIONS/DISEASESAZ/Pages/emerging-respiratory-infections.aspx",
    "OR_infected" => fix($OR_infected),
    "OR_death" => fix($OR_death)
  );
}



// function that parses CDC coronavirus cases
// returns an associative array that has the following layout
// Array(
//    "US_infect" =>
//    "US_death" =>
//)
function parseCDC() {
  $ch = curl_init("https://www.cdc.gov/coronavirus/2019-ncov/cases-in-us.html");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);

  $US_infected = "";
  $US_death = "";

  //$regex = "/<\/div><div class=\"card-body bg-white\"><ul>\n<li>Total cases:(.*)<\/li>\n<li>Total deaths:(.*)<\/li>/";
  //$regex = "/Total cases: 4,226<\/li>.*<li>Total deaths: 75<\/li>/";
  preg_match($regex, $data, $output);
  $US_infected = $output[1];
  $US_death = $output[2];

  return Array(
    "source_name" => "U.S. Center for Disease Control (CDC)",
    "source_url" => "https://www.cdc.gov/coronavirus/2019-ncov/cases-in-us.html",
    "US_infected" => fix($US_infected),
    "US_death" => fix($US_death)
  );
}


// function that parses Pennsylvania health department coronavirus cases
// returns an associative array that has the following layout
// Array(
//    "PA_infected" >
//    "PA_death" =>
//)
function parsePAgov() {
  $ch = curl_init("https://www.health.pa.gov/topics/disease/Pages/Coronavirus.aspx");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);

  $PA_infected = "";
  $PA_death = "0";

  $regex = "/<li>To date, there are(.*)confirmed cases of COVID-19 in Pennsylvania.<\/li>/";
  preg_match($regex, $data, $output);
  $PA_infected = $output[1];

  return Array(
    "source_name" => "Pennsylvania Department of Health",
    "source_url" => "https://www.health.pa.gov/topics/disease/Pages/Coronavirus.aspx",
    "PA_infected" => fix($PA_infected),
    "PA_death" => fix($PA_death)
  );
}

// function that parses Worldometer coronavirus cases count website
// returns an associative array that has the following layout
// Array(
//    "US_death" =>
//    "US_infected" =>
//    "US_recovered" =>
//)
function parseWorldometers() {
  $ch = curl_init("https://www.worldometers.info/coronavirus/country/us/");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);

  $US_infected = "";
  $US_death = "";
  $US_recovered = "";

  $regex1 = "/<h1>Coronavirus Cases:<\/h1>.*?<div class=\"maincounter-number\">.*?<span style=\"color:#aaa\">(.*?)<\/span>.*?<\/div>.*?<\/div>.*?<div id=\"maincounter-wrap\" style=\"margin-top:15px\">.*?<h1>Deaths:<\/h1>/";
  preg_match($regex1, $data, $output1);
  //print_r($output1);
  $US_infected = $output1[1];

  $regex2 = "/<h1>Deaths:<\/h1> <div class=\"maincounter-number\"> <span>(.*?)<\/span> <\/div><\/div><div id=\"maincounter-wrap\" style=\"margin-top:15px;\"> <h1>Recovered:<\/h1>/";
  preg_match($regex2, $data, $output2);
  //print_r($output2);
  $US_death = $output2[1];

  $regex3 = "/<h1>Recovered:<\/h1> <div class=\"maincounter-number\" style=\"color:#8ACA2B \"> <span>(.*?)<\/span> <\/div><\/div><div style=\"margin-top:50px;\"><\/div><!-- START --><style>/";
  preg_match($regex3, $data, $output3);
//print_r($output3);
  $US_recovered = $output3[1];

  return Array(
    "source_name" => "Worldometer.info",
    "source_url" => "https://www.worldometers.info/coronavirus/country/us/",
    "US_infected" => fix($US_infected),
    "US_death" => fix($US_death),
    "US_recovered" => fix($US_recovered)
  );
}


// function that parses CNN coronavirus cases count website
// returns an associative array that has the following layout
// Array(
//    "US_death" =>
//    "US_infected" =>
//    "OR_death" =>
//    "OR_infected" =>
//    "PA_death" =>
//    "PA_infected" =>
//)
function parseCNN() {
  $ch = curl_init("https://www.cnn.com/2020/03/03/health/us-coronavirus-cases-state-by-state/index.html");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  $data = curl_exec($ch);
  curl_close($ch);

  $US_infected = "";
  $US_death = "";
  $OR_infected = "";
  $OR_death = "";
  $PA_infect = "";
  $PA_death = "";

  // test on Kansas
  //$regex = "/Kansas: <\/strong>(.*)<\/div><div class=\"zn-body__paragraph\"><strong>Kentucky:/";
  //preg_match($regex, $data, $output);
  //$regex_1 = "/(.*)\(including(.*)death\)/";
  //preg_match($regex_1, $output[1], $output_1);
  // $output_1: Kansas infected and death
  //print_r ($output_1);

  // parse the infected cases of the United States
  $regex1 = "/has more than(.*?)cases/";
  preg_match($regex1, $data, $output1);
  // $output1[1]: coronavirus infected in the United States
  // print $output1[1];
  $US_infected = $output1[1];

  // parse the death toll of the United States
  //$regex1_1 = "/<div class=\"zn-body__paragraph speakable\">At least(.*)people have died:/";
  $regex1_1 = "/At least(.*)people.*have died/";
  preg_match($regex1_1, $data, $output1_1);
  // $output1_1[1]: coronavirus death cases in the United States
  //print_r ($output1_1);
  //print $output1_1[1];
  $US_death = $output1_1[1];



  // parse the data for Oregon
  $regex2 = "/Oregon:<\/strong>(.*)<\/div><div class=\"zn-body__paragraph\"><strong>Pennsylvania:/";
  preg_match($regex2, $data, $output2);
  // $output2[1]: coronavirus infected and death cases in Oregon
  // print "Oregon Infected & Death: " . $output2[1] . "\n";

  // parse the infected and death cases within Oregon
  $regex2_1 = "/(.*)\(including(.*)death\)/";
  preg_match($regex2_1, $output2[1], $output2_1);
  // $output2_1[1]: coronavirus infected cases in Oregon
  // $output2_1[2]: coronavirus death cases in Oregon
  //print_r ($output2_1);
  //print "Oregon Infected: " . $output2_1[1] . "\n";
  //print "Oregon Death: " . $output2_1[2] . "\n";
  if (!empty($output2_1)) {
    $OR_infected = fix($output2_1[1]);
    $OR_death = toNumeric(trim($output2_1[2]));
  } else {
    $OR_infected = fix($output2[1]);
    $OR_death = 0;
  }



  // parse the data for Pennsylvania
  //<strong>Pennsylvania: </strong>45</div><div class="zn-body__paragraph"><strong>Puerto Rico:  $regex = "/<strong>Pennsylvania: <\/strong>(.*)<\/div><div class=\"zn-body__paragraph\"><strong>Puerto Rico:/";
  $regex3 = "/<strong>Pennsylvania:.*<\/strong>(.*)<\/div><div class=\"zn-body__paragraph\"><strong>Puerto Rico:/";
  preg_match($regex3, $data, $output3);
  //print_r ($output3);
  // $output3[1]: coronavirus infected and death cases in Pennsylvania
  //print $output3[1];

  // parse the infected and death cases within Pennsylvania
  $regex3_1 = "/(.*)\(including(.*)death\)/";
  preg_match($regex3_1, $output3[1], $output3_1);
  // $output3_1[1]: coronavirus infected cases in Oregon
  // $output3_1[2]: coronavirus death cases in Oregon
  //print_r ($output3_1);
  //print "Pennsylvania Infected: " . $output3_1[1] . "\n";
  //print "Pennsylvania Death: " . $output3_1[2] . "\n";
  if (!empty($output3_1)) {
    $PA_infected = fix($output3_1[1]);
    $PA_death = toNumeric(trim($output3_1[2]));
  } else {
    $PA_infected = fix($output3[1]);
    $PA_death = 0;
  }

  return Array(
    "source_name" => "CNN",
    "source_url" => "https://www.cnn.com/2020/03/03/health/us-coronavirus-cases-state-by-state/index.html",
    "US_infected" => fix($US_infected),
    "US_death" => fix($US_death),
    "OR_infected" => fix($OR_infected),
    "OR_death" => fix($OR_death),
    "PA_infected" => fix($PA_infected),
    "PA_death" => fix($PA_death),
  );
}

// convert text number (from zero to ten) to numeric (from 1 to 10)
function toNumeric($word) {
    $wordArray = Array(
      "zero" => 0,
      "one" => 1,
      "two" => 2,
      "three" => 3,
      "four" => 4,
      "five" => 5,
      "six" => 6,
      "seven" => 7,
      "eight" => 8,
      "nine" => 9,
      "ten" => 10
    );

    // return the corresponding numeric value of the text
    return $wordArray[$word];
}

// check the highest number item given a two dimensional associative array and the key to be checked
function checkLatest($a, $key) {
  // set the max record
  $max_record = Array(
    "amount" => -1
  );

  foreach ($a as $record) {

    // check if the key exists in the current array
    if (array_key_exists($key, $record) && isset($record[$key])) {

      // next compare it with the max record
      if ($max_record["amount"] < $record[$key]) {
        $max_record["amount"] = $record[$key];
        $max_record["source_name"] = $record["source_name"];
        $max_record["source_url"] = $record["source_url"];
      }
    }
  }
  return $max_record;
}


// function to fix the format of the numeric values collected
function fix($str) {
  $trimed = trim($str);
  $replaceCommas = str_replace(",", "", $trimed);
  return (int) $replaceCommas;
}
?>

