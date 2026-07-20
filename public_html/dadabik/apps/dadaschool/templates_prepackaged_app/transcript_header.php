<!-- DaDaBIK Template -->
 <?php

$student_info = ddb_api::get_record_details('students', 'id', $_GET['id_student']);

// compute the GPA
$sql_gpa = "SELECT SUM(grade) as grades_sum, COUNT(*) AS grades_count FROM grades WHERE student_id = :student_id";
$res_prepare_gpa = prepare_db($conn, $sql_gpa);
$res_bind_gpa = bind_param_db($res_prepare_gpa, ':student_id', $_GET['id_student']);
$res_gpa = execute_prepared_db($res_prepare_gpa,0);
$row_gpa = fetch_row_db($res_prepare_gpa);
$gpa = round($row_gpa['grades_sum']/$row_gpa['grades_count']/100*4,2);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>High School Student Transcript</title>
    <style>
        th {
            font-weight: bold;
        }
        table {
            width: 50%;
        }

        </style>
</head>
<body>

    <div class="transcript-header">
        <img src="images_prepackaged_app/logo_school_5.png" alt="School Logo">
        <h1>Dante High School</h1>
        <h3>Student Transcript</h3>
    </div>

    <div class="student-info">
        <p><strong>Student Name:</strong> <?= $student_info['first_name'].' '.$student_info['last_name'] ?></p>
        <p><strong>Date of Birth:</strong> <?= $student_info['birthdate'] ?></p>
        <p><strong>Student ID:</strong> <?= $student_info['id'] ?></p>
    </div>

    <table cellpadding="10" border="1">
        <thead>
            <tr>
                <th>Course</th>
                <th>Teacher</th>
                <th>Date</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
