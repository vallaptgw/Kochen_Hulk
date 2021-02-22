<?php
	$stext =$_GET['suchtext'];
	$stext = str_replace("%", "", $stext);
?>
<div class="col-sm-12" id="featured">   
    <div class="page-header text-muted">
        <h1>Rezepte Suchen</h1>
    </div> 
</div>
<form action="" method="post">
	<div class="panel panel-default">
		<div class="panel-heading">		
			<b>Suchmuster:</b>
			<select name="Suchmuster">
				<option value="Name">Name</option>
				<option value="Zutaten">Zutaten</option>
			</select>
		</div>
		<div class="panel-body">
			<div class="input-group" style="max-width:470px;">
	        	<input type="text" name="Suchtext" class="form-control" placeholder="Rezept Suche" name="srch-term" id="srch-term">
	        	<div class="input-group-btn">
	        		<button class="btn btn-default btn-primary" name="search" type="submit"><i class="glyphicon glyphicon-search" style="color:#ffffff;"></i></button>
	        	</div>
    		</div>
    	</div>
	</div>
</form>

<?php
	if($stext != '')
	{
		$selectergebnis = $connect->prepare("select distinct recipe.Id,recipe.Name,recipe.description,images.imagelocation from recipe,images where recipe.images_id = images.id and Name LIKE('%".$stext."%')");
		$selectergebnis->execute();
		$selectergebnis->bind_result($id,$Name, $Description, $imagelocation);
		$selectergebnis->store_result();
		
		if($selectergebnis->num_rows != 0)
		{
			while($selectergebnis->fetch())
			{
				echo '
						<div class="col-sm-4 col-xs-6 speiseBox">
							<a href="index.php?seite=rezeptansicht&id='.$id.'">
								<div class="panel panel-default">
									<div class="panel-thumbnail">
										<img src="'.$imagelocation.'" class="img-responsive">
									</div>
									<div class="panel-body">
										<p class="lead">'.$Name.'</p>
									</div>
								</div>
							</a>
						</div>
					';
			}
		}
		else
		{
			echo '	<div>
						<div class="alert alert-info alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<strong>Sorry</strong>" In dieser Kategorie gibt es noch keine Rezepte.
						</div>
					</div>';
		}
	}
?>

<?php
	if(isset($_POST['search']))
	{
		$Suchtext = $_POST['Suchtext'];
		$Suchart = $_POST['Suchmuster'];
		
		if($Suchart == 'Name')
		{
			$selectergebnis = $connect->prepare("select distinct recipe.Id,recipe.Name,recipe.description,images.imagelocation from recipe,images where recipe.images_id = images.id and Name LIKE('%".$Suchtext."%')");
			$selectergebnis->execute();
			$selectergebnis->bind_result($id,$Name, $Description, $imagelocation);
			$selectergebnis->store_result();
			
			if($selectergebnis->num_rows != 0)
			{
				echo '
					<div class="row">';
				while($selectergebnis->fetch())
				{
					echo '<div class="col-sm-4 col-xs-6">
						<div class="panel panel-default">
							<div class="panel-thumbnail">
								<img src="'.$imagelocation.'" >
							</div>
							<div class="panel-body">
								<p class="lead"><a href="index.php?seite=rezeptansicht&id='.$id.'">'.$Name.'</a></p>
							</div>
						</div>
					  </div>';
				}
				echo '</div></div><hr>';
			}
			else
				{
					echo '	<div>
								<div class="alert alert-info alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
										<strong>Sorry</strong>" In dieser Kategorie gibt es noch keine Rezepte.
								</div>
							</div>';
				}
		}
		elseif($Suchart == 'Zutaten')
		{
			$selAllLehrberufeSchueler = $connect->prepare("SELECT distinct r.id,r.Name,r.description,im.imagelocation FROM ingredientsofrecipe ir, recipe r, ingredients i, images im WHERE ir.recipe_id = r.id AND ir.ingredients_id = i.id AND r.images_id = im.id AND i.Name LIKE('%".$Suchtext."%')");
			$selAllLehrberufeSchueler->execute();
			$selAllLehrberufeSchueler->bind_result($id,$Name, $Description, $imagelocation);
			$selAllLehrberufeSchueler->store_result();
			
			if($selAllLehrberufeSchueler->num_rows != 0)
			{
				echo '
					<div class="row">';
				while($selAllLehrberufeSchueler->fetch())
				{
					echo '<div class="col-sm-4 col-xs-6">
							<div class="panel panel-default">
								<div class="panel-thumbnail">
									<img src="'.$imagelocation.'" >
								</div>
								<div class="panel-body">
									<p class="lead"><a href="index.php?seite=rezeptansicht&id='.$id.'">'.$Name.'</a></p>
								</div>
							</div>
						  </div>';
				}
				echo '</div></div><hr>';
			}
			else
			{
				echo '	<div>
							<div class="alert alert-info alert-dismissable">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<strong>Sorry</strong>" In dieser Kategorie gibt es noch keine Rezepte.
							</div>
						</div>';
			}
		}
		
		echo mysqli_error($connect);
	}
?>