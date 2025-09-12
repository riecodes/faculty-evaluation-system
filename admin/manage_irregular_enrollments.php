<?php
include 'includes/functions.php';
?>

<style>
    .text-success,
    .border-success {
        color: #417029 !important;
        border-color: #417029 !important;
    }

    .bg-primary {
        background-color: #183f74 !important;
    }

    .text-primary {
        color: #183f74 !important;
    }

    .list-group-item.active {
        background-color: #183f74 !important;
        border-color: #183f74 !important;
    }

    .list-group-item.active:hover {
        color: #fff !important;
    }

    .list-group-item:hover {
        color: #183f74 !important;
        font-weight: 600 !important;
    }

    .callout.callout-info {
        border-left-color: #183f74 !important;
    }

    .bg-gradient-secondary {
        background: #6c757d linear-gradient(180deg, #828a91, #6c757d) repeat-x !important;
        color: #fff;
    }

    .btn-print {
        background-color: #417029 !important;
        border-color: #417029 !important;
    }

    .btn-print:hover {
        background-color: #345821 !important;
        border-color: #345821 !important;
    }
</style>

<div class="container-fluid px-4">
    <?php if (isset($_GET['status']) && isset($_GET['message'])): ?>
        <div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger' ?> alert-dismissible" id="status-alert">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <?php echo htmlspecialchars($_GET['message']); ?>
        </div>
        <script>
            $(document).ready(function () {
                var url = new URL(window.location.href);
                url.searchParams.delete('status');
                url.searchParams.delete('message');
                window.history.replaceState({}, '', url);
            });
        </script>
    <?php endif; ?>

    <div class="card shadow-sm border mb-4">
        <div class="shadow-sm container-fluid px-4 p-3 mb-4 bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">Manage Irregular Student Enrollments</h5>
            <button class="btn btn-primary border-white" id="new_enrollment">
                <i class="fas fa-plus"></i> New Enrollment
            </button>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="enrollment-list">
                    <thead class="bg-light">
                        <tr>
                            <th>Student</th>
                            <th>Subject</th>
                            <th>Section</th>
                            <th>Faculty</th>
                            <th>Academic Year</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $enrollments = $conn->query("SELECT e.*, 
                            CONCAT(s.firstname, ' ', s.lastname) as student_name,
                            sub.code as subject_code, sub.subject as subject_name,
                            CONCAT(c.curriculum, ' ', c.level, ' - ', c.section) as class_name,
                            CONCAT(f.firstname, ' ', f.lastname) as faculty_name,
                            a.year, a.semester
                            FROM irregular_enrollments e
                            INNER JOIN student_list s ON s.id = e.student_id
                            INNER JOIN subject_list sub ON sub.id = e.subject_id
                            INNER JOIN class_list c ON c.id = e.class_id
                            INNER JOIN faculty_list f ON f.id = e.faculty_id
                            INNER JOIN academic_list a ON a.id = e.academic_id
                            ORDER BY s.lastname, s.firstname");
                        while ($row = $enrollments->fetch_assoc()):
                            $academic_year = $row['year'] . ' ' . ordinal_suffix($row['semester']);
                        ?>
                            <tr>
                                <td><?php echo $row['student_name'] ?></td>
                                <td><?php echo $row['subject_code'] . ' - ' . $row['subject_name'] ?></td>
                                <td><?php echo $row['class_name'] ?></td>
                                <td><?php echo $row['faculty_name'] ?></td>
                                <td><?php echo $academic_year ?></td>
                                <td class="text-center">
                                    <button class="btn btn-flat btn-default btn-sm edit_enrollment" data-id="<?php echo $row['id'] ?>">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-flat btn-danger btn-sm delete_enrollment" data-id="<?php echo $row['id'] ?>">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Enrollment Modal -->
<div class="modal fade" id="enrollment_modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add New Enrollment</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="enrollment_form">
                <div class="modal-body">
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label>Student</label>
                        <select class="form-control select2" name="student_id" required>
                            <option value="">Select Student</option>
                            <?php
                            $students = $conn->query("SELECT * FROM student_list WHERE type = 'irregular' ORDER BY lastname, firstname");
                            while ($row = $students->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['id'] ?>">
                                    <?php echo $row['lastname'] . ', ' . $row['firstname'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Subject</label>
                        <select class="form-control select2" name="subject_id" required>
                            <option value="">Select Subject</option>
                            <?php
                            $subjects = $conn->query("SELECT * FROM subject_list ORDER BY code");
                            while ($row = $subjects->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['id'] ?>">
                                    <?php echo $row['code'] . ' - ' . $row['subject'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Section</label>
                        <select class="form-control select2" name="class_id" required>
                            <option value="">Select Section</option>
                            <?php
                            $classes = $conn->query("SELECT * FROM class_list ORDER BY curriculum, level, section");
                            while ($row = $classes->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['id'] ?>">
                                    <?php echo $row['curriculum'] . ' ' . $row['level'] . ' - ' . $row['section'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Faculty</label>
                        <select class="form-control select2" name="faculty_id" required>
                            <option value="">Select Faculty</option>
                            <?php
                            $faculty = $conn->query("SELECT * FROM faculty_list WHERE status = 1 ORDER BY lastname, firstname");
                            while ($row = $faculty->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['id'] ?>">
                                    <?php echo $row['lastname'] . ', ' . $row['firstname'] ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Academic Year</label>
                        <select class="form-control select2" name="academic_id" required>
                            <option value="">Select Academic Year</option>
                            <?php
                            $academic = $conn->query("SELECT * FROM academic_list WHERE status = 1 ORDER BY year DESC, semester DESC");
                            while ($row = $academic->fetch_assoc()):
                            ?>
                                <option value="<?php echo $row['id'] ?>">
                                    <?php echo $row['year'] . ' ' . ordinal_suffix($row['semester']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
$(document).ready(function() {
    $('.select2').select2({
        width: '100%'
    });

    $('#new_enrollment').click(function() {
        $('#enrollment_modal .modal-title').text('Add New Enrollment');
        $('#enrollment_form')[0].reset();
        $('#enrollment_form [name="id"]').val('');
        $('#enrollment_modal').modal('show');
    });

    $('#enrollment_form').submit(function(e) {
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_enrollment',
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(resp) {
                if(resp.status == 1) {
                    $('#enrollment_modal').modal('hide');
                    alert_toast(resp.message || "Enrollment successfully saved", "success");
                    setTimeout(function() {
                        location.reload();
                    }, 1500);
                } else {
                    alert_toast("Error: " + (resp.error || 'Failed to save enrollment'), "error");
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                alert_toast("Error: " + error, "error");
            },
            complete: function() {
                end_load();
            }
        });
    });

    $('.delete_enrollment').click(function() {
        var id = $(this).data('id');
        if(confirm('Are you sure you want to delete this enrollment?')) {
            start_load();
            $.ajax({
                url: 'ajax.php?action=delete_enrollment',
                method: 'POST',
                data: {id: id},
                dataType: 'json',
                success: function(resp) {
                    if(resp == 1) {
                        alert_toast("Enrollment successfully deleted", "success");
                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    } else {
                        alert_toast("Error: " + (typeof resp === 'string' ? resp : 'Failed to delete enrollment'), "error");
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    console.error('Status:', status);
                    console.error('Response:', xhr.responseText);
                    alert_toast("Error: " + error, "error");
                },
                complete: function() {
                    end_load();
                }
            });
        }
    });

    $('.edit_enrollment').click(function() {
        var id = $(this).data('id');
        start_load();
        $.ajax({
            url: 'ajax.php?action=get_enrollment',
            method: 'POST',
            data: {id: id},
            dataType: 'json',
            success: function(resp) {
                if(resp.status == 1) {
                    $('#enrollment_modal .modal-title').text('Edit Enrollment');
                    $('#enrollment_form [name="id"]').val(resp.data.id);
                    $('#enrollment_form [name="student_id"]').val(resp.data.student_id).trigger('change');
                    $('#enrollment_form [name="subject_id"]').val(resp.data.subject_id).trigger('change');
                    $('#enrollment_form [name="class_id"]').val(resp.data.class_id).trigger('change');
                    $('#enrollment_form [name="faculty_id"]').val(resp.data.faculty_id).trigger('change');
                    $('#enrollment_form [name="academic_id"]').val(resp.data.academic_id).trigger('change');
                    $('#enrollment_modal').modal('show');
                } else {
                    alert_toast("Error: " + (resp.message || 'Failed to load enrollment data'), "error");
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
                console.error('Status:', status);
                console.error('Response:', xhr.responseText);
                alert_toast("Error: " + error, "error");
            },
            complete: function() {
                end_load();
            }
        });
    });
});
</script> 