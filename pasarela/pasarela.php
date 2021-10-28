<?php
$numorden = $_GET['NumOrden'];
$valorden = $_GET['ValOrden'];
$nomusuar = $_GET['NomUsuar'];
$session = $_GET['Session'];

echo "<p> numero orden ".$numorden ."</p>";
echo  "<p> valor a pagar ".$valorden."</p>";
echo "<p> usuario ".$nomusuar."</p>";
${$variable}
?> 


<form action="pagar.php" method="post">
	 <input type="hidden" name="NumOrden" value=<?= $numorden ?>  />
	 <input type="hidden" name="ValOrden" value=<?= $valorden ?>/>
	 <input type="hidden" name="NomUsuar" value= <?= $nomusuar ?> />
	 <input type="submit" value="Pagar" />
   <input type="button" value="Cancelar" />
</form>