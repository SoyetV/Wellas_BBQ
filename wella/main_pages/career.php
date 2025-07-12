<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="ico" href="../wb.ico">
        <link href="../bootstrap-5.3.3-dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/Global_Design.css" rel="stylesheet">
        <link href="../css/career_design.css" rel="stylesheet">
        <title>Wella's BBQ</title>

        <style>
            #cashier-login {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                font-weight: 800;
                font-size: 30px;
                padding: 20px 50px;
                border: 5px solid rgb(220, 31, 31);
                border-radius: 50px;
                background-color: rgb(220, 31, 31);
                color: white;
                transition: all 0.1s ease-in-out;
            }
            @media all and (min-width: 1200px) {
                #cashier-login:hover {
                    background-color: rgba(0, 0, 0, 0);
                    color: white;
                }
            }
            @media all and (max-width: 1200px) {
                #non-official-contacts {
                    font-size: 12px;
                }
                #cashier-login:active {
                    background-color: rgba(0, 0, 0, 0);
                    color: white;
                }
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <?php include "../reusable_php/navtab.php" ?>
        
        <div id="MainContent">
            <div class="hero" id="villain">
                <div id="parent">
                    <div class="title">Wella's</div>
                    <div class="subtitle">BBQ</div>
                    <div class="content">
                        <h2 style="color:rgb(220, 31, 31)">Why Join Us?</h2>
                        <p style="max-width: 500px; margin-inline: auto">Wella's BBQ needs more staff to expand throughout the whole Islands of the Philippines</p>
                        <div class="content">
                            <h2 style="color: rgb(220, 31, 31)">We are Hiring!</h2>
                            <h3>Wella's BBQ Needs:</h3>
                            <ul style="max-width: 200px; text-align: left; margin-inline: auto;">
                                <li>Cashier</li>
                                <li>Branch Employee</li>
                               
                            </ul>
                            <br>
                            <h2 style="color:rgb(220, 31, 31)">Contact us through:</h2>
                            <h3 id="non-official-contacts" style="font-weight: lighter"><strong>NOTOFFICIALWellasBBQ@gmail.com</strong> or <strong>0906 557 4871</strong></h3>
                        </div>
                    </div>

                    <br><br>
                    <hr>
                    <br><br>
                    <div class="content"><h2 style="color: white; font-size: 40px">Already a part of us? Login here</h2></div>
                    
                    <a href="../cashier/cashier_log_in.php"><button id="cashier-login" style="margin-bottom: 70px">LOGIN AS CASHIER</button></a>
                </div>
            </div>

            <!-- Footer -->
            <?php include "../reusable_php/footer.php" ?>
        </div>

        <script src="../javascript/JQUERY.js"></script>
        <script src="../javascript/globaljavascript.js"></script>
        <script src="../javascript/expandnavtab.js"></script>
    </body>
</html>