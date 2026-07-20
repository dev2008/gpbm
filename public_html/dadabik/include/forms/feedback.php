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
<p><h1>

<?php if (isset($_GET['show_skip']) && $_GET['show_skip'] === '1') { ?>

TAKE a break ​🤓​&nbsp;

<?php } ?> 


Your Feedback is very important to us</h1>

<?php if ($function === 'show_feedback') { ?>

<p>Tell us one thing you like and one thing you don't like about DaDaBIK.

<form method="POST" action="admin.php?function=show_feedback_2">

<p><strong>What I like about DaDaBIK:</strong><br>
<textarea required name="like" cols="80" rows="8"></textarea>

<p><strong>What I don't like about DaDaBIK:</strong><br>

<textarea required name="dont_like" cols="80" rows="8"></textarea><br>

<input type="submit"  class="btn btn-primary" value="Send">

<?php if (isset($_GET['show_skip']) && $_GET['show_skip'] === '1') { ?>

<p>If you don't want to send feedback or you have already sent it for other DaDaBIK applications, <a href="admin.php?function=send_feedback&skip=1">SKIP</a>

<?php } ?> 



</form>
<?php } elseif ($function === 'show_feedback_2') { 

$_SESSION['like'] = $_POST['like'];
$_SESSION['dont_like'] = $_POST['dont_like'];

?>

<p>Almost done. Also add your email, we will use it to reply to you.

<form method="POST" action="admin.php?function=send_feedback">

<p><strong>email address:</strong><br> <input type="email" required name="email">

<input type="submit" class="btn btn-primary" value="Send">

</form>

<?php } elseif ($function === 'send_feedback' & isset($_GET['skip']) && $_GET['skip'] === '1') { ?>

<p>Thanks anyway!


<?php } elseif ($function === 'send_feedback' & !isset($_GET['skip']) || $_GET['skip'] !== '1') { ?>

<p>Thanks for your feedback!

<?php }  ?>



