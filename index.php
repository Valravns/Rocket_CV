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
                <li><a href="../queries/cvInq.php"><b>Show CV's</b></a></li>
                <li><a href="../queries/skillSelectionInq.php"><b>Candidates skills</b></a></li>
            </ul>
        </nav>
    </div>
    <div class="form-contents">
        <form method="post" action="../insert/addCV.php">
            <a style="margin-top: 10vh;"><b>Create a CV</a>
            <br>
            <input type="text" name="first" placeholder="First Name..." pattern="[A-Za-z]{1,20}" required /> 
            <input type="text" name="middle" placeholder="Middle Name..." pattern="[A-Za-z]{1,20}" required /> 
            <input type="text" name="last" placeholder="Last Name..." pattern="[A-Za-z]{1,20}" required /> 
            <input type="date" name="dob" min="1920-07-24" max="2006-07-24" required /> 
            <div class="uni-add">
            <?php
                include '../Rocket_CV/database/config.php';
                include '../Rocket_CV/database/create.php';

                $sql = "SELECT * FROM University";
                $result = mysqli_query($dbConn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo "<select name='option' id='requireUni' required>"; 
                    echo "<option value='choose' disabled selected hidden>Choose university...</option>";
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row["university_id"]."'>".$row["university_name"]."</option>";
                    }
                    echo "</select>";
                }
            ?>
            <button type="button" id="uniPopup"><img src="../photos/used_photos/pencil_b.png"></button>
            </div>


            <label>Select Skills: (multiple choice)</label><br>
            <div class="skill-add">
            <?php
                $sql = "SELECT * FROM Skills";
                $result = mysqli_query($dbConn, $sql);

                if (mysqli_num_rows($result) > 0) {
                    echo "<select name='optiont[]' multiple required>"; 
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='".$row["skill_id"]."'>".$row["skill"]."</option>";
                    }
                    echo "</select>";
                }
            ?>
            <button type="button" id="skillPopup"><img src="../photos/used_photos/pencil_b.png"></button>
            </div>

            <input type="submit" name="subm" value="Save the CV"/>
        </form> 
    </div>

    <div id="uni-popup" class="uni-popup">
        <div class="uni-popup-content">
            <span class="uni-close">&times;</span>
            <h2>Add a new university</h2> <br>
            <form id="addUniForm">
                <input type="text" id="uniName" name="uniName" placeholder="Name of the unviersity..." required>
                <input type="text" id="uniGrade" name="uniGrade" placeholder="Accreditation grade..." required>
                <button type="submit" name="uniSubm">Add</button>
            </form>
        </div>
    </div>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
        var selectElement = document.getElementById('requireUni');
        if (selectElement.value === "choose") {
            e.preventDefault();
            alert('Please select a university!');
        }
});
    </script>
    <script>
        document.getElementById('uniPopup').addEventListener('click', function() {
        document.getElementById('uni-popup').style.display = 'flex';
        });

        document.querySelector('.uni-close').addEventListener('click', function() {
        document.getElementById('uni-popup').style.display = 'none';
        });

        document.getElementById('addUniForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var uniName = document.getElementById('uniName').value;
            var uniGrade = document.getElementById('uniGrade').value;
    
            var checkU = new XMLHttpRequest();
            checkU.open('POST', '../validation/checkUni.php', true);
            checkU.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            checkU.onreadystatechange = function() {
                if (checkU.readyState == 4 && checkU.status == 200) {
                    var checkResponse = JSON.parse(checkU.responseText);

                    if (checkResponse.status === 'exists') {
                        alert(checkResponse.message);
                    } else {
                        var addU = new XMLHttpRequest();
                        addU.open('POST', '../insert/addUni.php', true);
                        addU.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                        addU.onreadystatechange = function() {

                            if (addU.readyState == 4 && addU.status == 200) {
                                var addResponse = JSON.parse(addU.responseText);

                                if (addResponse.status === 'success') {
                                    alert(addResponse.message);
                                    document.getElementById('uni-popup').style.display = 'none';

                                    var selectElement = document.querySelector('select[name="option"]');
                                    var newUni = document.createElement('option');
                                    newUni.value = addResponse.newUniId;
                                    newUni.textContent = uniName;
                                    newUni.selected = true;
                                    selectElement.appendChild(newUni);

                                    document.getElementById('uniName').value = '';
                                    document.getElementById('uniGrade').value = '';
                                } else {
                                    alert(addResponse.message);
                                }
                            }
                        };
                        addU.send('uniName=' + encodeURIComponent(uniName) + '&uniGrade=' + encodeURIComponent(uniGrade));
                    }
                }
            };
            checkU.send('uniName=' + encodeURIComponent(uniName));
        });
    </script>

    <div id="skill-popup" class="skill-popup">
        <div class="skill-popup-content">
            <span class="skill-close">&times;</span>
            <h2>Add a new skill</h2> <br>
            <form id="addSkillForm">
                <input type="text" id="skillName" name="skillName" placeholder="Name of the skill..." required>
                <button type="submit" name="skillSubm">Add</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('skillPopup').addEventListener('click', function() {
            document.getElementById('skill-popup').style.display = 'flex';
            });

        document.querySelector('.skill-close').addEventListener('click', function() {
            document.getElementById('skill-popup').style.display = 'none';
            });

        document.getElementById('addSkillForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var skillName = document.getElementById('skillName').value;


            var checkS = new XMLHttpRequest();
            checkS.open('POST', '../validation/checkSkill.php', true);
            checkS.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            checkS.onreadystatechange = function() {
                if (checkS.readyState == 4 && checkS.status == 200) {
                    var checkResponse = JSON.parse(checkS.responseText);

                    if (checkResponse.status === 'exists') {
                        alert(checkResponse.message);
                    } else {

                        var addS = new XMLHttpRequest();
                        addS.open('POST', '../insert/addSkill.php', true);
                        addS.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        addS.onreadystatechange = function() {

                        if (addS.readyState == 4 && addS.status == 200) {
                            var response = JSON.parse(addS.responseText);

                            if (response.status == 'success') {
                                alert(response.message);
                                document.getElementById('skill-popup').style.display = 'none';
                                console.log("SUCCESS");
                                var selectElement = document.querySelector('select[name="optiont[]"]');
                                var newSkill = document.createElement('option');
                                newSkill.value = response.newSkillId;
                                newSkill.textContent = skillName;
                                selectElement.appendChild(newSkill);
                
                                newSkill.selected = true;
                                document.getElementById('skillName').value = '';
                            } else {
                                alert(response.message);
                            }
                            }
                        };
                        addS.send('skillName=' + encodeURIComponent(skillName));
                    }
                }
            };
            checkS.send('skillName=' + encodeURIComponent(skillName));
        });
    </script>

</body>
</html>