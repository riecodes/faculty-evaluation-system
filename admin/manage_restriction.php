<?php
include '../db_connect.php';
?>
<div class="container-fluid">
	<form action="" id="manage-restriction">
		<div class="row">
			<div class="col-md-4 border-right">
				<input type="hidden" name="academic_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
				<div id="msg" class="form-group"></div>
				<div class="form-group">
					<label for="" class="control-label">Faculty</label>
					<select name="" id="faculty_id" class="form-control form-control-sm select2">
						<option value=""></option>
						<?php 
						$faculty = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM faculty_list where status = 1 order by concat(firstname,' ',lastname) asc");
						$f_arr = array();
						while($row=$faculty->fetch_assoc()):
							$f_arr[$row['id']]= $row;
						?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($faculty_id) && $faculty_id == $row['id'] ? "selected" : "" ?>><?php echo ucwords($row['name']) ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="" class="control-label">Class</label>
					<select name="" id="class_id" class="form-control form-control-sm select2">
						<option value=""></option>
						<?php 
						$classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as class FROM class_list");
						$c_arr = array();
						while($row=$classes->fetch_assoc()):
							$c_arr[$row['id']]= $row;
						?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($class_id) && $class_id == $row['id'] ? "selected" : "" ?>><?php echo $row['class'] ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<div class="form-group">
					<label for="" class="control-label">Subject</label>
					<select name="" id="subject_id" class="form-control form-control-sm select2">
						<option value=""></option>
						<?php 
						$subject = $conn->query("SELECT id,concat(code,' - ',subject) as subj FROM subject_list");
						$s_arr = array();
						while($row=$subject->fetch_assoc()):
							$s_arr[$row['id']]= $row;
						?>
						<option value="<?php echo $row['id'] ?>" <?php echo isset($subject_id) && $subject_id == $row['id'] ? "selected" : "" ?>><?php echo $row['subj'] ?></option>
						<?php endwhile; ?>
					</select>
				</div>
				<input type="hidden" id="type" value="regular">
				<div class="form-group">
					<div class="d-flex w-100 justify-content-center">
						<button class="btn btn-sm btn-primary" id="add_to_list" type="button">Add to List</button>
					</div>
				</div>
			</div>
			<div class="col-md-8">
				<table class="table table-condensed" id="r-list">
					<thead>
						<tr>
							<th>Faculty</th>
							<th>Class</th>
							<th>Subject</th>
							<th>Type</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$academic_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
						error_log("Fetching restrictions for academic_id: " . $academic_id);
						
						$restriction_query = "SELECT r.*, 
							f.firstname, f.lastname,
							c.curriculum, c.level, c.section,
							s.code, s.subject
							FROM restriction_list r 
							LEFT JOIN faculty_list f ON f.id = r.faculty_id 
							LEFT JOIN class_list c ON c.id = r.class_id 
							LEFT JOIN subject_list s ON s.id = r.subject_id 
							WHERE r.academic_id = $academic_id 
							ORDER BY r.id ASC";
						
						error_log("Restriction query: " . $restriction_query);
						
						$restriction = $conn->query($restriction_query);
						
						if(!$restriction) {
							error_log("Error fetching restrictions: " . $conn->error);
						}
						
						error_log("Number of restrictions found: " . $restriction->num_rows);
						
						while($row=$restriction->fetch_assoc()):
							error_log("Processing restriction row: " . print_r($row, true));
						?>
						<tr data-id="<?php echo $row['id'] ?>">
							<td>
								<b><?php echo ucwords($row['firstname'] . ' ' . $row['lastname']) ?></b>
								<input type="hidden" name="rid[]" value="<?php echo $row['id'] ?>">
								<input type="hidden" name="faculty_id[]" value="<?php echo $row['faculty_id'] ?>">
							</td>
							<td>
								<b><?php echo $row['curriculum'] . ' ' . $row['level'] . ' - ' . $row['section'] ?></b>
								<input type="hidden" name="class_id[]" value="<?php echo $row['class_id'] ?>">
							</td>
							<td>
								<b><?php echo $row['code'] . ' - ' . $row['subject'] ?></b>
								<input type="hidden" name="subject_id[]" value="<?php echo $row['subject_id'] ?>">
							</td>
							<td>
								<b><?php echo ucfirst($row['type']) ?></b>
								<input type="hidden" name="type[]" value="<?php echo $row['type'] ?>">
							</td>
							<td class="text-center">
								<?php if($row['type'] == 'regular'): ?>
								<button class="btn btn-sm btn-outline-danger remove_restriction" type="button" data-id="<?php echo $row['id'] ?>"><i class="fa fa-trash"></i></button>
								<?php endif; ?>
							</td>
						</tr>
					<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</form>
