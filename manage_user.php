<?php
include('db_connect.php');
session_start();
if (isset($_GET['id'])) {
    $type = array("", "users", "faculty_list", "student_list");
    $user = $conn->query("SELECT * FROM {$type[$_SESSION['login_type']]} where id =" . $_GET['id']);
    foreach ($user->fetch_array() as $k => $v) {
        $meta[$k] = $v;
    }
}
?>
<div class="container-fluid">
    <!-- Debug Box -->
    <div id="debug-box" class="alert alert-info" style="display:none;">
        <h5>Debug Information</h5>
        <div id="debug-content"></div>
    </div>

    <div id="msg"></div>

    <form action="" id="manage-user">
        <input type="hidden" name="id" value="<?php echo isset($meta['id']) ? $meta['id'] : '' ?>">
        <div class="form-group">
            <label for="name">First Name</label>
            <input type="text" name="firstname" id="firstname" class="form-control"
                value="<?php echo isset($meta['firstname']) ? $meta['firstname'] : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="name">Last Name</label>
            <input type="text" name="lastname" id="lastname" class="form-control"
                value="<?php echo isset($meta['lastname']) ? $meta['lastname'] : '' ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="text" name="email" id="email" class="form-control"
                value="<?php echo isset($meta['email']) ? $meta['email'] : '' ?>" required autocomplete="off">
        </div>
        <?php if($_SESSION['login_type'] == 3): // If user is a student 
            $student_info = $conn->query("SELECT type FROM student_list WHERE id = " . $_GET['id'])->fetch_assoc();
            if($student_info['type'] == 'regular'): // Only show class selection for regular students
        ?>
        <div class="form-group">
            <label for="class_id">Class</label>
            <select name="class_id" id="class_id" class="form-control select2">
                <option value="">Select Class</option>
                <?php 
                $classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as class FROM class_list order by curriculum,level,section");
                while($row = $classes->fetch_assoc()):
                ?>
                <option value="<?php echo $row['id'] ?>" <?php echo isset($meta['class_id']) && $meta['class_id'] == $row['id'] ? "selected" : "" ?>><?php echo $row['class'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <?php 
            endif;
        endif; 
        ?>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" name="password" id="password" class="form-control" value="" autocomplete="off">
            <small><i>Leave this blank if you dont want to change the password.</i></small>
        </div>
        <div class="form-group">
            <label for="" class="control-label">Avatar</label>
            <div class="custom-file">
                <input type="file" class="custom-file-input rounded-circle" id="customFile" name="img"
                    onchange="displayImg(this,$(this))">
                <label class="custom-file-label" for="customFile">Choose file</label>
            </div>
        </div>
        <div class="form-group d-flex justify-content-center">
            <img src="<?php echo isset($meta['avatar']) ? 'assets/uploads/' . $meta['avatar'] : '' ?>" alt="" id="cimg"
                class="img-fluid img-thumbnail">
        </div>
    </form>
</div>
<style>
    img#cimg {
        height: 15vh;
        width: 15vh;
        object-fit: cover;
        border-radius: 100% 100%;
    }
    #debug-box {
        margin-bottom: 20px;
        padding: 15px;
        border-radius: 5px;
    }
    #debug-content {
        font-family: monospace;
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>
<script>
    function displayImg(input, _this) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#cimg').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function showDebug(message, type = 'info') {
        $('#debug-box').removeClass('alert-info alert-success alert-danger')
            .addClass('alert-' + type)
            .show();
        $('#debug-content').html(message);
    }

    $('#manage-user').submit(function (e) {
        e.preventDefault();
        start_load();
        
        // Show form data in debug box
        let formData = new FormData(this);
        let debugInfo = "Form Data:\n";
        for (let pair of formData.entries()) {
            if (pair[0] !== 'password') { // Don't show password in debug
                debugInfo += pair[0] + ': ' + pair[1] + '\n';
            }
        }
        showDebug(debugInfo);

        $.ajax({
            url: 'ajax.php?action=update_user',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function (resp) {
                if (resp == 1) {
                    showDebug("Operation successful!", 'success');
                    alert_toast("Data successfully saved", 'success');
                    setTimeout(function () {
                        location.reload();
                    }, 1500);
                } else if (resp == 2) {
                    showDebug("Error: Email already exists", 'danger');
                    $('#msg').html('<div class="alert alert-danger">Email already exists</div>');
                } else {
                    showDebug("Error: " + resp, 'danger');
                    $('#msg').html('<div class="alert alert-danger">Error saving data</div>');
                }
                end_load();
            },
            error: function(err) {
                showDebug("AJAX Error:\n" + JSON.stringify(err, null, 2), 'danger');
                $('#msg').html('<div class="alert alert-danger">Error occurred</div>');
                end_load();
            }
        });
    });
</script>