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
            $typeSortie = "sortie";
	        $sqlite = new SQLite3($bdd_fichier);
            $nbCle = 0;
            $empl = $_GET['couloir'];
            
            
            



            // Attribuer position de depart initial
            $initial='SELECT couloir.id, couloir.type FROM couloir WHERE type=:type'; //requete emplacement initial
            $requeteINIT = $sqlite -> prepare($initial);	
	        $requeteINIT -> bindValue(':type', $type, SQLITE3_TEXT);
	        $resultINIT = $requeteINIT -> execute();





            // Recuperer la valeur de l'id de la page
            while($requeteINIT = $resultINIT -> fetchArray(SQLITE3_ASSOC)) {
                    
                  $init = $requeteINIT['id']; 
            }
                    
                
                

            if (isset($_GET['couloir']) == $empl)
            {
             
            
            //Verifier si le type = sortie et change de page dans ce cas
            $verifPartie='SELECT couloir.id,couloir.type FROM couloir WHERE couloir.type =:typeSortie';
                $requeteVerif = $sqlite -> prepare($verifPartie);	
	            $requeteVerif -> bindValue(':typeSortie', $typeSortie, SQLITE3_TEXT);
	            $resultVerif = $requeteVerif -> execute();

                while($requeteVerif = $resultVerif -> fetchArray(SQLITE3_ASSOC)) {

                    if ($empl == $requeteVerif['id']){

                        include("pages/pageFin.php");
                        echo "<h1><a href ='index.php?couloir=13'>  Recommencer</a></h1>";
                    }

                     else
                    {

                        // Donne l'emplacement actuel
                        $emplacement='SELECT couloir.id,couloir.type FROM couloir WHERE couloir.id =:empl';
                        $requete = $sqlite -> prepare($emplacement);	
	                    $requete -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                    $result = $requete -> execute();
                    
                        while($requete = $result -> fetchArray(SQLITE3_ASSOC)) {
                            
                            if ($requete['type'] == "cle"){
                               
                                $nbCle = 1;
                                
                            }
                            

                            echo "<h1>Vous etes emplacement : " .$requete['id']."(type : " .$requete['type']."), cle :".$nbCle." </h1>";
                            
                        }
                        

                        /*
                        while (){
                            // Recuperer les voisin de couloir2 si couloir2 = id
                            $voisinDeCouloir2='SELECT couloir1, couloir2 FROM passage WHERE passage.couloir2=:empl'; // requete pour voir les voisin de couloir2
                            $requeteVoisinCouloir2 = $sqlite -> prepare($voisinDeCouloir2);	
	                        $requeteVoisinCouloir2 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                        $resultVoisinCouloir2 = $requeteVoisinCouloir2 -> execute();
        
                            whil    e($requeteVoisinCouloir2 = $resultVoisinCouloir2 -> fetchArray(SQLITE3_ASSOC)) {

                            //echo "<h1>Vous etes voisin de " .$requeteVoisinCouloir2['couloir1']. " </h1>";
                            }
                        

                        
                            // Recuperer les voisin de couloir1 si couloir1 = id
                            $voisinDeCouloir1='SELECT couloir2, couloir1 FROM passage WHERE passage.couloir1=:empl'; // requete pour voir les voisin de couloir1
                            $requeteVoisinCouloir1 = $sqlite -> prepare($voisinDeCouloir1);	
	                        $req    ueteVoisinCouloir1 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                        $resultVoisinCouloir1 = $requeteVoisinCouloir1 -> execute();
                        

                        
                            while($requeteVoisinCouloir1 = $resultVoisinCouloir1 -> fetchArray(SQLITE3_ASSOC)) {

                            if ($requeteVoisinCouloir1['couloir2'] != $empl){

                               // echo "<h1> Vous etes voisin de  ".$requeteVoisinCouloir1['couloir2']."  </h1>";

                            }

                            }
                        */
                        


                        echo "<h1> Vous pouvez allez dans ces direction : </h1>\n";




                        // Recuperer les direction possible si couloir2 = id et permettre le deplacement
                        $position1='SELECT couloir1, couloir2, position1, position2, type FROM passage WHERE passage.couloir2=:empl'; // requete pour voir la position liés l'emplacement actuelle
                        $requetePos1 = $sqlite -> prepare($position1);	
	                    $requetePos1 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                    $resultPos1 = $requetePos1 -> execute();


                        while($requetePos1 = $resultPos1 -> fetchArray(SQLITE3_ASSOC)) {
              
                            echo "<h1> -  <a href ='index.php?couloir=".$requetePos1['couloir1']."' </a>".$requetePos1['position1']."  type (".$requetePos1['type'].") </h1>";
                            
                        }     
          

                        // Recuperer les direction possible si couloir1 = id et permettre le deplacement
                        $position2='SELECT couloir1, couloir2, position1, position2, type FROM passage WHERE passage.couloir1=:empl'; // requete pour voir la position liés l'emplacement actuelle
                        $requetePos2 = $sqlite -> prepare($position2);	
	                    $requetePos2 -> bindValue(':empl', $empl, SQLITE3_TEXT);
	                    $resultPos2 = $requetePos2 -> execute();

                        while($requetePos2 = $resultPos2 -> fetchArray(SQLITE3_ASSOC)) {
                            echo "<h1> - <a href ='index.php?couloir=".$requetePos2['couloir2']."'</a>".$requetePos2['position2']." type (".$requetePos2['type'].")  </h1>";
                        }





                    }
                }
            }

         // Fermer Base de Données
         $sqlite -> close();
         
        ?>



        </body>

    </html>

