<?php

session_start();

require 'app/autoloader.php';
app\autoloader::register();

if (isset($_POST['createArtiste'])) {
    $queryArtiste = "SELECT * FROM artiste WHERE NOMARTISTE = '" . $_POST['NomArtiste'] . "' AND PRENOMARTISTE = '" . $_POST['PrenomArtiste'] . "'";
    $resultArtiste = app\data::getDB()->queryFetchAll($queryArtiste);
    $count = count($resultArtiste);
    if ($count > 0) {
        ob_start();
        echo "Cet artiste existe déjà, vous allez être redirigé vers la page principal.";
        $content = ob_get_clean();
        ob_start();
        echo '<meta http-equiv="refresh" content="3;url=index.php" />';
        $head = ob_get_clean();
    } else {
        ob_start();
        echo "Enregistrement de votre profil, vous allez être redirigé dans quelques secondes.";
        $content = ob_get_clean();
        $req = "INSERT INTO artiste (NOMARTISTE, PSEUDO, PRENOMARTISTE) values ('" . $_POST['NomArtiste'] . "', '" . $_POST['PseudoArtiste'] . "', '" . $_POST['PrenomArtiste'] . "')";
        app\data::getDB()->exec($req);
        $queryArtiste = "SELECT * FROM artiste WHERE NOMARTISTE = '" . $_POST['NomArtiste'] . "' AND PRENOMARTISTE = '" . $_POST['PrenomArtiste'] . "'";
        $resultArtiste = app\data::getDB()->queryFetchAll($queryArtiste);
        $_SESSION['Artiste'] = $resultArtiste[0];
        ob_start();
        echo '<meta http-equiv="refresh" content="3;url=index.php" />';
        $head = ob_get_clean();
    }
}

//Verification si l'artiste est log, si il ne l'est pas on lui propose de se log ou de créer un profil artiste.
if (isset($_SESSION['Artiste'])) {
    ob_start();
    echo "Bienvenu sur votre page de gestion de rendez-vous";
    $reqDates = "SELECT  DATE_FORMAT(pl.DATESESSION, '%d/%m/%Y')Date, pl.LIBSESSION Nom, pl.COMMENTAIRE Commentaire,g.NOMGROUPE Groupe,s.NOMSTUDIO Studio,s.IDADDRESSE idadresse FROM planifier pl 

        JOIN pratiquer p     ON p.IDARTISTE     = '1'
        JOIN studio s         ON s.IDSTUDIO     = pl.IDSTUDIO
        JOIN groupe g         ON g.IDGROUPE     = pl.IDGROUPE

        ORDER BY Date, Groupe, Studio";
    $resultDates = app\data::getDB()->queryFetchAll($reqDates);
    $countDates = count($resultDates);
    if($countDates <= 0)    {  
        echo "<p>Aucunes dâtes de prévus</p>";
    }   elseif($countDates > 0) {
        ?>
        <p>
        <table>
            <tr>
                <th>Date</th>
                <th>Nom de la session</th>
                <th>Groupe</th>
                <th>Studio</th>
                <th>Adresse</th>
                <th>Commentaire</th>
            </tr>
        <?php
        foreach ($resultDates as $dates)    {
            $reqStud = "SELECT * FROM coordonnees WHERE IDADDRESSE = ". $dates['idadresse'];
            $resultStud = app\data::getDB()->queryFetchAll($reqStud);
            $resultStud = $resultStud[0];
            echo    "<tr>"
                    . "<td>" . $dates['Date'] . "</td>"
                    . "<td>" . $dates['Nom'] . "</td>"
                    . "<td>" . $dates['Groupe'] . "</td>"
                    . "<td>" . $dates['Studio'] . "</td>"
                    . "<td>" . $resultStud['NUMERO'] . " " . $resultStud['LIBADRESSE1'] . ", " . $resultStud['CODEPOSTAL'] . "    " . $resultStud['LIBADRESSE2'] . "</td>"
                    . "<td>" . $dates['Commentaire'] . "</td>";
        }
        echo "</table></p>";
        echo "<p><a href='addDate.php'>Ajouter une date d'enregistrement</a></p>";
    }
    $content = ob_get_clean();
} else {
    if (isset($_POST['prenom']) and isset($_POST['name'])) {
        $reqArtiste = "SELECT * FROM artiste WHERE NOMARTISTE = '" . $_POST['name'] . "' AND PRENOMARTISTE = '" . $_POST['prenom'] . "'";
        $resultArtiste = app\data::getDB()->queryFetchAll($reqArtiste);
        $count = count($resultArtiste);
        if ($count > 0) {
            ob_start();
            echo "Bonjour " . $resultArtiste[0]['PSEUDO'] . ", vous allez être redirigé vers la page principale dans quelques secondes";
            $content = ob_get_clean();
            $_SESSION['Artiste'] = $resultArtiste[0];
            ob_start();
            echo '<meta http-equiv="refresh" content="3;url=index.php" />';
            $head = ob_get_clean();
        } else {
            ob_start();
            ?>
            Création d'un profil d'artiste : 
            <form action="#" method="post">
                Nom : <input type="text" name="NomArtiste" placeholder="George" required="required" value="<?= $_POST['name'] ?>"/><br>
                Prénom : <input type="text" name="PrenomArtiste" placeholder="Dupont" required="required" value="<?= $_POST['prenom'] ?>"/><br>
                Pseudo : <input type="text" name="PseudoArtiste" placeholder="Oxmo puccino" required="required"/><br>
                <input type="submit" value="Envoyer" name="createArtiste"/>
            </form>
            <?php
            $content = ob_get_clean();
        }
    } elseif (!isset($_POST['createArtiste'])) {
        ob_start()
        ?>
        Entrez votre nom et prénom
        <p><form action='#' method='post'>
            Nom : <input name="name" type='text' placeholder="George" required="required"/><br>
            Prenom : <input name="prenom" type='text' placeholder="dupont"  required="required"/><br>
            <input type='submit' value="Confirmer"/>
        </form></p>
        <?php
        $content = ob_get_clean();
    }
}
?>
<html>
    <head>
        <title>TODO supply a title</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="public/style.css" />
<?php
if (isset($head)) {
    echo $head;
}
?>
    </head>
    <body>
        <div><center><?= $content ?></center></div>
    </body>
</html>
