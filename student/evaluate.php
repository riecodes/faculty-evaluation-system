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
$rid = '';
$faculty_id = '';
$subject_id = '';
if (isset($_GET['rid']))
	$rid = $_GET['rid'];
if (isset($_GET['fid']))
	$faculty_id = $_GET['fid'];
if (isset($_GET['sid']))
	$subject_id = $_GET['sid'];
$restriction = $conn->query("SELECT r.id,s.id as sid,f.id as fid,f.avatar,concat(f.firstname,' ',f.lastname) as faculty,s.code,s.subject FROM restriction_list r inner join faculty_list f on f.id = r.faculty_id inner join subject_list s on s.id = r.subject_id where academic_id ={$_SESSION['academic']['id']} and class_id = {$_SESSION['login_class_id']} and r.id not in (SELECT restriction_id from evaluation_list where academic_id ={$_SESSION['academic']['id']} and student_id = {$_SESSION['login_id']} ) ");
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

<div class="col-lg-12 ">
	<div class="row d-flex flex-column justify-content-center align-items-center">
		<div class="col-md-9">
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
						<input type="hidden" name="class_id" value="<?php echo $_SESSION['login_class_id'] ?>">
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
		// Initialize Owl Carousel
		var totalItems = $('.faculty-carousel .item').length;
		var itemsToShow = Math.min(totalItems, 3); // Show up to 3 items, or less if fewer items exist
		
		$('.faculty-carousel').owlCarousel({
			items: itemsToShow,
			loop: totalItems > 3, // Only loop if we have more than 3 items
			margin: 10,
			nav: totalItems > itemsToShow, // Only show navigation if we have more items than what's being displayed
			dots: true,
			autoplay: totalItems > itemsToShow, // Only autoplay if we have more items than what's being displayed
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

		if ('<?php echo $_SESSION['academic']['status'] ?>' == 0) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>not_started.php")
		} else if ('<?php echo $_SESSION['academic']['status'] ?>' == 2) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>closed.php")
		}
		else if ('<?php echo $_SESSION['academic']['status'] ?>' == 2) {
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>closed.php")
		}
		if (<?php echo empty($rid) ? 1 : 0 ?> == 1)
			uni_modal("Information", "<?php echo $_SESSION['login_view_folder'] ?>done.php")
	})
	$('#manage-evaluation').submit(function (e) {
		e.preventDefault();
		start_load()
		$.ajax({
			url: 'ajax.php?action=save_evaluation',
			method: 'POST',
			data: $(this).serialize(),
			success: function (resp) {
				if (resp == 1) {
					alert_toast("Data successfully saved.", "success");
					setTimeout(function () {
						location.reload()
					}, 1750)
				}
			}
		})
	})
</script>