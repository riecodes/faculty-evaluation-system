<?php
require_once 'includes/functions.php';

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
    // For irregular students, get all active faculty
    $faculty_list = $conn->query("SELECT id, avatar, concat(firstname,' ',lastname) as faculty 
        FROM faculty_list 
        WHERE status = 1 
        ORDER BY lastname, firstname");
} else {
    // For regular students, get restrictions as before
    $restriction = $conn->query("SELECT r.id, s.id as sid, f.id as fid, f.avatar, 
        concat(f.firstname,' ',f.lastname) as faculty, s.code, s.subject, r.type,
        concat(c.curriculum,' ',c.level,' - ',c.section) as class
        FROM restriction_list r 
        inner join faculty_list f on f.id = r.faculty_id 
        inner join subject_list s on s.id = r.subject_id 
        inner join class_list c on c.id = r.class_id
        where academic_id ={$_SESSION['academic']['id']} 
        and class_id = {$_SESSION['login_class_id']} 
        and r.type = 'regular'
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
.select2-container {
    width: 100% !important;
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
                                    <select class="form-control select2" id="faculty" name="faculty_id" required>
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
                                    <select class="form-control select2" id="subject" name="subject_id" required disabled>
                                        <option value="">Select Subject</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="class">Section</label>
                                    <select class="form-control select2" id="class" name="class_id" required disabled>
                                        <option value="">Select Section</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div id="selection-error" class="alert alert-danger mt-3" style="display: none;"></div>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-primary" id="proceed-btn" disabled>Proceed to Evaluation</button>
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
									<small class="text-muted"><?php echo ucfirst($row['type']) ?> - <?php echo $row["code"] ?> - <?php echo $row['subject'] ?></small><br>
									<small class="text-muted"><?php echo $row['class'] ?></small>
								
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
													<?php 
													// Debug output
													echo "<!-- Textfield for question ID: " . $row['id'] . " -->";
													?>
													<textarea name="rate[<?php echo $row['id'] ?>]" class="form-control" rows="2" required></textarea>
												</td>
											<?php endif; ?>
										</tr>
									<?php endwhile; ?>
								</tbody>
							</table>
						<?php endwhile; ?>
					</form>
					
					<!-- Debug Box -->
					<div id="debug-box" class="card mt-3" style="display: none;">
						<div class="card-header bg-dark text-white">
							<h5 class="mb-0">Debug Information</h5>
						</div>
						<div class="card-body">
							<pre id="debug-content" class="mb-0" style="white-space: pre-wrap;"></pre>
						</div>
					</div>
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
		<?php else: ?>
		// Initialize select2 for irregular students
		$('.select2').select2({
			placeholder: "Please select here",
			width: "100%"
		});

		// Handle faculty selection
		$('#faculty').change(function() {
			var facultyId = $(this).val();
			if(facultyId) {
				// Enable and load subjects
				$('#subject').prop('disabled', false).html('<option value="">Loading...</option>');
				$('#class').prop('disabled', true).html('<option value="">Select Section</option>');
				$('#proceed-btn').prop('disabled', true);
				
				console.log('Fetching subjects for faculty:', facultyId);
				
				$.ajax({
					url: 'ajax.php?action=get_faculty_subjects',
					method: 'POST',
					data: {faculty_id: facultyId},
					dataType: 'json',
					success: function(data) {
						console.log('Received response:', data);
						
						// Check for error response
						if(data.error) {
							console.error('Server returned error:', data.error);
							$('#selection-error').html(data.error).show();
							$('#subject').prop('disabled', true).html('<option value="">Select Subject</option>');
							return;
						}
						
						var options = '<option value="">Select Subject</option>';
						if(data && data.length > 0) {
							data.forEach(function(item) {
								options += '<option value="' + item.id + '">' + item.code + ' - ' + item.subject + '</option>';
							});
						} else {
							console.log('No subjects found for faculty');
							$('#selection-error').html('No subjects found for this faculty').show();
						}
						$('#subject').html(options);
						$('#selection-error').hide();
					},
					error: function(xhr, status, error) {
						console.error('AJAX Error:', error);
						console.error('Status:', status);
						console.error('Response:', xhr.responseText);
						console.error('Status Code:', xhr.status);
						
						var errorMsg = 'Error loading subjects. ';
						if(xhr.status === 0) {
							errorMsg += 'No response from server.';
						} else if(xhr.status === 404) {
							errorMsg += 'Requested page not found.';
						} else if(xhr.status === 500) {
							errorMsg += 'Internal server error.';
						} else {
							errorMsg += 'Please try again.';
						}
						
						$('#selection-error').html(errorMsg).show();
						$('#subject').prop('disabled', true).html('<option value="">Select Subject</option>');
					}
				});
			} else {
				$('#subject').prop('disabled', true).html('<option value="">Select Subject</option>');
				$('#class').prop('disabled', true).html('<option value="">Select Section</option>');
				$('#proceed-btn').prop('disabled', true);
				$('#selection-error').hide();
			}
		});

		// Handle subject selection
		$('#subject').change(function() {
			var facultyId = $('#faculty').val();
			var subjectId = $(this).val();
			if(facultyId && subjectId) {
				// Enable and load sections
				$('#class').prop('disabled', false).html('<option value="">Loading...</option>');
				$('#proceed-btn').prop('disabled', true);
				
				console.log('Fetching sections for faculty:', facultyId, 'and subject:', subjectId);
				
				$.ajax({
					url: 'ajax.php?action=get_faculty_sections',
					method: 'POST',
					data: {
						faculty_id: facultyId,
						subject_id: subjectId
					},
					dataType: 'json',
					success: function(data) {
						console.log('Received sections response:', data);
						
						// Check for error response
						if(data.error) {
							console.error('Server returned error:', data.error);
							$('#selection-error').html(data.error).show();
							$('#class').prop('disabled', true).html('<option value="">Select Section</option>');
							return;
						}
						
						var options = '<option value="">Select Section</option>';
						if(data && data.length > 0) {
							data.forEach(function(item) {
								options += '<option value="' + item.id + '">' + item.class + '</option>';
							});
						} else {
							console.log('No sections found');
							$('#selection-error').html('No sections found for this subject').show();
						}
						$('#class').html(options);
						$('#selection-error').hide();
					},
					error: function(xhr, status, error) {
						console.error('AJAX Error:', error);
						console.error('Status:', status);
						console.error('Response:', xhr.responseText);
						console.error('Status Code:', xhr.status);
						
						var errorMsg = 'Error loading sections. ';
						if(xhr.status === 0) {
							errorMsg += 'No response from server.';
						} else if(xhr.status === 404) {
							errorMsg += 'Requested page not found.';
						} else if(xhr.status === 500) {
							errorMsg += 'Internal server error.';
						} else {
							errorMsg += 'Please try again.';
						}
						
						$('#selection-error').html(errorMsg).show();
						$('#class').prop('disabled', true).html('<option value="">Select Section</option>');
					}
				});
			} else {
				$('#class').prop('disabled', true).html('<option value="">Select Section</option>');
				$('#proceed-btn').prop('disabled', true);
			}
		});

		// Handle section selection
		$('#class').change(function() {
			var facultyId = $('#faculty').val();
			var subjectId = $('#subject').val();
			var classId = $(this).val();
			
			if(facultyId && subjectId && classId) {
				// Check if already evaluated
				$.ajax({
					url: 'ajax.php?action=check_evaluation',
					method: 'POST',
					data: {
						faculty_id: facultyId,
						subject_id: subjectId,
						class_id: classId
					},
					success: function(resp) {
						if(resp == 1) {
							$('#selection-error').html('You have already evaluated this faculty for this subject and section').show();
							$('#proceed-btn').prop('disabled', true);
						} else {
							$('#selection-error').hide();
							$('#proceed-btn').prop('disabled', false);
						}
					},
					error: function() {
						$('#selection-error').html('Error checking evaluation status').show();
						$('#proceed-btn').prop('disabled', true);
					}
				});
			} else {
				$('#proceed-btn').prop('disabled', true);
			}
		});

		// Handle form submission
		$('#irregular-selection').submit(function(e) {
			e.preventDefault();
			var faculty = $('#faculty').val();
			var subject = $('#subject').val();
			var class_id = $('#class').val();
			
			if (faculty && subject && class_id) {
				// Disable all dropdowns
				$('#faculty, #subject, #class').prop('disabled', true);
				$('#proceed-btn').prop('disabled', true);
				
				// Show success message
				$('#selection-error')
					.removeClass('alert-danger')
					.addClass('alert-success')
					.html('Details saved! Please proceed with the evaluation below.')
					.show();
					
				// Update hidden fields
				$('input[name="faculty_id"]').val(faculty);
				$('input[name="subject_id"]').val(subject);
				$('input[name="class_id"]').val(class_id);
				
				// Scroll to evaluation form
				$('html, body').animate({
					scrollTop: $("#manage-evaluation").offset().top - 100
				}, 500);
			} else {
				$('#selection-error')
					.removeClass('alert-success')
					.addClass('alert-danger')
					.html('Please select all required fields')
					.show();
			}
		});
		<?php endif; ?>

		if ('<?php echo $_SESSION['academic']['status'] ?>' == 0) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>not_started.php")
		} else if ('<?php echo $_SESSION['academic']['status'] ?>' == 2) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>closed.php")
		}
		if (<?php echo empty($rid) && !$is_irregular ? 1 : 0 ?> == 1)
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>done.php")
	})
	
	// Function to update debug box
	function updateDebugBox(message, isError = false) {
		$('#debug-box').show();
		var timestamp = new Date().toLocaleTimeString();
		var content = $('#debug-content').html();
		var newContent = '[' + timestamp + '] ' + message + '\n' + content;
		$('#debug-content').html(newContent);
		
		if(isError) {
			$('#debug-box .card-header').removeClass('bg-dark').addClass('bg-danger');
		} else {
			$('#debug-box .card-header').removeClass('bg-danger').addClass('bg-dark');
		}
	}

	$('#manage-evaluation').submit(function(e) {
		e.preventDefault();
		start_load();
		
		var formData = $(this).serialize();
		updateDebugBox('Form data being submitted: ' + formData);
		
		// Log each textfield value
		$(this).find('textarea').each(function() {
			updateDebugBox('Textfield ' + $(this).attr('name') + ' value: ' + $(this).val());
		});
		
		// Validate required textfields
		var hasEmptyFields = false;
		$(this).find('textarea[required]').each(function() {
			if (!$(this).val().trim()) {
				hasEmptyFields = true;
				$(this).addClass('is-invalid');
			}
		});
		
		if (hasEmptyFields) {
			updateDebugBox('Error: Please fill in all required fields', true);
			alert_toast("Please fill in all required fields", "error");
			end_load();
			return false;
		}
		
		$.ajax({
			url: 'ajax.php?action=save_evaluation',
			method: 'POST',
			data: $(this).serialize(),
			success: function (resp) {
				updateDebugBox('Server response: ' + resp);
				if (resp == 1) {
					alert_toast("Evaluation submitted successfully.", "success");
					setTimeout(function () {
						location.reload();
					}, 1750);
				} else {
					updateDebugBox('Error: ' + resp, true);
					alert_toast("Error: " + resp, "error");
					end_load();
				}
			},
			error: function(xhr, status, error) {
				var errorMsg = 'AJAX Error: ' + error + '\nStatus: ' + status + '\nResponse: ' + xhr.responseText;
				updateDebugBox(errorMsg, true);
				alert_toast("Error submitting evaluation. Please try again.", "error");
				end_load();
			}
		});
	});
</script>