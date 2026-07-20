<?php
// don't delete this line, this must be the first line of your code
if(!defined('custom_page_from_inclusion')) { die(); }
?>

<h1>Welcome, <?= $current_user; ?></h1>


<?php if ($current_id_group == 4){ ?>


        <h2>From the menu you can access</h2>

        <h3>1. List of Teachers</h3>
        <p>You can see the list of all teachers, along with their emails.</p>

        <h3>2. Your Enrolled Classes</h3>
        <p>Check the list of classes you are enrolled in and download the PDF syllabus for each course.</p>

        <h3>3. Grades and Attendance Records</h3>
        <p>You can view your grades and attendance records for all your courses</p>


<?php } elseif ($current_id_group == 5){ // teachers ?>


    <h2>From the menu you can access</h2>

    <h3>1. List of Teachers and Students</h3>
    <p>You can view the list of all teachers and students, along with their emails. </p>

    <h3>2. Your Classes</h3>
    <p>Check the list of classes you teach and upload the PDF syllabus for each class. </p>

    <h3>3. Student Enrollment</h3>
    <p>You can view the list of students enrolled in each of your classes.</p>

    <h3>4. Add Grades and Attendance</h3>
    <p>Manage grades and attendance records for the students in your classes.</p>


<?php } elseif ($current_id_group == 3){ ?>

    <h2>From the menu you can:</h2>

    <h3>1. Manage Teachers</h3>
    <p>You can add, edit, or remove teachers from the system, as well as manage their contact information.</p>

    <h3>2. Manage Classes</h3>
    <p>Add, update, or remove classes, and assign teachers to each class.</p>

    <h3>3. Manage Students</h3>
    <p>Enroll, update, or remove students, and manage their class enrollments. </p>

    <h3>4. Generate Student Transcripts</h3>
    <p>You can generate PDF transcripts for each student based on their academic records. </p>

    <h3>5. See statistics</h3>
    <p>See some statistics (AVG grade by class, AVG graee by student) </p>


<?php
    if (SEND_EMAIL_STUDENT_TEACHER_CREATION === 0){
        echo '<p><b>Automatic emailing of students and teachers</b> is currently disabled. Ask your administrator to enable it if needed.';

    }
?>
    </section>


<?php } elseif ($current_id_group == 1){ ?>

    <h2>In this application, there are four user groups:</h2>

    <h3>1. Admin </h3>
    <h3>2. Office staff</h3>
    <h3>3. Teachers</h3>
    <h3>4. Student </h3>

    <br>
    <p>You can check the users belonging to each group from menu (click on Admin > Users). For all the users, the default password is <b>letizia24</b>

    <p>Since you are an admin, you can also modify this application by clicking on <a href="admin.php" class="btn btn-sm btn-success fw-semibold px-3 mb-1 me-2">
            <i class="bx bx-edit fs-base ms-n1 me-1"></i>
            Edit this App
          </a>


<?php }  ?>
<h3>Watch the video for a full overview of the application</h3>
<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/2AlJWtlqzlQ?si=UnnEkBW7Nrv1zRZJ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>

</body>
</html>
