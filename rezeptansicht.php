<?php
	if(is_numeric($_GET['id']))
				{
					$rezeptid =$_GET['id'];
					
					$SelectResult = $connect->prepare("SELECT recipe.id,recipe.name,recipe.description,images.`ImageLocation` from recipe,images where recipe.`Images_id` = images.`id` and recipe.id = ".$rezeptid);

					$SelectResult->execute();
					$SelectResult->bind_result($id,$Name, $Description,$Imagelocation);
					$SelectResult->store_result();
					
					if($SelectResult->num_rows != 0)
					{
						while($SelectResult->fetch())
						{
							$RezeptName = $Name;
							$RezeptDescription = $Description;
						}
					}
					else
					{
					echo '	<div>
								<div class="alert alert-info alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<strong>Sorry</strong>Es sind keine Rezepte vorhanden
								</div>
							</div>';
					}


					$SelectResult = $connect->prepare("SELECT round((SELECT SUM(s.wert)
															FROM starsofrecipe sr,stars s
															WHERE sr.`Sterne_id` = s.`id` 
															AND sr.recipe_id = ".$rezeptid.")/COUNT(*),1) AS 'wert'
														FROM starsofrecipe sr,stars s
														WHERE sr.`Sterne_id` = s.`id` 
														AND sr.recipe_id = ".$rezeptid);

					$SelectResult->execute();
					$SelectResult->bind_result($wert);
					$SelectResult->store_result();
					
					if($SelectResult->num_rows != 0)
					{
						while($SelectResult->fetch())
						{
							$Bewertungswert = $wert;
						}
					}
					else
					{
						echo '	<div>
								<div class="alert alert-info alert-dismissable">
									<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
									<strong>Sorry</strong>Keine Bewertung verfügbar!
								</div>
							</div>';
					}
					
					echo mysqli_error($connect);
				}

?>


	<div class="panel panel-default">
        <div class="panel-heading">
	        <h3><?php echo $RezeptName; ?></h3>
	        <h4>Sterne: <?php echo $Bewertungswert; ?></h4>
	    </div>
    	<?php echo '<a href="index.php?seite=rezeptbearbeiten&rezeptid='.$rezeptid.'">'; ?>
    	<button class="btn btn-lg btn-primary btn-rotate" type="button"><i class="glyphicon glyphicon-wrench" style="color:#FFFFFF;"></i></button></a>
    	<?php echo '<a href="index.php?seite=kochbuch">'; ?>
    	<button class="btn btn-lg btn-primary btn-rotate" type="button"><i class="glyphicon glyphicon-book" style="color:#FFFFFF;"></i></button></a>
    	<hr>
    	<form action="" enctype="multipart/form-data" method="post">
	    	<div class"btn-group">
	    		<input type="submit" class="btn btn-lg btn-primary btn-rotate" type="button" name="stern1"  value="1">
	    		<input type="submit" class="btn btn-lg btn-primary btn-rotate" type="button" name="stern2"  value="2">
	    		<input type="submit" class="btn btn-lg btn-primary btn-rotate" type="button" name="stern3"  value="3">
	    		<input type="submit" class="btn btn-lg btn-primary btn-rotate" type="button" name="stern4"  value="4">
	    		<input type="submit" class="btn btn-lg btn-primary btn-rotate" type="button" name="stern5"  value="5">
	    	</div>
    	</form>
    	<div class="panel-body">
    		<div class="row">
    			<div class="col-md-12 visible-xs visible-sm">
			    	<?php echo '<img src="'.$Imagelocation.'" class="img-responsive">'; ?>
			        <div class="clearfix"></div>
    			</div>
			    <div class="col-md-8">
			        <h4>Zubereitung: </h4>
				    <p>
				        <?php
							$RezeptDescription=str_replace("rn","[br]",$RezeptDescription); 
							$RezeptDescription=strip_tags($RezeptDescription); 
							$RezeptDescription=str_replace("[br]","<br>",$RezeptDescription);  
				        	echo $RezeptDescription;
				        ?>
			    	</p>
			    </div>
				<div class="col-md-4 speiseDetailImg hidden-xs hidden-sm">
			    	<?php echo '<img src="'.$Imagelocation.'" class="img-responsive">'; ?>
			        <div class="clearfix"></div>
			    </div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-12">
<?php
	if(is_numeric($_GET['id']))
	{
		$rezeptid =$_GET['id'];
		?>
		<h4>Zutaten:</h4>
		<?php
		
		$SelectResult = $connect->prepare("select i.id,i.name,i.amount,u.name from recipe r, ingredientsofrecipe ir, ingredients i, units u where ir.ingredients_id = i.id and ir.recipe_id = r.id and i.units_id = u.id and r.id = ".$rezeptid);
		$SelectResult->execute();
		$SelectResult->bind_result($IngredientId,$Name, $Amount, $Description);
		$SelectResult->store_result();
		
		if($SelectResult->num_rows != 0)
		{
			echo '<table class="table">';
			while($SelectResult->fetch())
			{
				echo
				'<tr>
					<td>'.$Name.'</td>
					<td>'.$Amount.'</td>
					<td>'.$Description.'</td>
				</tr>
				';
			}
		}
		else
		{
			echo '<tr><td colspan="4">Keine Zutaten vorhanden!</td></tr>';
		}
		echo '
			</table></div>';
		
		echo mysqli_error($connect);
		
		
	}
?>
		</div>
    </div>
    </div>
</div>

<?php
	if(isset($_POST['stern1']))
	{
		
		$rezeptid =$_GET['id'];
		$connect->query("insert into starsofrecipe (recipe_id,sterne_id) VALUES(".$rezeptid.",1)");
		echo mysqli_error($connect);
		echo '<meta http-equiv="refresh" content=0; href="templates/rezeptansicht.php">';
	}
?>

<?php
	if(isset($_POST['stern2']))
	{
		
		$rezeptid =$_GET['id'];
		$connect->query("insert into starsofrecipe (recipe_id,sterne_id) VALUES(".$rezeptid.",2)");
		echo mysqli_error($connect);
		echo '<meta http-equiv="refresh" content=0; href="templates/rezeptansicht.php">';

	}
?>

<?php
	if(isset($_POST['stern3']))
	{
		
		$rezeptid =$_GET['id'];
		$connect->query("insert into starsofrecipe (recipe_id,sterne_id) VALUES(".$rezeptid.",3)");
		echo mysqli_error($connect);
		echo '<meta http-equiv="refresh" content=0; href="templates/rezeptansicht.php">';
	}
?>

<?php
	if(isset($_POST['stern4']))
	{
		
		$rezeptid =$_GET['id'];
		$connect->query("insert into starsofrecipe (recipe_id,sterne_id) VALUES(".$rezeptid.",4)");
		echo mysqli_error($connect);
		echo '<meta http-equiv="refresh" content=0; href="templates/rezeptansicht.php">';
	}
?>

<?php
	if(isset($_POST['stern5']))
	{
		echo 'rated!';
		$rezeptid =$_GET['id'];
		$connect->query("insert into starsofrecipe (recipe_id,sterne_id) VALUES(".$rezeptid.",5)");
		echo mysqli_error($connect);
		echo '<meta http-equiv="refresh" content=0; href="templates/rezeptansicht.php">';
	}
?>
