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
            <a style="margin-top: 10vh;"><b>Create a CV</a>
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
            <button type="button" id="uniPopup"><img src="pencil_b.png"></button>
            </div>


            <label>Select Skills:</label><br>
            <div class="skill-add">
            <?php
                $sql = "SELECT * FROM Skills";
                $result = mysqli_query($dbConn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo "<select name='option' multiple required>"; 
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row["skill_id"]."'>".$row["skill"]."</option>";
                    }
                    echo "</select>";
                }
            ?>
            <button type="button"><img src="pencil_b.png"></button>
            </div>

            <input type="submit" name="subm" value="Save the CV"/>
        </form> 
    </div>

    <div id="uni-popup" class="uni-popup">
        <div class="uni-popup-content">
            <span class="close">&times;</span>
            <h2>Add a new university</h2>
            <form id="addUniForm">
            <input type="text" id="uniName" name="uniName" placeholder="Name of the unviersity..." required>
            <input type="text" id="uniGrade" name="uniGrade" placeholder="Accreditation grade..." required>
            <button type="submit" name="uniSubm">Add</button>
        </div>
    </div>

        <script>
            document.getElementById('uniPopup').addEventListener('click', function() {
            document.getElementById('uni-popup').style.display = 'flex';
            });

            document.querySelector('.close').addEventListener('click', function() {
            document.getElementById('uni-popup').style.display = 'none';
            });

            document.getElementById('addUniForm').addEventListener('submit', function(e) {
                e.preventDefault();
                var uniName = document.getElementById('uniName').value;
                var uniGrade = document.getElementById('uniGrade').value;
    
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'addUni.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.status == 'success') {
                            alert(response.message);
                            document.getElementById('uni-popup').style.display = 'none';

                            var selectElement = document.querySelector('select[name="option"]');
                            var newUni = document.createElement('option');
                            newUni.value = response.newUniId;
                            newUni.textContent = uniName;
                            selectElement.appendChild(newUni);
                
                            document.getElementById('uniName').value = '';
                            document.getElementById('uniGrade').value = '';
                        } else {
                            alert(response.message);
                        }
                    }
                };
                xhr.send('uniName=' + encodeURIComponent(uniName) + '&uniGrade=' + encodeURIComponent(uniGrade));
            });
        </script>
</body>
</html>