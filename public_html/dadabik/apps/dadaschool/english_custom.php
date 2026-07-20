<?php

$normal_messages_ar['dadabik_validate_enrollment_not_valid'] = 'This student is already enrolled in this class.';
$normal_messages_ar['dadabik_validate_grade_not_valid'] = 'For this student and this class, a grade on the same date has already been  registered.';
$normal_messages_ar['dadabik_validate_attendance_not_valid'] = 'For this student and this class, the adddendance on the same date has already been  registered.';

if (isset($_GET['tablename']) && $_GET['tablename'] === 'students'){
    $normal_messages_ar['record_inserted'] = 'Student registered. The corresponding application user has been also created; if email sending is enabled, an email containing username and temporary password has been sent to the student.';
}

if (isset($_GET['tablename']) && $_GET['tablename'] === 'teachers'){
    $normal_messages_ar['record_inserted'] = 'Teacher registered. The corresponding application user has been also created; if email sending is enabled, an email containing username and temporary password has been sent to the teacher.';
    }
