<?php
// Initialisation de la section
session_start();
 
 //ma base de donnée
// Inclus notre composant d'accès à la base de donnée
require_once "../database/db.php";
 
// Définissez les variables et initialisez avec des valeurs vides pour gerer nos messages d'erreur
$email = $motDePasse = "";
$email_err = $motDePasse_err = $error = "";
 
// Traitement des données du formulaire lors de la soumission du formulaire
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // verifier email
    if(empty(trim($_POST["email"]))){
        $email_err = "Veuillez renseigner votre adresse email.";
    }else{
        $email = trim($_POST["email"]);
    }
    // verifer mot de passe 
    if(empty(trim($_POST["motDePasse"]))){
        $motDePasse_err = "Veuillez renseigner votre mot de passe.";
    } else{
        $motDePasse = trim($_POST["motDePasse"]);
    }
    
    // Valider les informations d'identification
    if(empty($email_err) && empty($motDePasse_err)){
        $sql = "SELECT utilisateurs.id, utilisateurs.nom, utilisateurs.prenom, utilisateurs.contact, utilisateurs.adresse, utilisateurs.email, utilisateurs.motDePasse, roles.intituleRole FROM utilisateurs LEFT JOIN roles ON roles.id=utilisateurs.idRole WHERE utilisateurs.email = :email";
        
        if($stmt = $db->prepare($sql)){
            // Liaison des variables à l'instruction préparée en tant que paramètres
            $stmt->bindParam(":email", $param_email, PDO::PARAM_STR);
            $param_email = trim($_POST["email"]);
            // Tentative d'exécution de l'instruction préparée
            if($stmt->execute()){
                // Vérifiez si l'email existe, si oui, vérifiez le mot de passe
                if($stmt->rowCount() == 1){
                    if($result = $stmt->fetch()){
                        //var_dump($result);exit();
                        $id = $result["id"];
                       /*  recuperation d'un objet
                        $id = $result->id; */
                        $id = $result["id"];
                        $email = $result["email"];
                        $nom = $result["nom"];
                        $prenom = $result["prenom"];
                        $contact = $result["contact"];
                        $adresse = $result["adresse"];
                        $password = $result["motDePasse"];
                        $role = $result["intituleRole"];
                        //var_dump($role);exit();
                        if(password_verify(trim($_POST['motDePasse']), $password)){
                            
                            session_start();
                            
                            // Stockage des données dans des variables de session
                            $_SESSION["connecter"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["email"] = $email;
                            $_SESSION["prenom"] = $prenom;
                            $_SESSION["nom"] = $nom;
                            $_SESSION["contact"] = $contact;
                            $_SESSION["adresse"] = $adresse;
                            $_SESSION["role"] = $role;

                            if($_SESSION["role"] === "ROLE_ADMIN"){
                                header("location: ./super-admin/home.php");
                            }elseif ($_SESSION["role"] === "ROLE_OPERATEUR"){
                                header("location: ./operateurs/home.php");
                            }
                            elseif($_SESSION["role"] === "ROLE_SUPERVISEUR"){
                                header("location: ./superviseur/home.php");
                            }
                            else{
                                header("location: index.php");
                            }

                        } else{
                            $motDePasse_err = "Le mot de passe que vous avez entré n'est pas valide.";
                        }
                    }
                } else{
                    $email_err = "Aucun compte n'a été trouvé avec cet e-mail.";
                }
            } else{
                $error = "Oops! Quelque chose a mal tourné. Veuillez réessayer.";
            }
            unset($stmt);
        }
    }
    // Fermer la connexion à la base de donnée
    unset($db);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | BAD-EVENT</title>
    <link rel="stylesheet" href="./bootstrap/css/mStyle.css">
    <link rel="icon" type="image/png" href="./upload/Bad-event_logo.svg" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
    <section class="container">
        <div class="row mt-5 mb-5 my-3">
            <section class="monformulaire mt-5 p-3">
            <!-- le $_SERVER["PHP_SELF"] envoie les données de formulaire soumises à la page elle-même, au lieu de sauter à une page différente. De cette façon, l’utilisateur recevra des messages d’erreur sur la même page que le formulaire. -->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="row-form">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" name="email" id="email" class="form-control form-control-dangerview">
                    <i style="color: #ff1300 !important" >
                        <?php echo $email_err; ?>
                    </i>
                </div>
                <div class="form-group">
                    <label for="password">Mot de Passe</label>
                    <input type="password"  class="form-control form-control-dangerview password-control" name="motDePasse" id="motDePasse">
                    <i style="color: #ff1300 !important" >
                        <?php echo $motDePasse_err; ?>
                    </i>
                </div>
                <div class="form-group">
                    <i style="color: #ff1300 !important" >
                        <?php echo $error; ?>
                    </i>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="btn btn-outline-secondary">Se Connecter</button>   
                </div>
            </form>
            </section>
        </div>
    </section>
    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>