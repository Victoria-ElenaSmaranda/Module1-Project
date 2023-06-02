<?php
global $myStudents;
$fileName = 'studentEvidence.txt';
global $rowList;



// Function to update student information

    // open the file and read each row
    $handle = fopen($fileName, 'r');
    while ($row = fgets($handle)) {
        $rowList = explode(',', trim($row));
        $myStudents[] = [
            'registrationNumber' => $rowList[0],
            'name' => $rowList[1],
            'grade' => (int)$rowList[2],
            'classroom' => $rowList[3],
        ];
    }
    fclose($handle);

if (isset($_POST['registrationNumber'])) {
    $registrationNumber = $_POST['registrationNumber'];
    $name = $_POST['name'];
    $grade = $_POST['grade'];
    $classroom = $_POST['classroom'];

    $nameParts = explode(' ', $name);
    $registrationNumber = '';
    foreach ($nameParts as $part) {
        $registrationNumber .= strtoupper(substr($part, 0, 1));

        $registrationNumber .= date('YmdHis'); // Add timestamp for uniqueness


    }
    function updateStudent($updatedName, $updatedClassroom, $updatedGrade, $myStudents)
    {


        global $fileName;
        $updatedName = 'updatedName';
        $updatedClassroom = 'updatedClassroom';
        $updatedGrade = 'updatedGrade';
        $existingStudent = [
            'name' => $updatedName,
            'grade' => $updatedGrade,
            'classroom' => $updatedClassroom,
        ];


        $handle = fopen($fileName, 'w');
        $updatedStudent = [
            'updatedName' => $updatedName,
            'updatedGrade' => $updatedGrade,
            'updatedClassroom' => $updatedClassroom,
        ];
        $row = implode(',', $updatedStudent);
        fputs($handle, $row . PHP_EOL);
        fclose($handle);

        $myStudents[] = $updatedStudent;
    }
}

// Call the updateStudent function



?>





<link rel="stylesheet" href="editStyle.css">
<div class="container">
    <form name="update-student" method="post" action="studentEvidence.php">
        <h2>Update student</h2>
        <div class="mb-3">
            <label for="registrationNumber">Registration number:
                I want to keep displaying registration number and the name. registration number slot in update page it's not editable, but the name is.
            </label>
            <label for="name">Name:
            <input type="text" name="name" class="form-control" id="name" value="<?php echo $name; ?>">
            <input type="hidden" name="registrationNumber" class="form-control" id="registrationNumber" value="<?php echo $registrationNumber; ?>">
            </label>
        </div>
        <div class="mb-3">
            <label for="grade">Grade:
            <select id="grade" class="form-select" name="grade">
                <option value="">Please select student's grade</option>
                <?php for ($i = 0; $i <= 10; $i++) : ?>
                    <option value="<?= $i ?>" <?php $selected = $grade == $i ?  "selected":""; echo $selected;?>><?= $i ?></option>
                    <?php endfor; ?>
            </select>
            </label>
        </div>
        <div class="mb-3">
            <label for="classroom">Classroom:
            <select id="classroom" class="form-select" name="classroom">
                <option value="">Please select student's classroom</option>
                <option value="Mathematics" <?php $selected = $classroom == "Mathematics" ?  "selected":""; echo $selected;?>>Mathematics</option>
                <option value="Physics" <?php $selected = $classroom == "Physics" ?  "selected":""; echo $selected;?>>Physics</option>
                <option value="Chemistry" <?php $selected = $classroom == "Chemistry" ?  "selected":""; echo $selected;?>>Chemistry</option>
                <option value="Informatics" <?php $selected = $classroom == "Informatics" ?  "selected":""; echo $selected;?>>Informatics</option>
            </select>
            </label>
        </div>
            <button type="submit" name="edit-action" class="update" id="update">Update</button>
        </form>


</div>
<!--<script>-->
<!--let buttonElement = document.getElementById("update");-->
<!--buttonElement.addEventListener('click',() =>{-->
<!--    let input = document.getElementById('name')-->
<!---->
<!--let alertString = input.value + '-' + '' + 'Student updated successfully!';-->
<!--alert(alertString);-->
<!--})-->
<!--</script>-->