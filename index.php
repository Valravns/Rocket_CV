<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rocket CV</title>
    <link rel="stylesheet" href="indexCSS.css"> 

    
</head>
<body>
    <div class="header">
        <nav>
            <ul>
                <li><a href="index.php"><b>Submit CV</b></a></li>
                <li><a href="Inqueries.php"><b>Inquiries</b></a></li>
            </ul>
        </nav>
    </div>
    <div class="form-contents">
        <form method="post" action="#">
            <a><b>Create a CV</b></a> <br> <br>
            <br>
            <input type="text" name="first" placeholder="First Name..." required /> 
            <input type="text" name="middle" placeholder="Middle Name..." required /> 
            <input type="text" name="last" placeholder="Last Name..." required /> 
            <input type="date" name="dob" required /> 
            <div class="uni-add">
            <?php
                include 'config.php';
                include 'create.php';

                $sql = "SELECT * FROM University";
                $result = mysqli_query($dbConn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo "<select name='option' required>"; 
                    echo "<option value='choose'>Choose university...</option>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row["university_id"]."'>".$row["university_name"]."</option>";
                    }
                    echo "</select>";
                }
            ?>
            <button type="button"><img src="pencil_b.png"></button>
            </div>

            <input type="submit" name="subm" value="Save the CV"/>
        </form> 
    </div>
</body>
</html>