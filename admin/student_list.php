<?php include'db_connect.php' ?>
<div class="col-lg-12">
	<div class="card card-outline card-success">
		<div class="card-header">
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-default border-primary" href="./index.php?page=new_student"><i class="fa fa-plus"></i> Add New Student</a>
			</div>
		</div>
		<div class="card-body">
			<table class="table tab e-hover table-bordered" id="list">
				<thead>
					<tr>
						<th class="text-center">#</th>
						<th>School ID</th>
						<th>Name</th>
						<th>Email</th>
						<th>Current Class</th>
						<th>Status</th>
						<th>Action</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$class= array();
					$classes = $conn->query("SELECT id,concat(curriculum,' ',level,' - ',section) as `class` FROM class_list");
					while($row=$classes->fetch_assoc()){
						$class[$row['id']] = $row['class'];
					}
					$qry = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM student_list order by concat(firstname,' ',lastname) asc");
					while($row= $qry->fetch_assoc()):
					?>
					<tr>
						<th class="text-center"><?php echo $i++ ?></th>
						<td><b><?php echo $row['school_id'] ?></b></td>
						<td><b><?php echo ucwords($row['name']) ?></b></td>
						<td><b><?php echo $row['email'] ?></b></td>
						<td><b><?php echo isset($class[$row['class_id']]) ? $class[$row['class_id']] : "N/A" ?></b></td>
						<td class="text-center">
							<?php if($row['status'] == 1): ?>
								<span class="badge badge-success">Active</span>
							<?php else: ?>
								<span class="badge badge-danger">Deactivated</span>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<button type="button" class="btn btn-default btn-sm border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
		                      Action
		                    </button>
		                    <div class="dropdown-menu" style="">
		                      <a class="dropdown-item view_student" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">View</a>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item" href="./index.php?page=edit_student&id=<?php echo $row['id'] ?>">Edit</a>
		                      <div class="dropdown-divider"></div>
		                      <?php if($row['status'] == 1): ?>
		                      <a class="dropdown-item deactivate_student" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Deactivate</a>
		                      <?php else: ?>
		                      <a class="dropdown-item activate_student" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Activate</a>
		                      <?php endif; ?>
		                      <div class="dropdown-divider"></div>
		                      <a class="dropdown-item delete_student" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">Delete</a>
		                    </div>
						</td>
					</tr>	
				<?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
	$('.view_student').click(function(){
		uni_modal("<i class='fa fa-id-card'></i> student Details","<?php echo $_SESSION['login_view_folder'] ?>view_student.php?id="+$(this).attr('data-id'))
	})
	$('.delete_student').click(function(){
	_conf("Are you sure to delete this student?","delete_student",[$(this).attr('data-id')])
	})
	$('.deactivate_student').click(function(){
	_conf("Are you sure to deactivate this student?","deactivate_student",[$(this).attr('data-id')])
	})
	$('.activate_student').click(function(){
	_conf("Are you sure to activate this student?","activate_student",[$(this).attr('data-id')])
	})
		$('#list').dataTable()
	})
	function delete_student($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_student',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
	function deactivate_student($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=deactivate_student',
			method:'POST',
			data:{id:$id},
			error: function(err) {
				console.log(err)
				alert_toast("An error occurred",'error')
				end_load()
			},
			success:function(resp){
				if(resp==1){
					alert_toast("Student successfully deactivated",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				} else {
					alert_toast("An error occurred",'error')
					end_load()
				}
			}
		})
	}
	function activate_student($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=activate_student',
			method:'POST',
			data:{id:$id},
			error: function(err) {
				console.log(err)
				alert_toast("An error occurred",'error')
				end_load()
			},
			success:function(resp){
				if(resp==1){
					alert_toast("Student successfully activated",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				} else {
					alert_toast("An error occurred",'error')
					end_load()
				}
			}
		})
	}
</script>