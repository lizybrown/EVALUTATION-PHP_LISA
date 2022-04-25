<?php
    session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title> Accueil Articles </title>
</head>
<body>
<?php
    include "pdo.php";
    include "requete.php";


    if (!empty($_SESSION['users'])) {
        header('location: index.php');
    }
    ?>

    <div class="inscription">
        <div class="column">
            <div class="col-md-6 mx-auto">
                <form action="" method="POST" class="mb-5">
                    <div class="mb-3">
                        <label for="pseudo" class="form-label">Pseudo</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-outline-dark" name="btn_inscription">Inscription</button>
                </form>
            </div>
        </div>
    </div>

    <?php

        if (isset($_POST['btn_inscription'])) {

            //Je cherche si le pseudo saisi dans le formulaire, existe déjà en BdD
            $users = requete_findUser($_POST['pseudo']);

            // J'initialise une variable à 0, cette variable nous permettra d'adapter les messages d'erreur ou de succés sur la page connexion.php
            $existe = 0;

            //l'utilisateur existe déjà?
            if ($users){

                $existe = 1;
                header("location: connexion.php?existe=".$existe);

            } else {

                $mdp = password_hash($_POST['password'], PASSWORD_DEFAULT);

                requete_inscription($_POST['pseudo'],$mdp,1);

           
                header("location: connexion.php?existe=".$existe);
            }
        }
    ?>

<section class="article_presentation">

<h1>
    page articles 
</h1>
<?php


if (isset($_GET['errors'])) {
?>
  
    <div class="cards">
        <?php

        if ($_GET['errors'] === "existe")
            echo "L'utilisateur que vous souhaitez ajouter, est déjà inscrit";

        else if ($_GET['errors'] === "taille")
            echo "La taille de l'image dépasse 100 Ko";

        else if ($_GET['errors'] === "format")
            echo "Votre image doit être un jpg, jpeg ou png";

        else if ($_GET['errors'] === "erreur")
            echo "Le fichier uploadé n'est pas une image";

        else if ($_GET['errors'] === "ajout")
            echo "Une erreur est survenue lors de l'envoie du formulaire";

        else if ($_GET['errors'] === "fichier")
            echo "Veuillez choisir une image";
        ?>
    </div>
<?php


} else if (isset($_GET['success'])) {
?>
   
    <div class="container alert alert-success text-center">
    <?php
 
    if ($_GET['success'] === "ajout") {
        echo "Ajout effectuée";
    } else if ($_GET['success'] === "modif") {
        echo "Modification effectuée";
    } else if ($_GET['success'] === "suppr") {
        echo "Suppression effectuée";
    }
}
    ?>
    </div>

    <div class="container">

        <?php

    $users = requete_displayUsers();

        foreach ($users as $value) {
        ?>
          
            <div class="card mx-auto mb-3" style="width: 18rem;">
                <img src="images/<?= htmlspecialchars($value->vignette) ?>" class="card-img-top" alt="..." style="object-fit: cover; height: 250px;">
                <div class="card-body">

                    <h5 class="card-title"><?= htmlspecialchars($value->pseudo) ?></h5>

                    <?php

if (isset($_GET['errors'])) {
?>
   
    <div class="container alert alert-danger text-center">
        <?php

        if ($_GET['errors'] === "existe")
            echo "L'utilisateur que vous souhaitez ajouter, est déjà inscrit";

        else if ($_GET['errors'] === "taille")
            echo "La taille de l'image dépasse 100 Ko";

        else if ($_GET['errors'] === "format")
            echo "Votre image doit être un jpg, jpeg ou png";

        else if ($_GET['errors'] === "erreur")
            echo "Le fichier uploadé n'est pas une image";

        else if ($_GET['errors'] === "ajout")
            echo "Une erreur est survenue lors de l'envoie du formulaire";

        else if ($_GET['errors'] === "fichier")
            echo "Veuillez choisir une image";
        ?>
    </div>
<?php


} else if (isset($_GET['success'])) {
?>
    <!-- Si oui, j'affiche une div -->
    <div class="container alert alert-success text-center">
    <?php
    
    //Et en fonction du contenu de $_GET['success'], j'affiche un message
    if ($_GET['success'] === "ajout") {
        echo "Ajout effectuée";
    } else if ($_GET['success'] === "modif") {
        echo "Modification effectuée";
    } else if ($_GET['success'] === "suppr") {
        echo "Suppression effectuée";
    }
}
    ?>
    </div>

    <div class="container">

        <?php

        
        foreach ($users as $value) {
        ?>
            
            <div class="card mx-auto mb-3" style="width: 18rem;">
                <img src="images/<?= htmlspecialchars($value->vignette) ?>" class="card-img-top" alt="..." style="object-fit: cover; height: 250px;">
                <div class="card-body">

                    <h5 class="card-title"><?= htmlspecialchars($value->pseudo) ?></h5>

                    <p class="card-text"><?= htmlspecialchars($value->article) ?></p>

                    <p class="card-text"><?= htmlspecialchars($value->vignette) ?></p>

                    <a href="index.php?id=<?= $value->id_users ?>" class="btn btn-outline-dark">lire cette article </a>

                    <a href="modifier.php?name=<?= $value->users_pseudo ?>&article=<?= $value->users_article ?>&img=<?= $value->users_vignette ?>&id=<?= $value->id_users ?>" 
                    class="btn btn-outline-dark">MODIFIER</a>
                </div>
            </div>

        <?php
        }   

        ?>

    </div>

    <div class="container mt-5 mb-5 d-flex justify-content-center">
        
        <a href="#" class="btn btn-dark">RETOUR HAUT</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

                </div>
            </div>

        <?php
        }

        ?>

    </div>


</section>

    
</body>
</html>
    
