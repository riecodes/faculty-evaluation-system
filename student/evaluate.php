<?php
function ordinal_suffix($num)
{
	$num = $num % 100; // protect against large numbers
	if ($num < 11 || $num > 13) {
		switch ($num % 10) {
			case 1:
				return $num . 'st';
			case 2:
				return $num . 'nd';
			case 3:
				return $num . 'rd';
		}
	}
	return $num . 'th';
}

// Check if student is irregular
$student_type = $conn->query("SELECT type FROM student_list WHERE id = {$_SESSION['login_id']}")->fetch_assoc()['type'];
$is_irregular = ($student_type == 'irregular');

$rid = '';
$faculty_id = '';
$subject_id = '';
$class_id = '';

if (isset($_GET['rid']))
	$rid = $_GET['rid'];
if (isset($_GET['fid']))
	$faculty_id = $_GET['fid'];
if (isset($_GET['sid']))
	$subject_id = $_GET['sid'];
if (isset($_GET['cid']))
	$class_id = $_GET['cid'];

// Get restrictions based on student type
if ($is_irregular) {
    // For irregular students, get all active faculty and subjects
    $faculty_list = $conn->query("SELECT id, avatar, concat(firstname,' ',lastname) as faculty FROM faculty_list WHERE status = 1 ORDER BY lastname, firstname");
    $subject_list = $conn->query("SELECT id, code, subject FROM subject_list ORDER BY code");
    $class_list = $conn->query("SELECT id, concat(curriculum,' ',level,' - ',section) as class_name FROM class_list ORDER BY curriculum, level, section");
} else {
    // For regular students, get restrictions as before
    $restriction = $conn->query("SELECT r.id,s.id as sid,f.id as fid,f.avatar,concat(f.firstname,' ',f.lastname) as faculty,s.code,s.subject 
        FROM restriction_list r 
        inner join faculty_list f on f.id = r.faculty_id 
        inner join subject_list s on s.id = r.subject_id 
        where academic_id ={$_SESSION['academic']['id']} 
        and class_id = {$_SESSION['login_class_id']} 
        and r.id not in (
            SELECT restriction_id from evaluation_list 
            where academic_id ={$_SESSION['academic']['id']} 
            and student_id = {$_SESSION['login_id']} 
        )");
}
?>

<!-- Owl Carousel CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">

<style>
.faculty-card {
    transition: all 0.3s ease;
    cursor: pointer;
}
.faculty-card:not(.active):hover {
    opacity: 0.7;
    transform: scale(0.98);
}
.faculty-card.active {
    opacity: 1;
    transform: scale(1);
    background-color: #183f74;
}
.faculty-card.active a,
.faculty-card.active h6,
.faculty-card.active small {
    color: white !important;
}
.faculty-card.active small.text-muted {
    color: rgba(255,255,255,0.8) !important;
}
</style>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<div class="col-lg-12">
	<div class="row d-flex flex-column justify-content-center align-items-center">
		<div class="col-md-9">
            <?php if ($is_irregular): ?>
            <!-- Irregular Student Selection Form -->
            <div class="card card-outline card-info mb-4">
                <div class="card-header">
                    <b>Select Faculty, Subject, and Section</b>
                </div>
                <div class="card-body">
                    <form id="irregular-selection">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="faculty">Faculty</label>
                                    <select class="form-control" id="faculty" name="faculty_id" required>
                                        <option value="">Select Faculty</option>
                                        <?php while($row = $faculty_list->fetch_assoc()): ?>
                                            <option value="<?php echo $row['id'] ?>" <?php echo $faculty_id == $row['id'] ? 'selected' : '' ?>>
                                                <?php echo ucwords($row['faculty']) ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="subject">Subject</label>
                                    <select class="form-control" id="subject" name="subject_id" required>
                                        <option value="">Select Subject</option>
                                        <?php while($row = $subject_list->fetch_assoc()): ?>
                                            <option value="<?php echo $row['id'] ?>" <?php echo $subject_id == $row['id'] ? 'selected' : '' ?>>
                                                <?php echo $row['code'] . ' - ' . $row['subject'] ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="class">Section</label>
                                    <select class="form-control" id="class" name="class_id" required>
                                        <option value="">Select Section</option>
                                        <?php while($row = $class_list->fetch_assoc()): ?>
                                            <option value="<?php echo $row['id'] ?>" <?php echo $class_id == $row['id'] ? 'selected' : '' ?>>
                                                <?php echo $row['class_name'] ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary">Proceed to Evaluation</button>
                        </div>
                    </form>
                </div>
            </div>
            <?php else: ?>
            <!-- Regular Student Faculty Carousel -->
			<div class="owl-carousel owl-theme faculty-carousel">
				<?php
				while ($row = $restriction->fetch_array()):
					if (empty($rid)) {
						$rid = $row['id'];
						$faculty_id = $row['fid'];
						$subject_id = $row['sid'];
					}
					?>
					<div class="item">
						<div class="card faculty-card <?php echo isset($rid) && $rid == $row['id'] ? 'active' : '' ?>">
						<a class="text-decoration-none" href="./index.php?page=evaluate&rid=<?php echo $row['id'] ?>&sid=<?php echo $row['sid'] ?>&fid=<?php echo $row['fid'] ?>">
							<div class="card-body p-2">
								<div class="text-center mb-2">
									<img src="assets/uploads/<?php echo $row['avatar'] ?>" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover;" onerror="this.src='assets/uploads/no-image-available.png'">
								</div>
								
									<h6 class="mb-1"><?php echo ucwords($row['faculty']) ?></h6>
									<small class="text-muted"><?php echo $row["code"] ?></small><br>
									<small class="text-muted"><?php echo $row['subject'] ?></small>
								
							</div>
						</a>
						</div>
					</div>
				<?php endwhile; ?>
			</div>
            <?php endif; ?>
		</div>
		<div class="col-md-9">
			<div class="card card-outline card-info">
				<div class="card-header">
					<b>Evaluation Questionnaire for Academic:
						<?php echo $_SESSION['academic']['year'] . ' ' . (ordinal_suffix($_SESSION['academic']['semester'])) ?>
					</b>
					<div class="card-tools">
						<button class="btn btn-sm btn-primary mx-1" form="manage-evaluation">Submit
							Evaluation</button>
					</div>
				</div>
				<div class="card-body">
					<fieldset class="border border-info p-2 w-100">
						<legend class="w-auto">Rating Legend</legend>
						<div class="row justify-content-center">
							<div class="col-md-10">
								<div class="table-responsive">
									<table class="table table-bordered mb-0">
										<thead class="bg-light">
											<tr class="text-center">
												<th width="20%">Rating</th>
												<th width="80%">Description</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td class="text-center font-weight-bold">5</td>
												<td>Strongly Agree</td>
											</tr>
											<tr>
												<td class="text-center font-weight-bold">4</td>
												<td>Agree</td>
											</tr>
											<tr>
												<td class="text-center font-weight-bold">3</td>
												<td>Uncertain</td>
											</tr>
											<tr>
												<td class="text-center font-weight-bold">2</td>
												<td>Disagree</td>
											</tr>
											<tr>
												<td class="text-center font-weight-bold">1</td>
												<td>Strongly Disagree</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</fieldset>
					<form id="manage-evaluation">
						<input type="hidden" name="class_id" value="<?php echo $is_irregular ? $class_id : $_SESSION['login_class_id'] ?>">
						<input type="hidden" name="faculty_id" value="<?php echo $faculty_id ?>">
						<input type="hidden" name="restriction_id" value="<?php echo $rid ?>">
						<input type="hidden" name="subject_id" value="<?php echo $subject_id ?>">
						<input type="hidden" name="academic_id" value="<?php echo $_SESSION['academic']['id'] ?>">
						<div class="clear-fix mt-2"></div>
						<?php
						$q_arr = array();
						$criteria = $conn->query("SELECT * FROM criteria_list where id in (SELECT criteria_id FROM question_list where academic_id = {$_SESSION['academic']['id']} ) order by abs(order_by) asc ");
						while ($crow = $criteria->fetch_assoc()):
							?>
							<table class="table table-condensed">
								<thead>
									<tr class="bg-gradient-secondary">
										<th class=" p-1"><b><?php echo $crow['criteria'] ?></b></th>
										<th class="text-center">1</th>
										<th class="text-center">2</th>
										<th class="text-center">3</th>
										<th class="text-center">4</th>
										<th class="text-center">5</th>
									</tr>
								</thead>
								<tbody class="tr-sortable">
									<?php
									$questions = $conn->query("SELECT * FROM question_list where criteria_id = {$crow['id']} and academic_id = {$_SESSION['academic']['id']} order by abs(order_by) asc ");
									while ($row = $questions->fetch_assoc()):
										$q_arr[$row['id']] = $row;
										?>
										<tr class="bg-white">
											<td class="p-1" width="40%">
												<?php echo $row['question'] ?>
												<input type="hidden" name="qid[]" value="<?php echo $row['id'] ?>">
											</td>
											<?php if($row['type'] == 'checklist'): ?>
												<?php for ($c = 1; $c <= 5; $c++): ?>
													<td class="text-center">
														<div class="icheck-success d-inline">
															<input type="radio" name="rate[<?php echo $row['id'] ?>]" <?php echo $c == 5 ? "checked" : '' ?> id="qradio<?php echo $row['id'] . '_' . $c ?>"
																value="<?php echo $c ?>">
															<label for="qradio<?php echo $row['id'] . '_' . $c ?>">
															</label>
														</div>
													</td>
												<?php endfor; ?>
											<?php else: ?>
												<td colspan="5">
													<textarea name="rate[<?php echo $row['id'] ?>]" class="form-control" rows="2" required></textarea>
												</td>
											<?php endif; ?>
										</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						<?php endwhile; ?>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function () {
		<?php if (!$is_irregular): ?>
		// Initialize Owl Carousel for regular students
		var totalItems = $('.faculty-carousel .item').length;
		var itemsToShow = Math.min(totalItems, 3);
		
		$('.faculty-carousel').owlCarousel({
			items: itemsToShow,
			loop: totalItems > 3,
			margin: 10,
			nav: totalItems > itemsToShow,
			dots: true,
			autoplay: totalItems > itemsToShow,
			autoplayTimeout: 5000,
			autoplayHoverPause: true,
			smartSpeed: 1000,
			navText: ['<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>'],
			responsive: {
				0: {
					items: Math.min(totalItems, 1)
				},
				600: {
					items: Math.min(totalItems, 2)
				},
				1000: {
					items: Math.min(totalItems, 3)
				}
			}
		});
		<?php endif; ?>

		// Handle irregular student selection form
		$('#irregular-selection').submit(function(e) {
			e.preventDefault();
			var faculty = $('#faculty').val();
			var subject = $('#subject').val();
			var class_id = $('#class').val();
			
			if (faculty && subject && class_id) {
				window.location.href = './index.php?page=evaluate&fid=' + faculty + '&sid=' + subject + '&cid=' + class_id;
			} else {
				alert_toast("Please select all required fields", "error");
			}
		});

		if ('<?php echo $_SESSION['academic']['status'] ?>' == 0) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>not_started.php")
		} else if ('<?php echo $_SESSION['academic']['status'] ?>' == 2) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>closed.php")
		}
		else if ('<?php echo $_SESSION['academic']['status'] ?>' == 2) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>closed.php")
		}
		if (<?php echo empty($rid) && !$is_irregular ? 1 : 0 ?> == 1)
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>done.php")
	})
	
	$('#manage-evaluation').submit(function(e) {
		e.preventDefault();
		start_load();
		
		// Validate form
		var isValid = true;
		$(this).find('textarea[required]').each(function() {
			if (!$(this).val().trim()) {
				isValid = false;
				return false;
			}
		});
		
		if (!isValid) {
			alert_toast("Please fill in all required fields", "error");
			end_load();
			return false;
		}
		
		$.ajax({
			url: 'ajax.php?action=save_evaluation',
			method: 'POST',
			data: $(this).serialize(),
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Evaluation submitted successfully.", "success");
					setTimeout(function () {
						location.reload();
					}, 1750);
				} else {
					alert_toast("Error: " + resp, "error");
					end_load();
				}
			},
			error: function(xhr, status, error) {
				alert_toast("Error submitting evaluation. Please try again.", "error");
				end_load();
			}
		});
	});
</script>