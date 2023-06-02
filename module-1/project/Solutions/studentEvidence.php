<?php
declare(strict_types=1);
$fileName = 'studentEvidence.txt';
$myStudents = [];

// read the file if it exists
if (file_exists($fileName)) {
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
}

// add a new student


if (isset($_POST['action']) && $_POST['action'] === 'add-student') {
    $name = $_POST['name'];
    $grade = $_POST['grade'];
    $classroom = $_POST['classroom'];

    // Generate registration number


    $registrationNumber = generateRegistrationNumber($name);

    $handle = fopen($fileName, 'a');
    $newStudent = [
        'registrationNumber' => $registrationNumber,
        'name' => $name,
        'grade' => $grade,
        'classroom' => $classroom,
    ];
    $row = implode(',', $newStudent);
    fputs($handle, $row . PHP_EOL);
    fclose($handle);

    $myStudents[] = $newStudent;
}


// Function to generate registration number

function generateRegistrationNumber($name): string
{



    $nameParts = explode(' ', $name);
    $registrationNumber = '';
    foreach ($nameParts as $part) {
        $registrationNumber .= strtoupper(substr($part, 0, 1));
    }
    $registrationNumber .= date('YmdHis'); // Add timestamp for uniqueness
    return $registrationNumber;

}


// delete a student
if (isset($_POST['delete-action'])) {
    $studentIndex = (int)$_POST['studentIndex'];
    unset($myStudents[$studentIndex]);
    $myStudents = array_values($myStudents);

    // rewrite the entire txt file
    $handle = fopen($fileName, 'w');
    foreach ($myStudents as $student) {
        $row = implode(',', $student);
        fputs($handle, $row . PHP_EOL);
    }
    fclose($handle);
}
if (isset($_POST['edit-action'])){
    $registrationNumber = $_POST['registrationNumber'];
    $name = $_POST['name'];
    $grade = $_POST['grade'];
    $classroom = $_POST['classroom'];

    foreach ($myStudents as $key => $student){
        if ($student['registrationNumber'] == $registrationNumber){
            $myStudents[$key] = [$registrationNumber, $name, $grade, $classroom];
        }

}
    $dataString = '';
    foreach ($myStudents as $student){

        $dataString .= implode(',' , array_values($student)) . PHP_EOL;
    }

    $handle = fopen($fileName, 'w');
    fputs($handle, $dataString . PHP_EOL);
    fclose($handle);
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Evidence</title>
    <meta name="description" content="Module 1 project">
    <link rel="stylesheet" href="styles.css">
</head>
<div class="container">
    <div id="student-evidence" class="header">
        <form name="student-evidence" method="post" action="studentEvidence.php">
            <h2>Students Evidence</h2>
            <label for="name">Name:
                <input type="text" name="name" id="name" placeholder="Enter the name of your student">
            </label><br>
            <label for="grade">Grade:
                <select name="grade" id="grade">
                    <option value="">Please select student's grade</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </label><br>
            <label for="classroom">Classroom:
                <select name="classroom" id="classroom">
                    <option value="">Please select student's classroom</option>
                    <option value="Mathematics">Mathematics</option>
                    <option value="Physics">Physics</option>
                    <option value="Chemistry">Chemistry</option>
                    <option value="Informatics">Informatics</option>
                </select>
            </label>
            <button type="submit" class="addBtn">Add</button>
            <input type="hidden" name="studentIndex" value="#"/>
            <input type="hidden" name="action" value="add-student"/>
        </form>
    </div>
    <h2 class="header">Listed Students</h2>
    <div class="mb-3">
        <table class="table">
            <thead>
            <tr>
                <th scope="col">Registration Number</th>
                <th scope="col">Name</th>
                <th scope="col">Grade</th>
                <th scope="col">Classroom</th>
                <th scope="col">Update</th>
                <th scope="col">Delete</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($myStudents as $index => $student): ?>
                <?php $addedStudent = ($student['grade'] == 1) ? 'class="added"' : ''; ?>
                <tr
                    <?= $addedStudent ?>>

                    <td><?= $student['registrationNumber'] ?></td>
                    <td><?= $student['name'] ?></td>
                    <td><?= $student['grade'] ?></td>
                    <td><?= $student['classroom'] ?></td>
                    <td>
                        <form method="post" action="editPage.php">
                            <input type="hidden" name="registrationNumber" value="<?= $student['registrationNumber']?>"/>
                            <input type="hidden" name="name" value="<?= $student['name']?>"/>
                            <input type="hidden" name="grade" value="<?= $student['grade']?>"/>
                            <input type="hidden" name="classroom" value="<?= $student['classroom']?>"/>
                            <button type="submit" name="edit-action" class="update">Update</button>
                        </form>
                    </td>

                    <td>
                        <form method="post" action="studentEvidence.php">
                            <input type="hidden" name="studentIndex" value="<?= $index ?>"/>
                            <button type="submit" name="delete-action" class="delete">Delete</button>
                        </form>
                    </td>

                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</div>
<script>
    let list = document.querySelector('ul');
    let form = document.querySelector('form[name=student-evidence]');
    let inputStudentIndex = form.querySelector('input[name=studentIndex]');
    let inputAction = form.querySelector('input[name=action]');
</script>
