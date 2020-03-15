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

require "backend.php";

$a1 = parseCNN();
print "CNN Data:\n";
print_r ($a1);
print "\n\n";

$a2 = parseWorldometers();
print "Worldometers Data:\n";
print_r ($a2);
print "\n\n";

$a3 = parsePAgov();
print "PA Government Health Dept. Data:\n";
print_r ($a3);
print "\n\n";

$a4 = parseCDC();
print "CDC Official Data:\n";
print_r($a4);
print "\n\n";

$a5 = parseORgov();
print "OR Government Health Dept. Data:\n";
print_r($a5);
print "\n\n";

$max_us_death = checkLatest(Array($a1, $a2, $a3, $a4, $a5), "US_death");
print "Latest US Death Toll:\n";
print_r($max_us_death);
print "\n\n";

$max_pa_death = checkLatest(Array($a1, $a2, $a3, $a4, $a5), "PA_death");
print "Latest PA Death Toll:\n";
print_r($max_pa_death);
print "\n\n";
?>
