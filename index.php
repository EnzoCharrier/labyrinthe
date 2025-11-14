<!DOCTYPE html>		
    <html lang= "fr">

    <head>
	    <meta charset= "utf-8">	
	    <title>Labyrinthe</title>
        <link rel="stylesheet" href="style/style.css">
    </head>


    <body>

        <?php 
        
        include("include/header.php");
	    include("include/nav.php");

            // Ouvrir Base de Données
            $bdd_fichier = 'labyrinthe.db';	
	        $type = "depart";
	        $sqlite = new SQLite3($bdd_fichier);

            



            // Attribuer position de depart initial
            $initial='SELECT couloir.id, couloir.type FROM couloir WHERE type=:type'; //requete emplacement initial
            $requeteINIT = $sqlite -> prepare($initial);	
	        $requeteINIT -> bindValue(':type', $type, SQLITE3_TEXT);
	        $resultINIT = $requeteINIT -> execute();





            // Recuperer la valeur de l'id de la page
            while($requeteINIT = $resultINIT -> fetchArray(SQLITE3_ASSOC)) {
                    
                if (isset($_GET['couloir']) == $requeteINIT['id'])
                {

                    $empl = $_GET['couloir']; 

                }
                
                break;
            }

            



            if (isset($_GET['couloir']) == $empl)
            {

                // Definit la veleur de l'id par rapport a la valeur de la page
                $emplacement='SELECT couloir.id,couloir.type FROM couloir WHERE couloir.id =:empl';
                $requete = $sqlite -> prepare($emplacement);	
	            $requete -> bindValue(':empl', $empl, SQLITE3_TEXT);
	            $result = $requete -> execute();
                    
                while($requete = $result -> fetchArray(SQLITE3_ASSOC)) {
           
                    echo "<h1>Vous etes emplacement : " .$requete['id']."(type : " .$requete['type'].") <h1>";

                }





                // Recuperer les voisin de couloir2 si couloir2 = id
                $voisinDeCouloir2='SELECT couloir1, couloir2 FROM passage WHERE passage.couloir2=:empl'; // requete pour voir les voisin de couloir2
                $requeteVoisinCouloir2 = $sqlite -> prepare($voisinDeCouloir2);	
	            $requeteVoisinCouloir2 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	            $resultVoisinCouloir2 = $requeteVoisinCouloir2 -> execute();
        
                while($requeteVoisinCouloir2 = $resultVoisinCouloir2 -> fetchArray(SQLITE3_ASSOC)) {

                    echo "<h1>Vous etes voisin de " .$requeteVoisinCouloir2['couloir1']. " <h1>";

                }





                // Recuperer les voisin de couloir1 si couloir1 = id
                $voisinDeCouloir1='SELECT couloir2, couloir1 FROM passage WHERE passage.couloir1=:empl'; // requete pour voir les voisin de couloir1
                $requeteVoisinCouloir1 = $sqlite -> prepare($voisinDeCouloir1);	
	            $requeteVoisinCouloir1 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	            $resultVoisinCouloir1 = $requeteVoisinCouloir1 -> execute();

                while($requeteVoisinCouloir1 = $resultVoisinCouloir1 -> fetchArray(SQLITE3_ASSOC)) {

                    if ($requeteVoisinCouloir1['couloir2'] != $empl){

                        echo "<h1> Vous etes voisin de  ".$requeteVoisinCouloir1['couloir2']."  <h1>";

                    }

                }


                echo "<h1> Vous pouvez allez dans ces direction  : <h1>\n";





                // Recuperer les direction possible si couloir2 = id
                $position1='SELECT position1, position2 FROM passage WHERE passage.couloir2=:empl'; // requete pour voir la position liés l'emplacement actuelle
                $requetePos1 = $sqlite -> prepare($position1);	
	            $requetePos1 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	            $resultPos1 = $requetePos1 -> execute();

                while($requetePos1 = $resultPos1 -> fetchArray(SQLITE3_ASSOC)) {
                
                    echo "<h1> - ".$requetePos1['position1']."  <h1>";
                    
                }





                // Recuperer les direction possible si couloir1 = id
                $position2='SELECT position1, position2 FROM passage WHERE passage.couloir1=:empl'; // requete pour voir la position liés l'emplacement actuelle
                $requetePos2 = $sqlite -> prepare($position2);	
	            $requetePos2 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	            $resultPos2 = $requetePos2 -> execute();

                while($requetePos2 = $resultPos2 -> fetchArray(SQLITE3_ASSOC)) {
                
                    echo "<h1> - ".$requetePos2['position2']."  <h1>";
                
                }
            }




         // Fermer Base de Données
         $sqlite -> close();
         
        ?>



        </body>

    </html>

