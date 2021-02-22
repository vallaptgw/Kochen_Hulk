<form action="" method="post">
	<div class="panel panel-default">
		<div class="panel-heading">
			<div class="col-sm-12" id="featured">   
			    <div class="page-header text-muted">
			        <h1>Einheiten hinzufügen</h1>
			    </div> 
			</div>
		</div>
		<br>
		<div class="panel-body">
			<div class="input-group" style="max-width:500px;">
	        	<input type="text" name="UnitName" class="form-control" placeholder="Einheiten hinzufügen">
		        <div class="input-group-btn">
		        	<button class="btn btn-default btn-primary" name="add" type="submit"><i class="glyphicon glyphicon-plus" style="color:#ffffff;"></i></button>
	        	</div>
    		</div>
    	</div>
	</div>
</form>
<?php
	if(isset($_POST['add']))
	{
		
		$UnitsName = $_POST['UnitName'];
		
		if(!IsNullOrEmptyString($UnitsName))
		{
			$connect->query("insert into units (name) VALUES('".$UnitsName."')");
		}
		else
		{
			echo '	<div>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Sorry</strong> !  Keine Einheit eingetragen! ';
					echo '
				</div>
			</div>';
		}

		if(mysqli_error($connect))
		{
		  echo '	<div>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Sorry</strong> '; echo mysqli_error($connect);
					echo '
				</div>
			</div>';
		}
	}
?>