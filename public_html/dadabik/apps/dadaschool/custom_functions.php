<?php
/*
***********************************************************************************
DaDaBIK (DaDaBIK is a DataBase Interfaces Kreator) https://dadabik.com/
Copyright (C) 2001-2026 Eugenio Tacchini

This program is distributed "as is" and WITHOUT ANY WARRANTY, either expressed or implied, without even the implied warranties of merchantability or fitness for a particular purpose.

This program is distributed under the terms of the DaDaBIK license, which is included in this package (see dadabik_license.txt). For all the details see dadabik_license.txt.

If you are unsure about what you are allowed to do with this license, feel free to contact info@dadabik.com
***********************************************************************************
*/
?>
<?php
$hooks['grades_with_teacher']['resultsgrid_header']['before'] = 'dadabik_redirect_grades_with_teacher';

function dadabik_redirect_grades_with_teacher()
{

    header('location:index.php?function=search&tablename=grades');
    exit;

}

//$hooks['login_form']['before'] = 'dadabik_login_form_before';

function dadabik_login_form_before(){
    echo '<p>This application has three <b>final user</b> groups: Office Staff, Teachers and Student.<br>Here are some test users for each group.<br> <br><b>Office staff group</b><br>username: bob<br>password: the password you chose<br><b>Teachers group</b><br>username: jane.doe<br>password: the password you chose<br><b>Students group</b><br>username: john.doe<br>password: the password you chose<br><br>If instead you want to customize the application, login as username: root, password: the password you chose ';
}


$hooks['students']['insert']['after'] = 'dadabik_students_teachers_after_insert';
$hooks['teachers']['insert']['after'] = 'dadabik_students_teachers_after_insert';

function dadabik_students_teachers_after_insert($id)
{
    global $users_table_name;

    if (isset($_GET['tablename'])){
        switch ($_GET['tablename']){
            case 'students':
                $table_to_use = 'students';
                $suffix = 's';
                $group_to_use = '4';
                break;
            case 'teachers':
                $table_to_use = 'teachers';
                $suffix = 't';
                $group_to_use = '5';
                break;
            default:
                die('Unexpected error');
                break;
        }
        $table_name = $_GET['tablename'];
    }
    else{
        die('Unexpected error');
    }

    // get the info of the student / teacher just created
    $person_info = ddb_api::get_record_details($table_to_use, 'id', $id);

    // we need to create the corresponding user in the application, having as username first.last
    $username_user = $person_info['first_name'].'.'.$person_info['last_name'];

    // check if a user with the same username already exist
    $count = ddb_api::count_records($users_table_name, 'username_user', $username_user);

    // if yes, add "s" or "t" and the id of the student to try to guarantee uniqueness and avoid creation errors
    if ($count > 0){
        $username_user .= '.'.$suffix.$id;
    }

    // let's create a random temporary password
    if (function_exists('random_bytes')){
        $password_user = bin2hex(random_bytes(8));
    }
    else{
        $password_user = bin2hex(openssl_random_pseudo_bytes(8));
    }

    $password_user_encrypted = create_password_hash($password_user);
    if (strlen_custom($password_user_encrypted) < 20){
        echo 'Error';
        exit();
    }

    // create the record
    ddb_api::insert_record($users_table_name, ['username_user', 'password_user', 'id_group', 'email_user', 'confirmed_timestamp_user'], [$username_user, $password_user_encrypted, $group_to_use, $person_info['email'], time()]);

    // update the student's record with the username
    ddb_api::update_records($table_to_use, 'id', $id, ['username_user'], [$username_user]);

    if (SEND_EMAIL_STUDENT_TEACHER_CREATION === 1){

        mail_custom($person_info['email'], 'Your Account has been created', "Hello,\nan account has been created.\n\nUsername: ".$username_user."\n\nTemporary Passowrd: ".$password_user);
    }

}


// filter grades, each student can see only their grades
$custom_filters['grades'] = 'dadabik_custom_filter_grades';

function dadabik_custom_filter_grades()
{
    global $current_user, $current_id_group;

    if ($current_id_group == 4){

        $student_info = ddb_api::get_record_details('students', 'username_user', $current_user);
        $filter = "grades.student_id = '".$student_info['id']."'";
    }
    elseif ($current_id_group == 5){

        $teacher_info = ddb_api::get_record_details('teachers', 'username_user', $current_user);
        $filter = "(select teacher_id from classes where grades.class_id = classes.id) = '".$teacher_info['id']."'";
    }
    else{
        $filter = "1=1";
    }

    return $filter;
}



// filter attendance, each student can see only their records
$custom_filters['attendance'] = 'dadabik_custom_filter_attendance';

function dadabik_custom_filter_attendance()
{
    global $current_user, $current_id_group;

    if ($current_id_group == 4){

        $student_info = ddb_api::get_record_details('students', 'username_user', $current_user);
        $filter = "attendance.student_id = '".$student_info['id']."'";
    }
    elseif ($current_id_group == 5){

        $teacher_info = ddb_api::get_record_details('teachers', 'username_user', $current_user);
        $filter = "(select teacher_id from classes where attendance.class_id = classes.id) = '".$teacher_info['id']."'";
    }
    else{
        $filter = "1=1";
    }

    return $filter;
}


