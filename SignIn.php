<!doctype html>

<?php
//include('db.php'); //Create connection to databse.
session_Start();
?>

<html>

<head>

    <meta charset="utf-8">

    <title>Sign in</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">



    <style>
        <?php include 'baseCamp.css';

        ?>
    </style>

</head>

<body id="logInSignInBackground">

    <div class="container position-relative " style="height:100vh;">
        <div class="position-absolute start-0 backButton">
            <a href="javascript:history.back()">
                <button id="goBack">
                    < </button>
            </a>
        </div>

        <<<<<<< HEAD <div style="height:800px;"
            class="position-absolute top-50 start-50 translate-middle d-flex flex-column align-items-center ">
            <p id="text">Registrera</p>
            <form action="getRegInfo.php" method="post" class="d-flex flex-column align-items-center">
                =======
                <a href="javascript:history.back()">


                    <button id="goBack">

                        < </button>

                </a>

                <br><br><br>

                <center>

                    <p id="text">

                        Registrera

                    </p>

                </center>

                <center>


                    <form action="getRegInfo.php" method="post">
                        <br>

                        <label for="email" id="labelForEmail">

                            Email:

                        </label>
                        >>>>>>> 5150aed48505ab5d2e63f8bc9320dc0526d682d8

                        <label for="email" id="labelForEmail">Email:</label>

                        <input type="text" id="email" name="email"
                            pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-z]{2,}$"
                            title="DU använder ogiltiga tecken, använd endast a-z,A-Z,0-9" required class="mt-3">

                        <?php

                        echo $_SESSION["account_CREATION"] ?? "";

                        ?>

                        <label for="password" id="labelForPassword">Lösenord:</label>

                        <input type="password" id="password" name="password"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
                            title="Must contain at least one  number and one uppercase and lowercase letter, and at least 8 or more characters"
                            required class="mt-3">

                        <button id="submit" type="submit" class="mt-5">

                            Registrera

                        </button>


                        <a href="#" id="forgotPassword" class="my-5">

                            Glömt lösenord

                        </a>

                    </form>

    </div>

    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>