<div class="col-sm-12" id="featured">   
    <div class="page-header text-muted">
        Löschen der Zutat
    </div> 
</div>
<?php
if(is_numeric($_GET['rezeptid']))
	{
		$rezeptid = $_GET['rezeptid'];
		$ingredientId = $_GET['ingredientid'];

		if($connect->query("DELETE FROM ingredientsofrecipe WHERE recipe_id = ".$rezeptid." and ingredients_id = ".$ingredientId))
		{
			if($connect->query("DELETE FROM ingredients WHERE id = ".$ingredientId))
			{
				echo 'Erfolgreich gelöscht!<br>';
			}
		}
		echo '<div><a href="index.php?seite=rezeptansicht&id='.$rezeptid.'">Zurück zum Rezept</a></div>';
		echo mysqli_error($connect);
	}
?>