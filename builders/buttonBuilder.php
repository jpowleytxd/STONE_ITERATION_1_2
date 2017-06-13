<?php

include("common.php");

// a table booking      -> /table
// a party booking      -> /party
// a pre-order          -> /
// view menus           -> /website
// enter a competition  -> /competition
// give feedback        -> /feedback
// request a call back. -> /call

// New Re-directs to be made:
//    -> /competition
//    -> /feedback
//    -> /call

// var_dump(textColor("#ffff01")); die();

// Define hyperlinks for buttons
$tableBookingLink = "http://stonegateemail.co.uk/\$dynamic3\$/table";
$partyBookingLink = "http://stonegateemail.co.uk/\$dynamic3\$/party";
$preOrderLink = "http://stonegateemail.co.uk/\$dynamic3\$/website";
$menuLink = "http://stonegateemail.co.uk/\$dynamic3\$/website";
$competitionLink = "http://stonegateemail.co.uk/\$dynamic3\$/website";
$feedbackLink = "http://stonegateemail.co.uk/\$dynamic3\$/website";
$callBackLink = "http://stonegateemail.co.uk/\$dynamic3\$/website";

// Define text for buttons
$tableBookingText = "Book My Table Now";
$partyBookingText = "Book My Party";
$preOrderText = "Pre Order Now";
$menuText = "View Menus Now";
$competitionText = "Enter Now";
$feedbackText = "Feedback";
$callBackText = "Request A Call";

// Get default button from file
$basicButton = file_get_contents("../sites/_defaults/button.html");

// Get default spacer from file
$basicSpacer = file_get_contents("../sites/_defaults/basic_spacer.html");

// Styles for button insertion
$basicStyles = "text-align:center; font-size: 16px; [[FONT_FAMILY_HERE]] font-weight: normal; [[TEXT_COLOR_HERE]] text-decoration: none; [[BACKGROUND_COLOR_HERE]] border-top-width: 15px; border-bottom-width: 15px; border-left-width: 25px; border-right-width: 25px; border-style: solid; [[BORDER_COLOR_HERE]] border-radius: 3px; -webkit-border-radius: 3px; -moz-border-radius: 3px; display: inline-block;";

// Foreach brand in sites folder
foreach(glob('../sites/*/templates/*_branded.html') as $filename){
  $template = file_get_contents($filename);

  // Get current folder structure, file name and remove file name from folder structure
  preg_match_all('/.*\/(templates\/.*_branded.html)/', $filename, $matches, PREG_SET_ORDER, 0);
  $currentFile = $matches[0][1];
  $folder = str_replace($currentFile, '', $filename);

  // Get brand from filename
  preg_match_all('/.*\/(.*)_branded.html/', $filename, $matches, PREG_SET_ORDER, 0);
  $brand = $matches[0][1];

  // Variables to be set
  $fontFamily;
  $textColor;
  $backgroundColor;
  $borderColor;

  // Get Background Colour
  preg_match_all('/"linkColour": "(.*?)"/', $template, $matches, PREG_SET_ORDER, 0);
  $backgroundColor = "background-color: " . $matches[0][1] . ";";
  $borderColor = "border-color: " . $matches[0][1] . ";";

  // Get Text Colour
  $textColor = textColor($matches[0][1]);
  $textColor = "color: " . $textColor . ";";

  // Get Font Fomily
  preg_match_all('/"h1FontFamily": "(.*?)"/', $template, $matches, PREG_SET_ORDER, 0);
  $fontFamily = "font-family: " . $matches[0][1] . ";";

  // Insert variables into basic style string
  $styleInsert = str_replace("[[FONT_FAMILY_HERE]]", $fontFamily, $basicStyles);
  $styleInsert = str_replace("[[TEXT_COLOR_HERE]]", $textColor, $styleInsert);
  $styleInsert = str_replace("[[BACKGROUND_COLOR_HERE]]", $backgroundColor, $styleInsert);
  $styleInsert = str_replace("[[BORDER_COLOR_HERE]]", $borderColor, $styleInsert);

  // Insert style into venue button
  $venueButton = str_replace("[[STYLE_HERE]]", $styleInsert, $basicButton);

  // Insert link text and link
  $tableBookingButton = str_replace("[[TEXT_HERE]]", $tableBookingText, $venueButton);
  $tableBookingButton = str_replace("[[LINK_HERE]]", $tableBookingLink, $tableBookingButton);

  $partyBookingButton = str_replace("[[TEXT_HERE]]", $partyBookingText, $venueButton);
  $partyBookingButton = str_replace("[[LINK_HERE]]", $partyBookingLink, $partyBookingButton);

  $preOrderButton = str_replace("[[TEXT_HERE]]", $preOrderText, $venueButton);
  $preOrderButton = str_replace("[[LINK_HERE]]", $preOrderLink, $preOrderButton);

  $menuButton = str_replace("[[TEXT_HERE]]", $menuText, $venueButton);
  $menuButton = str_replace("[[LINK_HERE]]", $menuLink, $menuButton);

  $competitionButton = str_replace("[[TEXT_HERE]]", $competitionText, $venueButton);
  $competitionButton = str_replace("[[LINK_HERE]]", $feedbackLink, $competitionButton);

  $feedbackButton = str_replace("[[TEXT_HERE]]", $feedbackText, $venueButton);
  $feedbackButton = str_replace("[[LINK_HERE]]", $feedbackText, $feedbackButton);

  $callBackButton = str_replace("[[TEXT_HERE]]", $callBackText, $venueButton);
  $callBackButton = str_replace("[[LINK_HERE]]", $callBackLink, $callBackButton);

  // Write buttons to file
  $file = $folder . 'bespoke_blocks/' . $brand . '_table_booking_button.html';
  file_put_contents($file, $tableBookingButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_party_booking_button.html';
  file_put_contents($file, $partyBookingButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_pre_order_button.html';
  file_put_contents($file, $preOrderButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_competition_button.html';
  file_put_contents($file, $competitionButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_feedback_button.html';
  file_put_contents($file, $feedbackButton);

  $file = $folder . 'bespoke_blocks/' . $brand . '_call_back_button.html';
  file_put_contents($file, $callBackButton);

  // Generate Demo Code
  $insert = $basicSpacer . $tableBookingButton . $basicSpacer . $partyBookingButton . $basicSpacer . $preOrderButton . $basicSpacer . $competitionButton . $basicSpacer . $feedbackButton . $basicSpacer . $callBackButton . $basicSpacer;
  $search = "/<!-- User Content: Main Content Start -->\s*<!-- User Content: Main Content End -->/";
  $output = preg_replace($search, "<!-- User Content: Main Content Start -->" . $insert . "<!-- User Content: Main Content End -->", $template);

  // Save Demo Code Top File
  $file = '../client.demo/buttons/inner/' . $brand . '_buttons.html';
  file_put_contents($file, $output);
}


?>
