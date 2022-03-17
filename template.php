<html lang="en">
<body>

<div class="splash"><h1>Arcada Attendance</h1></div>

<div class="register">
    <div>
        <h2>Register for <?= $template['courseID']?> :: <?= date("Y-m-d")?></h2>
        <form method="post">

            <button type="submit" name="submitAttendance">
                Register attendance
            </button>
        </form>
    </div>

</div>


</body>
</html>


<?php

if(isset($_POST['submitAttendance'])){

}
