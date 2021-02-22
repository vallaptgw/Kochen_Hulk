<?php
	if(is_numeric($_GET['rezeptid']))
				{
					$rezeptid =$_GET['rezeptid'];
					
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


					$SelectResult = $connect->prepare("SELECT (SELECT SUM(s.wert)
															FROM starsofrecipe sr,stars s
															WHERE sr.`Sterne_id` = s.`id` 
															AND sr.recipe_id = ".$rezeptid.")/COUNT(*) AS 'wert'
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
        	<form action="" method="post">
	        	<div class="input-group" style="max-width:470px;">
			        <h3><input type="text" class="form-control" name="titletext" id="srch-term" value="<?php echo $RezeptName; ?>"></input>
			        	<div class="input-group-btn">
		                  	<button class="btn btn-default btn-primary" name="savetitlebutton" type="submit" ><i class="glyphicon glyphicon-save" style="color:#ffffff"></i></button>
		                </div>
		            </h3>
		        </div>
	    	</form>
	        <h5>Bewertung: <?php echo $Bewertungswert; ?></h5>
	    </div>
    	<?php echo '<a href="index.php?seite=delrezept&rezeptid='.$rezeptid.'">'; ?>
    	<button class="btn btn-lg btn-primary btn-rotate" type="button"><i class="glyphicon glyphicon-trash"  style="color:#FFFFFF;"></i></button></a>
    	<?php echo '<a href="index.php?seite=rezeptansicht&id='.$rezeptid.'">'; ?>
    	<button class="btn btn-lg btn-primary btn-rotate" type="button"><i class="glyphicon glyphicon-arrow-left"  style="color:#FFFFFF;"></i></button></a>
    	<div class="panel-body">
    		<form action="" method="post">
	    		<div class="input-group" style="max-width:500px;">
					<b>Kategorien: </b>
					<select name="Kategorien">
									<?php
										$selAllBestellungen = $connect->prepare('select * from categories order by id');
										$selAllBestellungen->execute();
										$selAllBestellungen->bind_result($ID,$Name);
										while($selAllBestellungen->fetch())
										{
											echo '<option value="'.$ID.'">'.$Name.'</option>';
										}
									?>
					</select>
					<div>
						<button class="btn btn-default btn-primary" name="savenewcategorie" value="speichern" type="submit"><i class="glyphicon glyphicon-plus" style="color:#ffffff;"> Kategorie ändern </i></button>
					</div>
				</div>
			</form>
    		<div class="row">
    			<div class="col-md-12 visible-xs visible-sm">
			    	<?php echo '<img src="'.$Imagelocation.'" class="img-responsive">'; ?>
			        <div class="clearfix"></div>
    			</div>
			    <div class="col-md-8">
			        <h4>Zubereitung: </h4>
			        <form action="" method="post">
			        	<textarea cols="80" rows="7" name="RezeptBeschreibung" ><?php echo $RezeptDescription; ?></textarea>
		                <button class="btn btn-default btn-primary" name="savebeschreibungbutton" type="submit"><i class="glyphicon glyphicon-save" style="color:#ffffff"></i></button>
			        </form>
			    </div>
				<div class="col-md-4 speiseDetailImg hidden-xs hidden-sm">
			    	<?php echo '<img src="'.$Imagelocation.'" class="img-responsive">'; ?>
			        <div class="clearfix"></div>
			    </div>
			</div>
			<div class="row">
				<div class="col-xs-12 col-md-12">
<?php
	if(is_numeric($_GET['rezeptid']))
	{
		$rezeptid =$_GET['rezeptid'];
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
					<td>';
						echo '<a href="index.php?seite=delzutat&ingredientid='.$IngredientId.'&rezeptid='.$rezeptid.'">
    					<button class="btn btn-lg btn-primary btn-rotate" type="button"><i class="glyphicon glyphicon-trash"  style="color:#FFFFFF;"></i></button></a>
    				</td>
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
	if(isset($_POST['savetitlebutton']))
	{
		
		$Titletext = $_POST['titletext'];
		if(!IsNullOrEmptyString($Titletext))
		{
			$connect->query("UPDATE recipe SET name = '".$Titletext."' WHERE id = ".$rezeptid);
			echo '	<div>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Info</strong> !  Erfolgreich upgedated! ';
					echo '
				</div>
			</div>';
		}
		else
		{
			echo '	<div>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Achtung</strong> !  Bitte einen Titel eingetragen! ';
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



<?php
	if(isset($_POST['savebeschreibungbutton']))
	{
		
		$Rezeptbeschreibungstext = $_POST['RezeptBeschreibung'];
		if(!IsNullOrEmptyString($Rezeptbeschreibungstext))
		{
			$connect->query("UPDATE recipe SET description = '".$Rezeptbeschreibungstext."' WHERE id = ".$rezeptid);
			echo '	<div>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Info</strong> !  Erfolgreich upgedated! ';
					echo '
				</div>
			</div><meta http-equiv="refresh" content=0; href="templates/rezeptbearbeiten.php">';
		}
		else
		{
			echo '	<div>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Achtung</strong> !  Bitte einen Titel eingetragen! ';
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

<?php
	if(isset($_POST['savenewcategorie']))
	{
		
		$Kategorie = $_POST['Kategorien'];
		if(!IsNullOrEmptyString($Kategorie))
		{
			$connect->query("UPDATE recipesofcategories SET categories_id = '".$Kategorie."' WHERE recipe_id = ".$rezeptid);
			echo '	<div>
						<div class="alert alert-info alert-dismissable">
							<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
								<strong>Info</strong> !  Erfolgreich upgedated! ';
							echo '
						</div>
					</div>';
		}
		else
		{
			echo '	<div>
				<div class="alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<strong>Achtung</strong> !  Bitte einen Titel eingetragen! ';
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