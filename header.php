<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>
<!DOCTYPE html>
<html>

<head>

    <meta name="viewport" content="width=520, target-densitydpi=high-dpi" />
    <meta http-equiv="Content-Type" content="application/vnd.wap.xhtml+xml; charset=utf-8" />
    <meta name="HandheldFriendly" content="true" />
    <meta name="apple-mobile-web-app-capable" content="yes" />

    <title>FileCoop</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script defer src="https://friconix.com/cdn/friconix.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bellota+Text:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@100;300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
        integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>

    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/filesize/6.1.0/filesize.min.js"></script>

    <!-- Notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>

    <style>
    * {
        font-family: 'Arimo', sans-serif;
        font-weight: 400;
        font-size: 16px;
    }

    body {
        margin: 0;
    }
    </style>

</head>

<body>

    <header>

        <link rel="stylesheet" type="text/css" href="stylesheets/index.css">
        <link rel="stylesheet" type="text/css" href="stylesheets/sidebar.css">
        <!-- overhang notifications -->
        <link href="external/css/overhang.min.css" rel="stylesheet">
        <script src="external/js/overhang.min.js"></script>


        <?php
if(isset($_SESSION['userID'])){
  //IS LOGGED IN
  echo '<div class="topBar"><p>'.$_SESSION['firstName'].' '.$_SESSION['lastName'].'</p>
		<h1 id="brand">FileCoop</h1><form action="includes/logout.inc.php" method="post">
  <button type="submit" name="logout-submit">Log Out</button>
  </form></div>';
} else {
      //IS LOGGED OUT
			echo '<div class="topBar">
				<h1 id="brand">FileCoop</h1>
				</div>';

}
 ?>

    </header>

    </script>

</body>

</html>