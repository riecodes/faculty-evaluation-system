<?php
?>
<div class="col-lg-12">
	<div class="card">
		<div class="card-body">			
			<button class="btn btn-primary btn-sm mb-2" type="button" data-toggle="modal" data-target="#bulkFacultyModal"><i class="fa fa-upload"></i> Bulk Account Creation</button>
			<form action="" id="manage_faculty">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">School ID</label>
							<input type="text" name="school_id" class="form-control form-control-sm" required
								value="<?php echo isset($school_id) ? $school_id : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">First Name</label>
							<input type="text" name="firstname" class="form-control form-control-sm" required
								value="<?php echo isset($firstname) ? $firstname : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Last Name</label>
							<input type="text" name="lastname" class="form-control form-control-sm" required
								value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="" class="control-label">Avatar</label>
							<div class="custom-file">
								<input type="file" class="custom-file-input" id="customFile" name="img"
									onchange="displayImg(this,$(this))">
								<label class="custom-file-label" for="customFile">Choose file</label>
							</div>
						</div>
						<div class="form-group d-flex justify-content-center align-items-center">
							<img src="<?php echo isset($avatar) ? 'assets/uploads/' . $avatar : '' ?>" alt="Avatar"
								id="cimg" class="img-fluid img-thumbnail ">
						</div>
						<div class="form-group">
							<label class="control-label">Email</label>
							<input type="email" class="form-control form-control-sm" name="email" required
								value="<?php echo isset($email) ? $email : '' ?>">
							<small id="#msg"></small>
						</div>
						<div class="form-group">
							<label class="control-label">Password</label>
							<input type="password" class="form-control form-control-sm" name="password" <?php echo !isset($id) ? "required" : '' ?>>
							<small><i><?php echo isset($id) ? "Leave this blank if you dont want to change you password" : '' ?></i></small>
						</div>
						<div class="form-group">
							<label class="label control-label">Confirm Password</label>
							<input type="password" class="form-control form-control-sm" name="cpass" <?php echo !isset($id) ? 'required' : '' ?>>
							<small id="pass_match" data-status=''></small>
						</div>
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button"
						onclick="location.href = 'index.php?page=faculty_list'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<style>
	img#cimg {
		height: 15vh;
		width: 15vh;
		object-fit: cover;
		border-radius: 100% 100%;
	}
</style>
<script>
	$('[name="password"],[name="cpass"]').keyup(function () {
		var pass = $('[name="password"]').val()
		var cpass = $('[name="cpass"]').val()
		if (cpass == '' || pass == '') {
			$('#pass_match').attr('data-status', '')
		} else {
			if (cpass == pass) {
				$('#pass_match').attr('data-status', '1').html('<i class="text-success">Password Matched.</i>')
			} else {
				$('#pass_match').attr('data-status', '2').html('<i class="text-danger">Password does not match.</i>')
			}
		}
	})
	function displayImg(input, _this) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function (e) {
				$('#cimg').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
	$('#manage_faculty').submit(function (e) {
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		if ($('[name="password"]').val() != '' && $('[name="cpass"]').val() != '') {
			if ($('#pass_match').attr('data-status') != 1) {
				if ($("[name='password']").val() != '') {
					$('[name="password"],[name="cpass"]').addClass("border-danger")
					end_load()
					return false;
				}
			}
		}
		$.ajax({
			url: 'ajax.php?action=save_faculty',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function (resp) {
				if (resp == 1) {
					alert_toast('Data successfully saved.', "success");
					setTimeout(function () {
						location.replace('index.php?page=faculty_list')
					}, 750)
				} else if (resp == 2) {
					$('#msg').html("<div class='alert alert-danger'>Email already exist.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				} else if (resp == 3) {
					$('#msg').html("<div class='alert alert-danger'>School ID already exist.</div>");
					$('[name="school_id"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})

	$('#bulkFacultyForm').submit(function(e){
		e.preventDefault();
		start_load();
		$.ajax({
			url: 'ajax.php?action=import_faculty_csv',
			data: new FormData($(this)[0]),
			cache: false,
			contentType: false,
			processData: false,
			method: 'POST',
			type: 'POST',
			success: function(resp){
				resp = JSON.parse(resp);
				if(resp.status == 'success'){
					alert_toast(resp.message, 'success');
					if(resp.errors.length > 0){
						var errorMsg = '<ul>';
						resp.errors.forEach(function(error){
							errorMsg += '<li>' + error + '</li>';
						});
						errorMsg += '</ul>';
						$('#bulkFacultyModal .modal-body').prepend('<div class="alert alert-warning">' + errorMsg + '</div>');
					}
					setTimeout(function(){
						location.reload();
					}, 1500);
				} else {
					alert_toast(resp.message, 'error');
					end_load();
				}
			},
			error: function(err){
				console.log(err);
				alert_toast('An error occurred', 'error');
				end_load();
			}
		});
	});
</script>
<!-- Bulk Faculty Modal -->
<div class="modal fade" id="bulkFacultyModal" tabindex="-1" role="dialog" aria-labelledby="bulkFacultyModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bulkFacultyModalLabel">Bulk Faculty Account Creation</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="bulkFacultyForm" enctype="multipart/form-data">
          <div class="form-group">
            <label for="facultyCSV">Upload CSV File</label>
            <input type="file" class="form-control-file" id="facultyCSV" name="facultyCSV" accept=".csv" required>
          </div>
          <a href="templates/sample_faculty_import.csv" download>Download Sample CSV Template</a>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="bulkFacultyForm" class="btn btn-primary">Upload</button>
      </div>
    </div>
  </div>
</div>