<?php include('db_connect.php');?>

<div class="container-fluid">
	
	<div class="col-lg-12">
		<div class="row">
			<!-- FORM Panel -->
			<div class="col-md-4">
			<form action="" id="manage-course">
				<div class="card">
					<div class="card-header">
						    Thêm khóa học mới
				  	</div>
					<div class="card-body">
							<input type="hidden" name="id">
							<div id="msg"></div>
							<div class="form-group">
								<label class="control-label">Khóa học</label>
								<input type="text" class="form-control" name="course">
							</div>
							<div class="form-group">
								<label class="control-label">Miêu tả</label>
								<textarea name="description" id="" cols="30" rows="4" class="form-control"></textarea>
							</div>
					</div>
							
					<div class="card-footer">
						<div class="row">
							<div class="col-md-12">
								<button class="btn btn-sm btn-primary col-sm-3 offset-md-3"> Lưu</button>
								<button class="btn btn-sm btn-default col-sm-3" type="reset"> Hủy bỏ</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			</div>
			<!-- FORM Panel -->

			<!-- Table Panel -->
			<div class="col-md-8">
				<div class="card">
					<div class="card-header">
						<b>Danh sách các khóa học</b>
					</div>
					<div class="card-body">
						<table class="table table-bordered table-hover">

							<thead>
								<tr>
									<th class="text-center" width="5%">#</th>
									<th class="text-center">Khóa học</th>
									<th class="text-center" width="25%">Action</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$i = 1;
								$course = $conn->query("SELECT * FROM courses order by id asc");
								while($row=$course->fetch_assoc()):
								?>
								<tr>
									<td class="text-center"><?php echo $i++ ?></td>
									<td class="">
										<p><b><?php echo ucwords($row['course']) ?></b></p>
										<small><i><?php echo $row['description'] ?></i></small>
									</td>
									<td class="text-center">
										<button class="btn btn-sm btn-primary edit_course" type="button" data-id="<?php echo $row['id'] ?>"  data-course="<?php echo $row['course'] ?>"  data-description="<?php echo $row['description'] ?>" >Sửa</button>
										<button class="btn btn-sm btn-danger delete_course" type="button" data-id="<?php echo $row['id'] ?>">Xóa</button>
									</td>
								</tr>
								<?php endwhile; ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
			<!-- Table Panel -->
		</div>
	</div>	

</div>
<style>
	
	td{
		vertical-align: middle !important;
	}
	td p {
	    margin: unset;
	}
</style>
<script>
	$('#manage-course').on('reset',function(){
		$('#msg').html('')
		$('input:hidden').val('')
	})
	$('#manage-course').submit(function(e){
		e.preventDefault()
		$('#msg').html('')
		start_load()
		$.ajax({
			url:'ajax.php?action=save_course',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp==1){
					alert_toast("Dữ liệu được lưu thành công",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}else if(resp == 2){
				$('#msg').html('<div class="alert alert-danger mx-2">Khóa học đã tồn tại.</div>')
				end_load()
				}			
			}
		})
	})
	$('.edit_course').click(function(){
		start_load()
		var cat = $('#manage-course')
		cat.get(0).reset()
		cat.find("[name='id']").val($(this).attr('data-id'))
		cat.find("[name='course']").val($(this).attr('data-course'))
		cat.find("[name='description']").val($(this).attr('data-description'))
		end_load()
	})
	$('.delete_course').click(function(){
		_conf("Bạn có chắc chắn xóa khóa học này không?","delete_course",[$(this).attr('data-id')])
	})
	function delete_course($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_course',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Dữ liệu đã được xóa thành công",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	$('table').dataTable()
</script>