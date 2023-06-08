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

// Redirect to edit page if registration number is provided
if (isset($_GET['registrationNumber'])) {
    $registrationNumber = $_GET['registrationNumber'];
    $editPageUrl = 'editPage.php?registrationNumber=' . urlencode($registrationNumber);
    header('Location: ' . $editPageUrl);
    exit;
}

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Evidence</title>
    <meta name="description" content="Module 1 project">
    <link rel="stylesheet" href="styles.css">

</head>

<body>
<h1>Student Evidence</h1>

<div class="container">
    <h2>Add a Student</h2>
    <form action="" method="POST">
        <input type="hidden" name="action" value="add-student">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" placeholder="Please enter the name of your student" required><br>
        <label for="grade">Grade:</label>
        <select name="grade" id="grade" required>
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
        </select><br>
        <label for="classroom">Classroom:</label>
        <select name="classroom" id="classroom" required>
            <option value="">Please select student's classroom</option>
            <option value="Mathematics">Mathematics</option>
            <option value="Physics">Physics</option>
            <option value="Chemistry">Chemistry</option>
            <option value="Informatics">Informatics</option>
        </select>
        <button type="submit" name="addBtn" id="add">Add Student</button>
    </form>
</div>

<div class="container">
    <h2>Student List</h2>
    <?php if (count($myStudents) > 0) : ?>
        <table>
            <thead>
            <tr>
                <th>Registration Number</th>
                <th>Name</th>
                <th>Grade</th>
                <th>Classroom</th>
                <th>Actions</th>

            </tr>
            </thead>
            <tbody>
            <?php foreach ($myStudents as $index => $student) : ?>
                <tr>
                    <td><?php echo $student['registrationNumber']; ?></td>
                    <td><?php echo $student['name']; ?></td>
                    <td><?php echo $student['grade']; ?></td>
                    <td><?php echo $student['classroom']; ?></td>
                    <td>


                        <form method="get" action="editPage.php">
                            <input type="hidden" name="registrationNumber"
                                   value="<?php echo $student['registrationNumber']; ?>">
                            <button type="submit" name="update" id="update">Update</button>
                        </form>
                        <form method="post" action="studentEvidence.php">
                            <input type="hidden" name="delete-action" value="delete-student">
                            <input type="hidden" name="studentIndex" value="<?php echo $index; ?>">
                            <button type="submit" name="delete" id="delete">Delete</button>
                        </form>


                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else : ?>
        <p>No students found.</p>
    <?php endif; ?>
</div>
</body>












