<?php session_start();
if ($_POST) {
    $_SESSION["lang"] = $_POST["lang"];
}
?>
<html>
<head>
    <title>exTracker Installer | License Agreement</title>
    <link rel="stylesheet" href="style.css" type="text/css" media="screen"/>
</head>

<body>
<div id="container">
    <?php include("header.php"); ?>
    <?php include("menu.php"); ?>

    <div id="content">
        <div id="main_content">
            <h4>License Agreement</h4>

            <div id="content_install_instructions">
                <p>
                    Please read and accept the License agreement before clicking "Next". By clicking "Next" you are
                    acknowledging the License agreement<br/><br/>
                </p>
                <b>NO LICENSE FOR THIS VERSION!</b>
            </div>
            <div id="content_install_buttons">
                <form action="step3.php" method="post">
                    <input type="hidden" value="<?php echo $_SESSION['lang']; ?>" name="lang"/>
                    <button id="step2_back" onclick="location.href = 'step1.php';" type="button"
                            style="width: 100px; height: 30px">Back
                    </button>
                    <button id="step3" onclick="location.href = 'step3.php';" type="submit"
                            style="width: 100px; height: 30px">Next
                    </button>
                </form>
            </div>
        </div>
        <div id="summary_content">
            <div id="data_summary">
                <p>
                    <b>Introduction</b><br> <br>
                    <b>Select Language</b><br> <br>
                    <b>Licence Agreement</b><br> <br>
                    Environment Verification<br> <br>
                    Database Settings<br> <br>
                    Other Settings<br> <br>
                    Ready to Install<br> <br>
                    Completed Installation<br> <br>
                </p>
            </div>
        </div>
    </div>
    <?php include("footer.php"); ?>

</div>
</body>

</html>
