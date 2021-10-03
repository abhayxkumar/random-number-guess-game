<?php
    session_start();
    function ResetGame()
    {
        unset ( $_SESSION['theMaxNumber'] );
    }

    function InitGame()
    {
        $_SESSION['theNumberToGuess'] = mt_rand (0, $_SESSION['theMaxNumber']);
        $_SESSION['theMaxNumberOfTries'] = 10;
        $_SESSION['theUserTriesCount'] = 0;
        $_SESSION['thePrevGuessesString'] = '';
        $_SESSION['theUserGuess'] = 0;
    }

    function ComputeNumberOfTriesLeft()
    {
        return $_SESSION['theMaxNumberOfTries'] - $_SESSION['theUserTriesCount'];
    }

    function IsNoMoreTriesLeft()
    {
        return ComputeNumberOfTriesLeft() <= 0;
    }

    $aCanPlayGame = false;
    $aUserSubmittedGuess = false;
    $aIsNoMoreTriesLeft = false;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ( isset ($_REQUEST['playgame']) ) {
            $_SESSION['theMaxNumber'] = intval($_REQUEST['theMaxNumber']);
            InitGame();
            $aCanPlayGame = true;
        }
        elseif ( isset ($_REQUEST['submituserguess']) ) {
            $aCanPlayGame = true;
            $aUserSubmittedGuess = true;
            $_SESSION['theUserGuess'] = intval($_REQUEST['theUserGuess']);
        }
        elseif ( isset ($_REQUEST['resetgame']) ) {
            ResetGame();
        }
        else {
            ResetGame();
        }
    }
    else {
        ResetGame();
    }

    $aUserShouldTryLower = false;
    $aUserShouldTryHigher = false;
    $aUserWins = false;
    $aUserLooses = false;
    if ($aCanPlayGame) {
        $aIsNoMoreTriesLeft = IsNoMoreTriesLeft();
        if ( ! $aIsNoMoreTriesLeft ) {
            if ($aUserSubmittedGuess) {
                $aUserGuess = intval($_SESSION['theUserGuess']);
                if ( $aUserGuess > $_SESSION['theNumberToGuess'] ) {
                    $aUserShouldTryLower = true;
                }
                elseif ( $aUserGuess < $_SESSION['theNumberToGuess'] ) {
                    $aUserShouldTryHigher = true;
                }
                else {
                    $aUserWins = true;
                    ResetGame();
                }
                $_SESSION['thePrevGuessesString'] .= $_SESSION['theUserGuess'] . ' ';
                ++ $_SESSION['theUserTriesCount'];
                if ( ! $aUserWins ) {
                    $aIsNoMoreTriesLeft = IsNoMoreTriesLeft();
                    if ($aIsNoMoreTriesLeft) {
                        $aUserLooses = true;
                        ResetGame();
                    }
                }
            }
        }
        else {
            $aUserLooses = true;
            ResetGame();
        }
    }
?>

<?php if ($aUserLooses): ?>

<!DOCTYPE html>
<html>
<head>
    <title>Random Number Guessing Game</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            background: radial-gradient(at top, hsl(214, 47%, 23%), hsl(237, 49%, 15%));
            color: #ffffff;
            min-height: 100vh;
            padding-top: 100px;
            overflow: hidden;
        }

        p {
            font-size: 27px;
            font-weight: 500;
        }

        .ipt-btn {
            display: inline-block;
            margin: 0 auto;
            padding: 10px;
            font-size: 20px;
            font-weight: 400;
            border: 1px solid #000;
            border-radius: 5px;
            background-color: #fff;
            color: #000;
            box-shadow: #000 0px 15px 15px;
        }
    </style>
</head>
<body>
    <p>Sorry, you Loose the Game 🙁</p>
    <p>The Number to Guess was : <?php echo $_SESSION['theNumberToGuess']; ?></p>
    <br>
    <form method="post">
        <input class="ipt-btn" type="submit" name="resetgame" value="Reset Game & Play Again">
    </form>
</body>
</html>

<?php elseif ($aUserWins): ?>

<!DOCTYPE html>
<html>
<head>
    <title>Random Number Guessing Game</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            background: radial-gradient(at top, hsl(214, 47%, 23%), hsl(237, 49%, 15%));
            color: #ffffff;
            min-height: 100vh;
            padding-top: 100px;
            overflow: hidden;
        }

        p {
            font-size: 27px;
            font-weight: 500;
        }

        .ipt-btn {
            display: inline-block;
            margin: 0 auto;
            padding: 10px;
            font-size: 20px;
            font-weight: 400;
            border: 1px solid #000;
            border-radius: 5px;
            background-color: #fff;
            color: #000;
            box-shadow: #000 0px 15px 15px;
        }
        
        img {
            width: 20%;
            margin-top: 150px;
        }
    </style>
