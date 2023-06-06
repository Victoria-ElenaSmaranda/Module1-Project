<?php
declare(strict_types=1);

$registrationNumber = $_GET['registrationNumber'] ?? '';
$name = $_GET['name'] ?? '';
$grade = $_GET['grade'] ?? '';
$classroom = $_GET['classroom'] ?? '';

// Retrieve the student's information based on the registration number
$fileName = 'studentEvidence.txt';
$studentInfo = [];
if (file_exists($fileName)) {
    $handle = fopen($fileName, 'r');
    while ($row = fgets($handle)) {
        $rowList = explode(',', trim($row));
        if ($rowList[0] === $registrationNumber) {
            $studentInfo = [
                'registrationNumber' => $rowList[0],
                'name' => $rowList[1],
                'grade' => (int)$rowList[2],
                'classroom' => $rowList[3],
            ];
            break;
        }
    }
    fclose($handle);
}

// Redirect to the student evidence page if no student information is found
if (empty($studentInfo)) {
    header('Location: studentEvidence.php');
    exit;
}

// Update student information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['name'] ?? '';
    $newGrade = $_POST['grade'] ?? '';
    $newClassroom = $_POST['classroom'] ?? '';

    // Validate and update the student information
    $valid = true;
        if ($valid){
        $handle = fopen($fileName, 'r+');
        while ($row = fgets($handle)) {
            $rowList = explode(',', trim($row));
            if ($rowList[0] === $registrationNumber) {
                $rowList[1] = $newName;
                $rowList[2] = $newGrade;
                $rowList[3] = $newClassroom;
                $line = implode(',', $rowList) . PHP_EOL;
                fseek($handle, -strlen($row), SEEK_CUR); // move the file pointer to the beginning of the line
                fwrite($handle, $line);
                break;
            }
        }
        fclose($handle);

        // Redirect to the student evidence page after updating the student information
        header('Location: studentEvidence.php');
        exit;
    }
}
?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Page</title>
    <meta name="description" content="Module 1 project">
    <link rel="stylesheet" href="editStyle.css">
</head>
<div class="container">
    <div id="edit-student" class="header">
        <h2>Edit Student</h2>
        <form name="edit-student" method="post" action="editPage.php?registrationNumber=<?php echo $registrationNumber; ?>">
            <label for="registrationNumber">Registration Number:
                <input type="text" name="registrationNumber" id="registrationNumber" value="<?php echo $registrationNumber; ?>" disabled>
            </label><br>
            <label for="name">Name:
                <input type="text" name="name" id="name" value="<?php echo $studentInfo['name']; ?>">
            </label><br>
            <label for="grade">Grade:</label>
                <select name="grade" id="grade">
                    <option value="<?php echo $studentInfo['grade']; ?>"><?php echo ($studentInfo['grade']); ?></option>
                    <option value="1" <?php echo ($studentInfo['grade'] === ['grade']) ? 'selected' : ''; ?>>1</option>
                    <option value="2" <?php echo ($studentInfo['grade'] === ['grade']) ? 'selected' : ''; ?>>2</option>
                    <option value="3" <?php echo ($studentInfo['grade'] === ['grade']) ? 'selected' : ''; ?>>3</option>
                    <option value="4" <?php echo ($studentInfo['grade'] === ['grade']) ? 'selected' : ''; ?>>4</option>
                    <option value="5" <?php echo ($studentInfo['grade'] === ['grade']) ? 'selected' : ''; ?>>5</option>
                    <option value="6" <?php echo ($studentInfo['grade'] === ['grade']) ? 'selected' : ''; ?>>6</option>
                    <option value="7" <?php echo ($studentInfo['grade'] === ['grade']) ? 'selected' : ''; ?>>7</option>
                    <option value="8" <?php echo ($studentInfo['grade'] === ['grade']) ? 'selected' : ''; ?>>8</option>
                    <option value="9" <?php echo ($studentInfo['grade'] === ['grade']) ? 'selected' : ''; ?>>9</option>
                    <option value="10" <?php echo ($studentInfo['grade'] === ['grade']) ? 'selected' : ''; ?>>10</option>

                </select>
            <br>
            <label for="classroom">Classroom: </label>
            <select name="classroom" id="classroom">
                <option value="<?php echo $studentInfo['classroom']; ?>"><?php echo ($studentInfo['classroom']); ?></option>
                <option value="Mathematics" <?php echo ($studentInfo['classroom'] === 1) ? 'selected' : ''; ?>>Mathematics</option>
                <option value="Physics" <?php echo ($studentInfo['classroom'] === 2) ? 'selected' : ''; ?>>Physics</option>
                <option value="Chemistry" <?php echo ($studentInfo['classroom'] === 3) ? 'selected' : ''; ?>>Chemistry</option>
                <option value="Informatics" <?php echo ($studentInfo['classroom'] === 4) ? 'selected' : ''; ?>>Informatics</option>

            </select>
                <br>
            <button type="submit" id="save" value="Save">Save changes</button>
        </form>
    </div>
</div>
<script>
let buttonElement = document.getElementById("save");
buttonElement.addEventListener('click',() =>{
    let input = document.getElementById('name')

let alertString = input.value + '-' + '' + 'Student updated successfully!';
alert(alertString);
})
</script>