</div>
<script>
	$(document).ready(function(){
		$('.select2').select2({
		    placeholder:"Please select here",
		    width: "100%"
		});

		// Handle form submission
		$('#manage-restriction').submit(function(e){
			e.preventDefault();
			start_load();
			$('#msg').html('');
			
			// Get academic_id from hidden input
			var academic_id = $('input[name="academic_id"]').val();
			if(!academic_id) {
				$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error: Academic ID is missing</div>');
				end_load();
				return false;
			}
			
			// Debug log
			console.log('Academic ID:', academic_id);
			
			// Get all restrictions from the table
			var formData = new FormData();
			formData.append('academic_id', academic_id);
			
			// Get all rows from the table
			$('#r-list tbody tr').each(function(index) {
				var row = $(this);
				formData.append('rid[]', row.find('input[name="rid[]"]').val() || '');
				formData.append('faculty_id[]', row.find('input[name="faculty_id[]"]').val());
				formData.append('class_id[]', row.find('input[name="class_id[]"]').val());
				formData.append('subject_id[]', row.find('input[name="subject_id[]"]').val());
				formData.append('type[]', row.find('input[name="type[]"]').val());
			});
			
			// Debug log the form data
			console.log('Submitting form data:');
			for (var pair of formData.entries()) {
				console.log(pair[0] + ': ' + pair[1]);
			}
			
			$.ajax({
				url:'ajax.php?action=save_restriction&id=' + academic_id,
				method:'POST',
				data: formData,
				processData: false,
				contentType: false,
				success:function(resp){
					console.log('Server response:', resp);
					if(resp == 1){
						// Send message to parent window
						window.parent.postMessage({
							type: 'restriction_update',
							action: 'save',
							status: 'success',
							message: 'Restrictions saved successfully',
							data: Object.fromEntries(formData)
						}, '*');
						
						alert_toast("Data successfully saved.","success");
						setTimeout(function(){
							location.reload();	
						},1750);
					}else if(resp == 2){
						// Send error message to parent window
						window.parent.postMessage({
							type: 'restriction_update',
							action: 'save',
							status: 'error',
							message: 'Duplicate faculty-subject-class-type combination found'
						}, '*');
						
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Duplicate faculty-subject-class-type combination found.</div>');
						end_load();
					}else{
						// Send error message to parent window
						window.parent.postMessage({
							type: 'restriction_update',
							action: 'save',
							status: 'error',
							message: 'Error saving data: ' + resp
						}, '*');
						
						$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error saving data. Response: ' + resp + '</div>');
						end_load();
					}
				},
				error: function(xhr, status, error) {
					// Send error message to parent window
					window.parent.postMessage({
						type: 'restriction_update',
						action: 'save',
						status: 'error',
						message: 'AJAX Error: ' + error,
						details: xhr.responseText
					}, '*');
					
					console.error('AJAX Error:', {
						status: status,
						error: error,
						response: xhr.responseText
					});
					$('#msg').html('<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> Error: ' + error + '<br>Response: ' + xhr.responseText + '</div>');
					end_load();
				}
			});
		});

		// Handle adding new restriction
		$('#add_to_list').click(function(){
			start_load();
			var frm = $('#manage-restriction');
			var cid = frm.find('#class_id').val();
			var fid = frm.find('#faculty_id').val();
			var sid = frm.find('#subject_id').val();
			var type = frm.find('#type').val();

			// Debug log
			console.log('Adding restriction:', {
				faculty_id: fid,
				class_id: cid,
				subject_id: sid,
				type: type
			});

			if(!cid || !fid || !sid) {
				alert_toast("Please select faculty, class and subject.", "error");
				end_load();
				return false;
			}

			// Check for duplicate combination in UI table
			var isDuplicate = false;
			$('#r-list tbody tr').each(function() {
				var rowFid = $(this).find('input[name="faculty_id[]"]').val();
				var rowCid = $(this).find('input[name="class_id[]"]').val();
				var rowSid = $(this).find('input[name="subject_id[]"]').val();
				var rowType = $(this).find('input[name="type[]"]').val();
				
				// Debug log
				console.log('Checking row:', {
					rowFid: rowFid,
					rowCid: rowCid,
					rowSid: rowSid,
					rowType: rowType
				});
				
				if(rowFid == fid && rowCid == cid && rowSid == sid && rowType == type) {
					isDuplicate = true;
					return false;
				}
			});

			if(isDuplicate) {
				alert_toast("This faculty-subject-class-type combination already exists in the list.", "error");
				end_load();
				return false;
			}

			// Check for duplicate in database
			$.ajax({
				url: 'ajax.php?action=check_restriction_duplicate',
				method: 'POST',
				data: {
					faculty_id: fid,
					class_id: cid,
					subject_id: sid,
					type: type,
					academic_id: <?php echo $_GET['id'] ?>
				},
				success: function(resp) {
					console.log('Duplicate check response:', resp);
					if(resp == 1) {
						alert_toast("This faculty-subject-class-type combination already exists in the database.", "error");
						end_load();
						return false;
					} else {
						// No duplicate found, proceed with adding to list
			var f_arr = <?php echo json_encode($f_arr) ?>;
			var c_arr = <?php echo json_encode($c_arr) ?>;
			var s_arr = <?php echo json_encode($s_arr) ?>;
			
			var tr = $("<tr></tr>");
			tr.append('<td><b>'+f_arr[fid].name+'</b><input type="hidden" name="rid[]" value=""><input type="hidden" name="faculty_id[]" value="'+fid+'"></td>');
			tr.append('<td><b>'+c_arr[cid].class+'</b><input type="hidden" name="class_id[]" value="'+cid+'"></td>');
			tr.append('<td><b>'+s_arr[sid].subj+'</b><input type="hidden" name="subject_id[]" value="'+sid+'"></td>');
			tr.append('<td><b>'+type.charAt(0).toUpperCase() + type.slice(1)+'</b><input type="hidden" name="type[]" value="'+type+'"></td>');
			tr.append('<td class="text-center"><button class="btn btn-sm btn-outline-danger remove_restriction" type="button"><i class="fa fa-trash"></i></button></td>');
			$('#r-list tbody').append(tr);
			
			// Clear form
			frm.find('#class_id').val('').trigger('change');
			frm.find('#faculty_id').val('').trigger('change');
			frm.find('#subject_id').val('').trigger('change');
			end_load();
					}
				},
				error: function(xhr, status, error) {
					console.error('Error checking duplicate:', error);
					alert_toast("Error checking for duplicates: " + error, "error");
					end_load();
				}
			});
		});

		// Handle removing restriction
		$(document).on('click', '.remove_restriction', function(){
			var id = $(this).data('id');
			var row = $(this).closest('tr');
			
			if(id) {
				// If it's an existing record, remove it from database
				$.ajax({
					url:'ajax.php?action=delete_restriction',
					method:'POST',
					data:{id: id},
					success:function(resp){
						try {
							var response = JSON.parse(resp);
							if(response.success) {
								// Send message to parent window
								window.parent.postMessage({
									type: 'restriction_update',
									action: 'delete',
									status: 'success',
									message: 'Restriction deleted successfully',
									id: id
								}, '*');
								
								// Remove the row from the table
								row.remove();
								alert_toast("Restriction deleted successfully", "success");
							} else {
								alert_toast("Error: " + (response.error || "Failed to delete restriction"), "error");
			}
						} catch(e) {
							console.error('Error parsing response:', e);
							alert_toast("Error deleting restriction", "error");
						}
					},
					error: function(xhr, status, error) {
						console.error('AJAX Error:', error);
						alert_toast("Error deleting restriction: " + error, "error");
					}
				});
			} else {
				// If it's a new row (no ID), just remove it from the table
				row.remove();
			}
		});
	});
</script>