</head>
<body>
    <h1>Congrats, You Won the Game 🤩🎉</h1>
    <p>The Number to Guess was : <?php echo $_SESSION['theNumberToGuess'];?> </p>
    <br>
    <form method="post">
        <input class="ipt-btn" type="submit" name="resetgame" value="Reset Game & Play Again">
    </form>
    <img src="https://i.gifer.com/2r67.gif" alt="">
</body>
</html>

<?php elseif ($aCanPlayGame): ?>

<!DOCTYPE html>
<html>
<head>
    <title>Random Number Guessing Game</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            background: radial-gradient(at top, hsl(214, 47%, 23%), hsl(237, 49%, 15%));
            color: #ffffff;
            min-height: 100vh;
            padding-top: 100px;
            overflow: hidden;
        }

        p {
            font-size: 27px;
            font-weight: 500;
        }

        .ipt-box {
            width: 300px;
            height: 30px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px auto;
            padding: 10px;
            margin: 0 0 0 10px;
        }

        .ipt-btn {
            display: inline-block;
            margin: 0 auto;
            padding: 10px;
            font-size: 20px;
            font-weight: 400;
            border: 1px solid #000;
            border-radius: 5px;
            background-color: #fff;
            color: #000;
            box-shadow: #000 0px 15px 15px;
            margin: 0 0 0 20px;
        }

        label {
            font-size: 25px;
            font-weight: 400;
        }
    </style>
</head>
<body>
    <h1>Enter your Guess 🧩🔥</h1>
    <br>
    <br>
    <br>
    <p>The Maximum Number is <?php echo $_SESSION['theMaxNumber']; ?></p>
    <p>Guess a Number in the Range [ 0 .. <?php echo ($_SESSION['theMaxNumber']); ?> ]</p>
    <!-- <p>[FOR TESTING] The number to guess is <?php echo $_SESSION['theNumberToGuess']; ?></p> -->
    <br>
    <br>
    <form method="post">
        <label for="theUserGuess">Enter your Guess: </label>
        <input class="ipt-box" type="text" id="theUserGuess" name="theUserGuess" placeholder="Write here">
        <input class="ipt-btn" type="submit" name="submituserguess" value="Guess">
        <input class="ipt-btn" type="submit" name="resetgame" value="Reset Game">
    </form>
    <br>
    <br>
    <p>⚠️ You have <?php echo ComputeNumberOfTriesLeft(); ?> tries left! ⚠️</p>
    <br>
    <p>Previous Guesses: <?php echo $_SESSION['thePrevGuessesString']; ?> </p>
    <p>
        <?php
            if ($aUserShouldTryLower) {
                echo 'Hint: You should try a lower guess ⬇️';
            }
            elseif ($aUserShouldTryHigher) {
                echo 'Hint: You should try a higher guess ⬆️';
            }
            else {
                echo 'No Guess Till Now! ❌';
            }
        ?>
    </p>

</body>
</html>

<?php else: ?>

<!DOCTYPE html>
<html>
<head>
    <title>Random Number Guessing Game</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            background: radial-gradient(at top, hsl(214, 47%, 23%), hsl(237, 49%, 15%));
            color: #ffffff;
            min-height: 100vh;
            padding-top: 100px;
            overflow: hidden;
        }

        p {
            font-size: 27px;
            font-weight: 500;
        }

        .ipt-box {
            width: 300px;
            height: 30px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin: 10px;
            padding: 10px;
        }

        .ipt-btn {
            display: inline-block;
            margin: 0 auto;
            padding: 10px;
            font-size: 20px;
            font-weight: 400;
            border: 1px solid #000;
            border-radius: 5px;
            background-color: #fff;
            color: #000;
            box-shadow: #000 0px 15px 15px;
        }
    </style>
</head>
<body>
    <h1>Random Number Guessing Game in PHP 🧩</h1>
    <br>
    <br>
    <p>Guess a Number from 0 to ... </p>
    <br>
    <form method="post">
        <input class="ipt-box" id="theMaxNumber" name="theMaxNumber" placeholder="Write maximum number here">
        <input class="ipt-btn" type="submit" name="playgame" value="Play Game">
    </form>

</body>
</html>

<?php endif; ?>

