<?php
  /**
   * head.php
   * 
   * Responsibility: This file renders the standard standard stuff that is contained in the <head> section of each page's HTML.
   * This way we can include common CSS styles, fonts, meta tags, scripts, etc. in one location.
   */
?>
<?php /* IMPORTANT: MUST BE INSIDE HTML <head> </head> TAGS! */
  // incremented by 1 tab just to make it look nice in source code
?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru&family=Source+Sans+Pro&display=swap" rel="stylesheet">
  <link href="styles/colors.css" rel="stylesheet">
  <?php /* global.css should be first, right after the color palette. This defines breakpoint sizes and common global styles. */ ?>
  <link href="styles/global.css" rel="stylesheet">
  <link href="styles/header.css" rel="stylesheet">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
