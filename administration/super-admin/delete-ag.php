<?php
require_once "./data/db.php";
session_start();

if(isset($_POST['id']))
{
    $id = $_POST['id'];

    $sql = "DELETE FROM utilisateurs WHERE id='$id' ";
    $query = $db->prepare($sql);
    $query -> execute();
    $newActivite = [
        ':activite'     => 'Suppression dun utilisateur',
        ':dateactivite' => date("Y-m-d H:i:s"),
        ':iduser'       => $_SESSION['id']
    ];
    //ajout d'activité
 
    $activite = "INSERT  INTO activites (intituleActivite, periode, idUtilisateur) VALUES ( :activite, :dateactivite, :iduser)";
  
    $rActivite = $db->prepare($activite)->execute($newActivite);
    echo 1;
    exit;
}


?>