<?php

/** 
*
* Vesta Web Interface
*
* Copyright (C) 2018 Carter Roeser <carter@cdgtech.one>
* https://cdgco.github.io/VestaWebInterface
*
* Vesta Web Interface is free software: you can redistribute it and/or modify
* it under the terms of version 3 of the GNU General Public License as published 
* by the Free Software Foundation.
*
* Vesta Web Interface is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with Vesta Web Interface.  If not, see
* <https://github.com/cdgco/VestaWebInterface/blob/master/LICENSE>.
*
*/

session_start();
$configlocation = "../includes/";
if (file_exists( '../includes/config.php' )) { require( '../includes/includes.php'); }  else { header( 'Location: ../install' );};
if(base64_decode($_SESSION['loggedin']) == 'true') {}
else { header('Location: ../login.php'); }

if(isset($mailenabled) && $mailenabled != 'true'){ header("Location: ../error-pages/403.html"); }

$v_domain = $_POST['v_domain'];

// Check antispam option
if (isset($_POST['v_antispam']) && !empty($_POST['v_antispam'])) {
    $v_antispam = 'yes';
} else {
    $v_antispam = 'no';
}

// Check antivirus option
if (isset($_POST['v_antivirus']) && !empty($_POST['v_antivirus'])) {
    $v_antivirus = 'yes';
} else {
    $v_antivirus = 'no';
}

// Check dkim option
if (!empty($_POST['v_dkim'])) {
    $v_dkim = 'yes';
} else {
    $v_dkim = 'no';
}


if ((!isset($_POST['v_domain'])) || ($_POST['v_domain'] == '')) { header('Location: ../add/mail.php?error=1');}

$postvars = array('hash' => $vst_apikey, 'user' => $vst_username,'password' => $vst_password,'returncode' => 'yes','cmd' => 'v-add-mail-domain','arg1' => $username,'arg2' => $v_domain, 'arg3' => $v_antispam, 'arg4' => $v_antivirus, 'arg5' => $v_dkim);

$curl0 = curl_init();
curl_setopt($curl0, CURLOPT_URL, $vst_url);
curl_setopt($curl0, CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl0, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl0, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl0, CURLOPT_POST, true);
curl_setopt($curl0, CURLOPT_POSTFIELDS, http_build_query($postvars));
$r1 = curl_exec($curl0);

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body class="fix-header">
        <div class="preloader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
            </svg>
        </div>

        <form id="form" action="../list/mail.php" method="post">
            <?php 
            echo '<input type="hidden" name="addcode" value="'.$r1.'">';
            ?>
        </form>
        <script type="text/javascript">
            document.getElementById('form').submit();
        </script>
    </body>
    <script src="../plugins/components/jquery/jquery.min.js"></script>
</html>