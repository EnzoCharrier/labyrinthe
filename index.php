<!DOCTYPE html>		
<html lang= fr>
    <head>
	    <meta charset= "UTF-8">	
	    <title>Labyrinthe</title>
    </head>


    <body>

        <?php include("include/header.php");
	        include("include/nav.php");



            if (isset($_GET['menu']))
            {

                switch($_GET['menu'])
                {

                    case '1': 
            
                    include("pages/pageDepart.php");
                    break;
      
                default:
                include("pages/page0.php") ;
                }
            }

        ?>

    </body>


