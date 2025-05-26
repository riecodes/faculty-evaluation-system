<?php

include 'db_connect.php';
if (isset($_GET['id'])) {
	$qry = $conn->query("SELECT * FROM academic_list where id = " . $_GET['id'])->fetch_array();
	foreach ($qry as $k => $v) {
		$$k = $v;
	}
}
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

	.btn-primary {
		background-color: #183f74 !important;
		border-color: #183f74 !important;
	}

	.btn-success {
		background-color: #417029 !important;
		border-color: #417029 !important;
	}

	.btn-secondary {
		background-color: #6c757d !important;
		border-color: #6c757d !important;
	}

	.card-primary .card-header {
		background-color: #183f74 !important;
		border-color: #183f74 !important;
	}

	.card-info .card-header {
		background-color: #183f74 !important;
		border-color: #183f74 !important;
	}

	.dropdown-item:hover {
		background-color: #f8f9fa !important;
		color: #183f74 !important;
	}
</style>

<div class="container-fluid px-4">
	<div class="row">
		<div class="col-md-4">
			<div class="card shadow-sm border">
				<div class="card-header bg-primary text-white">
					<h5 class="card-title mb-0">Question Form</h5>
				</div>
				<div class="card-body">
					<form action="" id="manage-question">
						<input type="hidden" name="academic_id" value="<?php echo isset($id) ? $id : '' ?>">
						<input type="hidden" name="id" value="">
						<div class="form-group">
							<label for="" class="font-weight-bold">Criteria</label>
							<select name="criteria_id" id="criteria_id" class="form-control select2">
								<option value=""></option>
								<?php
								$criteria = $conn->query("SELECT * FROM criteria_list order by abs(order_by) asc ");
								while ($row = $criteria->fetch_assoc()):
									?>
									<option value="<?php echo $row['id'] ?>" data-type="<?php echo $row['type'] ?>"><?php echo $row['criteria'] ?></option>
								<?php endwhile; ?>
							</select>
						</div>
						<div class="form-group">
							<label for="" class="font-weight-bold">Question</label>
							<textarea name="question" id="question" cols="30" rows="4" class="form-control"
								><?php echo isset($question) ? $question : '' ?></textarea>
						</div>
						<input type="hidden" name="type" id="question_type">
					</form>
				</div>
				<div class="card-footer bg-light">
					<div class="d-flex justify-content-end w-100">
						<button class="btn btn-sm btn-primary mx-1" form="manage-question">Save</button>
						<button class="btn btn-sm btn-secondary mx-1" form="manage-question"
							type="reset">Cancel</button>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="card shadow-sm border">
				<div class="p-3 mb-4 border shadow-sm bg-primary d-flex justify-content-between align-items-center">
					<div class="d-flex flex-column">
						<h5 class="card-title mb-0">Evaluation Questionnaire for Academic: </h5>
						<h5 class="card-title mb-0"><?php echo $year . ' ' . (ordinal_suffix($semester)) ?></h5>
					</div>

					<div class="d-flex ">
						<button class="btn btn-sm btn-light mx-1" id="eval_restrict" type="button">
							<i class="fa fa-cog"></i> Evaluation Restriction
						</button>
						<button class="btn btn-sm btn-success text-white mx-1" form="order-question">
							<i class="fa fa-save"></i> Save Order
						</button>
					</div>
				</div>
				<!-- Debug Box -->
				<div id="debug-box" class="alert alert-info mb-3">
					<div class="d-flex justify-content-between align-items-center mb-2">
						<h6 class="font-weight-bold mb-0">Debug Information:</h6>
						<button class="btn btn-sm btn-outline-secondary" id="clear-debug">Clear</button>
					</div>
					<div id="debug-content"></div>
				</div>
				<div class="card-body">
					<fieldset class="border border-info p-2 w-100 mb-4">
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
					<form id="order-question">
						<div class="clear-fix mt-2"></div>
						<?php
						$q_arr = array();
						$criteria = $conn->query("SELECT * FROM criteria_list order by abs(order_by) asc ");
						while ($crow = $criteria->fetch_assoc()):
							?>
							<table class="table table-condensed mt-3">
								<thead>
									<tr class="bg-primary text-white">
										<th colspan="2"><b><?php echo $crow['criteria'] ?></b></th>
										<th class="text-center">5</th>
										<th class="text-center">4</th>
										<th class="text-center">3</th>
										<th class="text-center">2</th>
										<th class="text-center">1</th>
									</tr>
								</thead>
								<tbody class="tr-sortable">
									<?php
									$questions = $conn->query("SELECT * FROM question_list where criteria_id = {$crow['id']} and academic_id = $id order by abs(order_by) asc ");
									while ($row = $questions->fetch_assoc()):
										$q_arr[$row['id']] = $row;
										?>
										<tr class="bg-white">
											<td class="p-1 text-center" width="5px">
												<span class="btn-group drop right">
													<span type="button" class="btn" data-toggle="dropdown" aria-haspopup="true"
														aria-expanded="false">
														<i class="fa fa-ellipsis-v"></i>
													</span>
													<div class="dropdown-menu">
														<a class="dropdown-item edit_question" href="javascript:void(0)"
															data-id="<?php echo $row['id'] ?>">Edit</a>
														<div class="dropdown-divider"></div>
														<a class="dropdown-item delete_question" href="javascript:void(0)"
															data-id="<?php echo $row['id'] ?>">Delete </a>
													</div>
												</span>
											</td>
											<td class="p-1" width="40%">
												<?php echo $row['question'] ?>
												<input type="hidden" name="qid[]" value="<?php echo $row['id'] ?>">
											</td>
											<?php if($row['type'] == 'checklist'): ?>
												<?php for ($c = 0; $c < 5; $c++): ?>
													<td class="text-center">
														<div class="icheck-success d-inline">
															<input type="radio" name="qid[<?php echo $row['id'] ?>][]"
																id="qradio<?php echo $row['id'] . '_' . $c ?>">
															<label for="qradio<?php echo $row['id'] . '_' . $c ?>">
															</label>
														</div>
													</td>
												<?php endfor; ?>
											<?php else: ?>
												<td colspan="5">
													<textarea name="qid[<?php echo $row['id'] ?>]" class="form-control" rows="2"></textarea>
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
		// Load saved debug messages on page load
		var savedMessages = localStorage.getItem('debugMessages');
		if (savedMessages) {
			$('#debug-content').html(savedMessages);
		}
		
		// Clear debug messages button
		$('#clear-debug').click(function() {
			$('#debug-content').empty();
			localStorage.removeItem('debugMessages');
		});
		
		$('.select2').select2({
			placeholder: "Please select here",
			width: "100%"
		});

		// Update question type based on selected criteria
		$('#criteria_id').change(function() {
			var selectedOption = $(this).find('option:selected');
			var criteriaType = selectedOption.data('type');
			$('#question_type').val(criteriaType);
		});
	})
	
	// Function to update debug box
	function updateDebugBox(message, isError = false) {
		var debugBox = $('#debug-box');
		var debugContent = $('#debug-content');
		
		debugBox.removeClass('alert-danger alert-info')
			.addClass(isError ? 'alert-danger' : 'alert-info');
		
		var timestamp = new Date().toLocaleTimeString();
		var newMessage = '<div class="mb-1">[' + timestamp + '] ' + message + '</div>';
		
		// Add new message to the top
		debugContent.prepend(newMessage);
		
		// Save to localStorage
		localStorage.setItem('debugMessages', debugContent.html());
	}
	
	$('.edit_question').click(function () {
		var id = $(this).attr('data-id')
		var question = <?php echo json_encode($q_arr) ?>;
		$('#manage-question').find("[name='id']").val(question[id].id)
		$('#manage-question').find("[name='question']").val(question[id].question)
		$('#manage-question').find("[name='criteria_id']").val(question[id].criteria_id).trigger('change')
		$('#manage-question').find("[name='type']").val(question[id].type)
	})
	$('.delete_question').click(function () {
		_conf("Are you sure to delete this question?", "delete_question", [$(this).attr('data-id')])
	})
	$('#eval_restrict').click(function () {
		var academic_id = <?php echo isset($id) ? $id : 'null' ?>;
		updateDebugBox('Opening restriction manager for academic_id: ' + academic_id);
		
		// Check if academic_id is valid
		if (!academic_id) {
			updateDebugBox('Error: Invalid academic_id', true);
			return;
		}
		
		// Check current restrictions before opening modal
		$.ajax({
			url: 'ajax.php?action=check_restrictions',
			method: 'POST',
			data: { academic_id: academic_id },
			success: function(resp) {
				try {
					var data = JSON.parse(resp);
					updateDebugBox('Current restrictions: ' + JSON.stringify(data, null, 2));
				} catch(e) {
					updateDebugBox('Error parsing restrictions data: ' + e.message, true);
				}
			},
			error: function(xhr, status, error) {
				updateDebugBox('Error checking restrictions: ' + error, true);
			}
		});
		
		uni_modal("Manage Evaluation Restrictions", "<?php echo $_SESSION['login_view_folder'] ?>manage_restriction.php?id=<?php echo $id ?>", "mid-large");
	});
	
	// Listen for messages from the restriction modal
	window.addEventListener('message', function(event) {
		if (event.data && event.data.type === 'restriction_update') {
			updateDebugBox('Restriction update received: ' + JSON.stringify(event.data, null, 2));
		}
	});
	$('.tr-sortable').sortable()
	$('#manage-question').on('reset', function () {
		$(this).find('input[name="id"]').val('')
		$('#manage-question').find("[name='criteria_id']").val('').trigger('change')
	})
	$('#manage-question').submit(function (e) {
		e.preventDefault()
		start_load()
		if ($('#question').val() == '') {
			alert_toast("Please fill the question description first", 'error');
			end_load();
			return false;
		}
		// Ensure type is set from criteria before submitting
		var selectedOption = $('#criteria_id').find('option:selected');
		var criteriaType = selectedOption.data('type');
		$('#question_type').val(criteriaType);
		
		$.ajax({
			url: 'ajax.php?action=save_question',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function (resp) {
				if (resp == 1) {
					alert_toast('Data successfully saved', "success");
					updateDebugBox('Question saved successfully');
					setTimeout(function () {
						location.reload()
					}, 1500)
				}
			}
		})
	})
	$('#order-question').submit(function (e) {
		e.preventDefault()
		start_load()
		var formData = new FormData(this);
		
		// Get the order of questions within each criteria
		$('.tr-sortable').each(function(criteriaIndex) {
			$(this).find('tr').each(function(questionIndex) {
				var qid = $(this).find('input[name="qid[]"]').val();
				if(qid) {
					formData.append('order[' + qid + ']', questionIndex);
				}
			});
		});

		$.ajax({
			url: 'ajax.php?action=save_question_order',
			data: formData,
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function (resp) {
				if (resp == 1) {
					alert_toast('Order successfully saved', "success");
					end_load()
				} else {
					alert_toast('Error saving order', "error");
					end_load()
				}
			},
			error: function(xhr, status, error) {
				alert_toast('Error: ' + error, "error");
				end_load()
			}
		})
	})
	function delete_question($id) {
		start_load()
		$.ajax({
			url: 'ajax.php?action=delete_question',
			method: 'POST',
			data: { id: $id },
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully deleted", 'success')
					setTimeout(function () {
						location.reload()
					}, 1500)
				}
			}
		})
	}
</script>