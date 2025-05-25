<?php 
// Debug session
echo "<!-- Debug Info: ";
print_r($_SESSION);
echo " -->";
$faculty_id = isset($_GET['fid']) ? $_GET['fid'] : ''; ?>
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
	<?php if(isset($_GET['status']) && isset($_GET['message'])): ?>
	<div class="alert alert-<?php echo $_GET['status'] == 'success' ? 'success' : 'danger' ?> alert-dismissible" id="status-alert">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<?php echo htmlspecialchars($_GET['message']); ?>
	</div>
	<script>
		$(document).ready(function() {
			// Remove the status parameters from URL without refreshing
			var url = new URL(window.location.href);
			url.searchParams.delete('status');
			url.searchParams.delete('message');
			window.history.replaceState({}, '', url);
		});
	</script>
	<?php endif; ?>

	<div class="card shadow-sm border mb-4">
		<div class="shadow-sm container-fluid px-4 p-3 mb-4 bg-primary text-white d-flex justify-content-between align-items-center">
			<h5 class="card-title mb-0">Pending Evaluations</h5>
			<a href="admin/send_reminders.php" class="btn btn-primary border-white">
				<i class="fas fa-envelope"></i> Send Reminders
			</a>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-bordered table-hover" id="pending-evals">
					<thead class="bg-light">
						<tr>
							<th>Student Name</th>
							<th>Class</th>
							<th>Faculty</th>
							<th>Subject</th>
							<th>Status</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$pending_query = $conn->query("SELECT 
							s.id as student_id,
							CONCAT(s.firstname, ' ', s.lastname) as student_name,
							CONCAT(c.curriculum, ' ', c.level, '-', c.section) as class,
							CONCAT(f.firstname, ' ', f.lastname) as faculty_name,
							sub.subject,
							sub.code as subject_code,
							s.email
						FROM student_list s
						INNER JOIN class_list c ON c.id = s.class_id
						INNER JOIN restriction_list r ON r.class_id = c.id
						INNER JOIN faculty_list f ON f.id = r.faculty_id
						INNER JOIN subject_list sub ON sub.id = r.subject_id
						WHERE r.academic_id = {$_SESSION['academic']['id']}
						AND r.id NOT IN (
							SELECT restriction_id 
							FROM evaluation_list 
							WHERE academic_id = {$_SESSION['academic']['id']} 
							AND student_id = s.id
						)
						ORDER BY s.lastname, s.firstname");

						while ($row = $pending_query->fetch_assoc()):
							?>
							<tr>
								<td><?php echo ucwords($row['student_name']) ?></td>
								<td><?php echo $row['class'] ?></td>
								<td><?php echo ucwords($row['faculty_name']) ?></td>
								<td><?php echo $row['subject_code'] . ' - ' . $row['subject'] ?></td>
								<td><span class="badge badge-warning">Pending</span></td>
							</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<!-- Faculty Selection Panel -->
	<div class="bg-white p-3 mb-4 border shadow-sm rounded">
		<div class="d-flex w-100 justify-content-center align-items-center">
			<label for="faculty" class="font-weight-bold mb-0 mr-3">Select Faculty:</label>
			<div class="col-md-4">
				<select name="" id="faculty_id" class="form-control form-control-sm select2">
					<option value=""></option>
					<?php
					$faculty = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM faculty_list order by concat(firstname,' ',lastname) asc");
					$f_arr = array();
					$fname = array();
					while ($row = $faculty->fetch_assoc()):
						$f_arr[$row['id']] = $row;
						$fname[$row['id']] = ucwords($row['name']);
						?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($faculty_id) && $faculty_id == $row['id'] ? "selected" : "" ?>><?php echo ucwords($row['name']) ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="ml-3">
				<button class="btn btn-sm btn-print text-white" style="display:none" id="print-btn"><i
						class="fa fa-print"></i> Print Report</button>
			</div>
		</div>
	</div>

	<div class="row">
		<!-- Class List Panel -->
		<div class="col-md-3">
			<div class="card shadow-sm border">
				<div class="card-header bg-primary text-white">
					<h5 class="card-title mb-0">Class List</h5>
				</div>
				<div class="card-body p-0">
					<div class="list-group list-group-flush" id="class-list">
						<!-- List items will be dynamically added here -->
					</div>
				</div>
			</div>
		</div>

		<!-- Report Display Panel -->
		<div class="col-md-9">
			<div class="card shadow-sm border" id="printable">
				<div class="card-body">
					<div>
						<h3 class="text-center text-primary">Evaluation Report</h3>
						<hr class="border-primary">
						<table width="100%">
							<tr>
								<td width="50%">
									<p><b>Faculty: <span id="fname"></span></b></p>
								</td>
								<td width="50%">
									<p>
										<b>Academic Year:
											<span
												id="ay"><?php echo $_SESSION['academic']['year'] . ' ' . (ordinal_suffix($_SESSION['academic']['semester'])) ?>
												Semester
											</span>
										</b>
									</p>
								</td>
							</tr>
							<tr>
								<td width="50%">
									<p><b>Class: <span id="classField"></span></b></p>
								</td>
								<td width="50%">
									<p><b>Subject: <span id="subjectField"></span></b></p>
								</td>
							</tr>
						</table>
						<p class=""><b>Total Student Evaluated: <span id="tse"></span></b></p>
					</div>
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
					<?php
					$q_arr = array();
					$criteria = $conn->query("SELECT * FROM criteria_list where id in (SELECT criteria_id FROM question_list where academic_id = {$_SESSION['academic']['id']} ) order by abs(order_by) asc ");
					while ($crow = $criteria->fetch_assoc()):
						?>
						<table class="table table-condensed wborder mt-3">
							<thead>
								<tr class="bg-primary">
									<th><b><?php echo $crow['criteria'] ?></b></th>
									<th width="5%" class="text-center">1</th>
									<th width="5%" class="text-center">2</th>
									<th width="5%" class="text-center">3</th>
									<th width="5%" class="text-center">4</th>
									<th width="5%" class="text-center">5</th>
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
										</td>
										<?php for ($c = 1; $c <= 5; $c++): ?>
											<td class="text-center">
												<span class="rate_<?php echo $c . '_' . $row['id'] ?> rates"></span>
											</td>
										<?php endfor; ?>
									</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					<?php endwhile; ?>
				</div>
			</div>
		</div>
	</div>
</div>

<noscript>
	<style>
		table {
			width: 100%;
			border-collapse: collapse;
		}

		table.wborder tr,
		table.wborder td,
		table.wborder th {
			border: 1px solid gray;
			padding: 3px
		}

		table.wborder thead tr {
			background: #6c757d linear-gradient(180deg, #828a91, #6c757d) repeat-x !important;
			color: #fff;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}

		.text-left {
			text-align: left;
		}
	</style>
</noscript>
<script>
	$(document).ready(function () {
		$('#faculty_id').change(function () {
			if ($(this).val() > 0)
				window.history.pushState({}, null, './index.php?page=report&fid=' + $(this).val());
			load_class()
		})
		if ($('#faculty_id').val() > 0)
			load_class()

		// Add loading overlay when sending reminders
		$('a[href="admin/send_reminders.php"]').click(function(e) {
			e.preventDefault();
			var href = $(this).attr('href');
			
			// Show loading overlay
			$('body').append('<div id="loading-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999; display: flex; justify-content: center; align-items: center;"><div style="background: white; padding: 20px; border-radius: 5px; text-align: center;"><i class="fas fa-spinner fa-spin fa-2x"></i><br>Sending reminders, please wait...</div></div>');
			
			// Disable all clickable elements
			$('a, button').css('pointer-events', 'none');
			
			// Remove the beforeunload handler before navigation
			$(window).off('beforeunload');
			
			// Navigate to send_reminders.php
			window.location.href = href;
		});

		// Handle navigation warning only when loading overlay is not present
		$(window).on('beforeunload', function(e) {
			if($('#loading-overlay').length > 0) {
				return;
			}
			// Only show warning if there are unsaved changes
			if($('form').length > 0 && $('form').serialize() !== $('form').data('original-state')) {
				return "Changes you made may not be saved.";
			}
		});
	})
	function load_class() {
		start_load()
		var fname = <?php echo json_encode($fname) ?>;
		$('#fname').text(fname[$('#faculty_id').val()])
		console.log('Loading class for faculty:', $('#faculty_id').val());
		$.ajax({
			url: "./ajax.php?action=get_class",
			method: 'POST',
			data: { fid: $('#faculty_id').val() },
			error: function (err) {
				console.log('Error loading class:', err);
				alert_toast("An error occured", 'error')
				end_load()
			},
			success: function (resp) {
				console.log('Class response:', resp);
				if (resp) {
					resp = JSON.parse(resp)
					if (Object.keys(resp).length <= 0) {
						$('#class-list').html('<a href="javascript:void(0)" class="list-group-item list-group-item-action disabled">No data to be display.</a>')
					} else {
						$('#class-list').html('')
						Object.keys(resp).map(k => {
							$('#class-list').append('<a href="javascript:void(0)" data-json=\'' + JSON.stringify(resp[k]) + '\' data-id="' + resp[k].id + '" class="list-group-item list-group-item-action show-result">' + resp[k].class + ' - ' + resp[k].subj + '</a>')
						})

					}
				}
			},
			complete: function () {
				end_load()
				anchor_func()
				if ('<?php echo isset($_GET['rid']) ?>' == 1) {
					$('.show-result[data-id="<?php echo isset($_GET['rid']) ? $_GET['rid'] : '' ?>"]').trigger('click')
				} else {
					$('.show-result').first().trigger('click')
				}
			}
		})
	}
	function anchor_func() {
		$('.show-result').click(function () {
			var vars = [], hash;
			var data = $(this).attr('data-json')
			data = JSON.parse(data)
			var _href = location.href.slice(window.location.href.indexOf('?') + 1).split('&');
			for (var i = 0; i < _href.length; i++) {
				hash = _href[i].split('=');
				vars[hash[0]] = hash[1];
			}
			window.history.pushState({}, null, './index.php?page=report&fid=' + vars.fid + '&rid=' + data.id);
			load_report(vars.fid, data.sid, data.id);
			$('#subjectField').text(data.subj)
			$('#classField').text(data.class)
			$('.show-result.active').removeClass('active')
			$(this).addClass('active')
		})
	}
	function load_report($faculty_id, $subject_id, $class_id) {
		if ($('#preloader2').length <= 0)
			start_load()
		console.log('Loading report for:', {faculty_id: $faculty_id, subject_id: $subject_id, class_id: $class_id});
		$.ajax({
			url: './ajax.php?action=get_report',
			method: "POST",
			data: { faculty_id: $faculty_id, subject_id: $subject_id, class_id: $class_id },
			error: function (err) {
				console.log('Error loading report:', err);
				alert_toast("An Error Occured.", "error");
				end_load()
			},
			success: function (resp) {
				console.log('Report response:', resp);
				if (resp) {
					resp = JSON.parse(resp)
					if (Object.keys(resp).length <= 0) {
						$('.rates').text('')
						$('#tse').text('')
						$('#print-btn').hide()
					} else {
						$('#print-btn').show()
						$('#tse').text(resp.tse)
						$('.rates').text('-')
						var data = resp.data
						Object.keys(data).map(q => {
							Object.keys(data[q]).map(r => {
								console.log($('.rate_' + r + '_' + q), data[q][r])
								$('.rate_' + r + '_' + q).text(data[q][r] + '%')
							})
						})
					}

				}
			},
			complete: function () {
				end_load()
			}
		})
	}
	$('#print-btn').click(function () {
		start_load()
		var ns = $('noscript').clone()
		var content = $('#printable').html()
		ns.append(content)
		var nw = window.open("Report", "_blank", "width=900,height=700")
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(function () {
			nw.close()
			end_load()
		}, 750)
	})
</script>