// filter classes, each teacher can see only their classes
$custom_filters['classes'] = 'dadabik_custom_filter_classes';

function dadabik_custom_filter_classes()
{
    global $current_user, $current_id_group;

    if ($current_id_group == 5){

        $teacher_info = ddb_api::get_record_details('teachers', 'username_user', $current_user);
        $filter = "classes.teacher_id = '".$teacher_info['id']."'";
    }
    elseif ($current_id_group == 4){

        $student_info = ddb_api::get_record_details('students', 'username_user', $current_user);
        $filter = $student_info['id']." IN (select student_id from enrollments where class_id = classes.id)";
    }
    else{
        $filter = "1=1";
    }

    return $filter;
}

// filter enrollments, each student can see only their records
$custom_filters['enrollments'] = 'dadabik_custom_filter_enrollments';

function dadabik_custom_filter_enrollments()
{
    global $current_user, $current_id_group;

    if ($current_id_group == 4){

        $student_info = ddb_api::get_record_details('students', 'username_user', $current_user);
        $filter = "enrollments.student_id = '".$student_info['id']."'";
    }
    else{
        $filter = "1=1";
    }

    return $filter;
}

$custom_startup_function = 'dadabik_startup';
function dadabik_startup()
{
    global $enable_report_generation, $enable_pivot_generation;

    $enable_report_generation = 0;
    $enable_pivot_generation = 0;

    if (isset($_SESSION['logged_user_infos_ar']['id_group']) && ($_SESSION['logged_user_infos_ar']['id_group'] == 1 || $_SESSION['logged_user_infos_ar']['id_group'] == 3)){
        $enable_report_generation = 1;
        $enable_pivot_generation = 1;

    }
}

function set_standard_pdf_parameters_custom($pdf)
{
    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 50);
}


$custom_buttons['students'][$cnt]['type'] = 'php_standard';
$custom_buttons['students'][$cnt]['callback_function'] = 'dadabik_generate_student_transcript';
$custom_buttons['students'][$cnt]['permission_needed'] = 'insert';
$custom_buttons['students'][$cnt]['show_in'][] = 'results_grid';
$custom_buttons['students'][$cnt]['position_form'] = 'row';
$custom_buttons['students'][$cnt]['label_type'] = 'fixed';
$custom_buttons['students'][$cnt]['label'] = 'Transcript';
$custom_buttons['students'][$cnt]['style'] = '';
$custom_buttons['students'][$cnt]['confirmation_message'] = 'Please confirm before generating the transcript:\n\n1- Ensure no one is currently editing, adding, or deleting grades for this student, as it may affect the GPA calculation.\n\n2- Always generate a new transcript using this button. Do not reuse the result PDF URL, as it may result in wrong information.';
$cnt++;

function dadabik_generate_student_transcript($table_name, $where_field, $where_value)
{

   $_SESSION['where_clause_grades_with_teacher'] = "`grades_with_teacher`.`student_id` = '".(int)$where_value."'";
   $_SESSION['advanced_filters_ar_grades_with_teacher']["select_types"]['student_id'] = 'is_equal';
   $_SESSION['advanced_filters_ar_grades_with_teacher']["values"]['student_id'] = (int)$where_value;
   header('location:index.php?tablename=grades_with_teacher&function=search&export_to_pdf=1&id_student='.(int)$where_value.'&pdf_template=transcript');
  exit;
}

function dadabik_validate_enrollment ($params){

    global $conn;

    $sql = "SELECT COUNT(*) from enrollments WHERE student_id = :student_id AND class_id = :class_id";
    $res_prepare = prepare_db($conn, $sql);

    $res_bind = bind_param_db($res_prepare, ':student_id', $params['student_id']);
    $res_bind = bind_param_db($res_prepare, ':class_id', $params['class_id']);
    $res = execute_prepared_db($res_prepare,0);

    $row = fetch_row_db($res_prepare);

    if ( $row[0] > 0){
        return false;
    }
    else{
        return true;
    }

}

function dadabik_validate_grade ($params){

    global $conn;

    $sql = "SELECT COUNT(*) from grades WHERE student_id = :student_id AND class_id = :class_id and date = :date";
    $res_prepare = prepare_db($conn, $sql);

    $res_bind = bind_param_db($res_prepare, ':student_id', $params['student_id']);
    $res_bind = bind_param_db($res_prepare, ':class_id', $params['class_id']);
    $res_bind = bind_param_db($res_prepare, ':date', $params['date']);
    $res = execute_prepared_db($res_prepare,0);

    $row = fetch_row_db($res_prepare);

    if ( $row[0] > 0){
        return false;
    }
    else{
        return true;
    }

}

function dadabik_validate_attendance ($params){

    global $conn;

    $sql = "SELECT COUNT(*) from attendance WHERE student_id = :student_id AND class_id = :class_id and date = :date";
    $res_prepare = prepare_db($conn, $sql);

    $res_bind = bind_param_db($res_prepare, ':student_id', $params['student_id']);
    $res_bind = bind_param_db($res_prepare, ':class_id', $params['class_id']);
    $res_bind = bind_param_db($res_prepare, ':date', $params['date']);
    $res = execute_prepared_db($res_prepare,0);

    $row = fetch_row_db($res_prepare);

    if ( $row[0] > 0){
        return false;
    }
    else{
        return true;
    }

}
