<?php
session_start();
require_once("../database/wb_db_connect.php");

// Redirect if not logged in or not a Member
if (!isset($_SESSION["user_id"]) || $_SESSION["user_type"] != 'Member') {
    header("Location: ../index.php");
    exit();
}

$showForm = false;
$user_id = $_SESSION["user_id"];
$payment_id = null;

if ($conn) {
    $sql = "SELECT ID FROM payment WHERE User_ID = $user_id AND Rating_Status = 'Received' LIMIT 1";
    $result = $conn->query($sql);
    if ($result && $result->num_rows > 0) {
        $payment = $result->fetch_assoc();
        $payment_id = $payment["ID"];
        $showForm = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wella's BBQ</title>
    <link rel="icon" type="ico" href="../wb.ico">
    <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/Global_Design.css" rel="stylesheet">
    <style>

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin-inline: 10px;
        }
        #order-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2), 0 6px 20px rgba(0, 0, 0, 0.19);
            display: flex;
            gap: 30px;
            justify-content: center;
            border-top: red solid 10px;
        }
        #order-container h1 {
            font-size: 30px;
            font-weight: 700;
            text-align: center;
            margin-bottom: 40px;
        }
        .star-rating-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .star-rating {
            cursor: pointer;
            width: 80px;
            height: 80px;
            max-height: 50px;
            max-width: 50px;
            filter: grayscale(1);
            transition: scale 0.2s ease-in-out;
        }
        #comment-box {
            padding: 5px;
            margin-top: 10px;
            resize: none;
            width: 100%;
            height: 200px;
            transition: all 0.2s ease-in-out;
        }
        #req-dropdown {
            width: 25px;
            height: 25px;
            rotate: 0deg;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .buttons-container {
            margin-top: 25px;
            display: flex;
            gap: 10px;
        }
        .comment-container {
            margin-inline: auto;
            max-width: 450px;
        }
        button, #submit-rate-button {
            color: white;
            font-weight: 800;
            padding: 10px 20px;
            width: 100%;
            border-radius: 50px;
            transition: all 0.1s ease-in-out;
            height: 60px;
        }
        button {
            background-color: rgb(248, 17, 17);
            border: rgb(248, 17, 17) solid 2px;
        }
        #submit-rate-button {
            background-color: #aaa;
            border: #aaa solid 2px;
            cursor: default;
        }
        @media all and (min-width: 1200px) {
            .star-rating:hover {
                scale: 1.2;
            }
            button:hover {
                background-color: white;
                color: rgb(248, 17, 17);
            }
        }
        @media all and (max-width: 1200px) {
            .star-rating {
                width: 15%;
                height: 15%;
            }
            #order-container {
                gap: 20px;
            }
            .star-rating:active {
                scale: 1.2;
            }
            button:active {
                background-color: white;
                color: rgb(248, 17, 17);
            }
        }
    </style>
</head>
<body>

<main id="MainContent">
    <?php if ($showForm): ?>
        <div id="order-container">
            <form id="rating-form" name="rating-form" action="../database/add_rating.php" method="post">
                <h1>How would you rate your experience?</h1>
                <input type="number" name="rating" id="star-rating-value" style="display: none;" value="0">
                <input type="hidden" name="payment_id" value="<?= $payment_id ?>">

                <div class="star-rating-container">
                    <img id="star1" class="star-rating" src="../images/Icons/RatingStar.png">
                    <img id="star2" class="star-rating" src="../images/Icons/RatingStar.png">
                    <img id="star3" class="star-rating" src="../images/Icons/RatingStar.png">
                    <img id="star4" class="star-rating" src="../images/Icons/RatingStar.png">
                    <img id="star5" class="star-rating" src="../images/Icons/RatingStar.png">
                </div>

                <div class="comment-container">
                    <label for="comment-box" style="margin-top: 20px;">Comments</label>
                    <img id="req-dropdown" src="../images/Icons/moreArrorIcon.png">
                    <br>
                    <textarea name="comment" id="comment-box" maxlength="255" autocomplete="on"></textarea>
                </div>

                <div class="buttons-container">
                    <button type="button" onclick="returnHome()">Rate Later</button>
                    <input id="submit-rate-button" type="button" value="Submit Review" name="submit-rate">
                </div>
            </form>
        </div>
    <?php else: ?>
        <div style="text-align:center; margin-top: 50px;">
            <h2>No orders found to rate.</h2>
            <button onclick="returnHome()" style="max-width: 300px; margin-top: 20px;">Return Home</button>
        </div>
    <?php endif; ?>
</main>

<script src="../javascript/JQUERY.js"></script>
<script src="../javascript/globaljavascript.js"></script>
<script>
    const stars = [1, 2, 3, 4, 5];
    stars.forEach(i => {
        document.getElementById("star" + i).addEventListener("click", () => hoverStar(i));
    });

    function hoverStar(rate) {
        activateSubmitButton();
        document.getElementById("star-rating-value").value = rate;
        stars.forEach(i => {
            document.getElementById("star" + i).style.filter = i <= rate ? "grayscale(0)" : "grayscale(1)";
        });
    }

    let dropped = true;
    document.getElementById("req-dropdown").addEventListener("click", () => {
        const dropdown = document.getElementById("req-dropdown");
        const textarea = document.getElementById("comment-box");
        dropped = !dropped;
        dropdown.style.rotate = dropped ? "0deg" : "-90deg";
        textarea.style.height = dropped ? "200px" : "0px";
    });

    function returnHome() {
        window.location = "../main_pages/home.php";
    }

    function activateSubmitButton() {
        const btn = document.getElementById("submit-rate-button");
        btn.type = "submit";
        btn.style.backgroundColor = "red";
        btn.style.border = "red solid 2px";
        btn.style.cursor = "pointer";
    }

    document.forms["rating-form"].onsubmit = function(e) {
        if (document.getElementById("star-rating-value").value == 0) {
            e.preventDefault();
        }
    }
</script>

</body>
</html>
