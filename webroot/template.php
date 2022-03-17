<html lang="en">
<body>

<div class="splash"><h1>Arcada Attendance</h1></div>

<div class="register">
    <div>
        <h2>Hello <?= $template['displayName'] ?></h2>
        <?php if (!$template['isRegistered']) : ?>
            <h2>Register for <?= $template['course'] ?> :: <?= date("Y-m-d") ?></h2>
            <form method="POST">
                <input type="submit" name="submitAttendance" value="Register">
            </form>
        <?php else : ?>
            <h2>You registered for <?=$template['isRegistered']['course']?> lecture at <?=$template['isRegistered']['date'] ?></h2>
        <?php endif; ?>
    </div>

</div>


</body>
</html>


<?php

