<html lang="en">
<link rel="stylesheet" href="./media/style.css" type="text/css">
<body>

<div class="splash"><h1>Arcada Attendance</h1></div>

<section class="flex">
    <div>
        <!--Show user name when he visits the site-->
        <h2>Hello <?= $template['displayName'] ?></h2>
        <!--Check if user is already registered-->
        <?php if (!$template['isRegistered']) : ?>
            <!--Show user a button to register for the supplied course-->
            <h2>Register to "<?= $template['courses'][$template['course']] ?>" <?= date("Y-m-d") ?> Lecture</h2>
            <form method="POST">
                <input class="button" type="submit" name="submitAttendance" value="Register">
            </form>
        <?php else : ?>
            <!--If user has already registered for todays lecture inform him of when he did-->
            <h2>You registered for todays <?= $template['courses'][$template['isRegistered']['course']] ?> lecture
                at <?= $template['isRegistered']['date'] ?></h2>
        <?php endif; ?>
    </div>
</section>

</body>
</html>


<?php

