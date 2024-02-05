
<!-- Inclusion du header et du fichier PDO, et récupération de données dans la BDD "favoris" -->
<?php

    include("header.php");

    include("pdo.php");


    // Récupération des catégories :
    $result_cat = $pdo->query("SELECT * FROM categorie");
    $categories = $result_cat->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des domaines :
    $result_dom = $pdo->query("SELECT * FROM domaine");
    $domaines = $result_dom->fetchAll(PDO::FETCH_ASSOC);


    // Alimentation de la variable contenant la requête du filtre :
    $requestSql = ("SELECT * FROM favori");

            
            if(!empty($_GET["categories"]) && empty($_GET["domaines"])){
                $requestSql .= " INNER JOIN cat_fav ON cat_fav.id_fav = favori.id_fav WHERE cat_fav.id_cat = ".$_GET["categories"];
            }elseif(empty($_GET["categories"]) && !empty($_GET["domaines"])){
                $requestSql .= " WHERE favori.id_dom = ".$_GET["domaines"];
            }elseif(isset($_GET["categories"]) && isset($_GET["domaines"])){
                $requestSql .= " INNER JOIN cat_fav ON cat_fav.id_fav = favori.id_fav WHERE cat_fav.id_cat = ".$_GET["categories"]." AND favori.id_dom = ".$_GET["domaines"];
            }


            $resultat = $pdo->query($requestSql);     
            $favoris = $resultat->fetchAll(PDO::FETCH_ASSOC);

?>



<!-- Sélecteur de catégorie et de domaine et champ de recherche -->
    <section class="flex justify-center">

        <!-- Zone de recherche et filtre -->
        <fieldset class="flex justify-center flex-col p-10 items-center border-solid border-black border-2 bg-stone-300 m-6 rounded-2xl">
            <legend class="text-2xl text-white font-bold bg-stone-800 p-3 rounded-lg border-solid border-black border-2">Recherche</legend>
            <div class="flex items-center">
                <form action="" method="GET">

                    <!-- Choix des catégories -->
                    <label class="text-2xl pr-5 font-bold">Catégories</label>
                    <select name="categories" class="text-xl border-solid border-2 border-black rounded-xl p-2 cursor-pointer  hover:bg-slate-100 hover:shadow-xl">
                        <option value="">-- Choix de la catégorie --</option>
                        <?php 
                            foreach($categories as $categorie){
                            ?>
                            <option value="<?php echo $categorie['id_cat']?>"><?php echo $categorie['nom_cat']?></option>
                            <?php
                            }
                        ?>
                    </select>

                    <!-- Choix du domaine -->
                    <label class="text-2xl pr-5 font-bold ml-20">Domaines</label>
                    <select name="domaines" class="text-xl border-solid border-2 border-black rounded-xl p-2 cursor-pointer  hover:bg-slate-100 hover:shadow-xl">
                        <option value="">-- Choix du domaine --</option>
                        <?php 
                            foreach($domaines as $domaine){
                            ?>
                            <option value="<?php echo $domaine['id_dom']?>"><?php echo $domaine['nom']?></option>
                            <?php
                            }
                        ?>
                    </select>

                    <!-- Bouton filtre -->
                    <input type="submit" name="filtre" value="Filtrer" class="text-xl font-semibold bg-blue-400 p-3 rounded-xl border-solid border-black border-2 ml-20 cursor-pointer hover:bg-blue-500">
                </form>
            </div>

                <!-- Champ de recherche -->
            <form action="search.php" method="GET" class="flex pt-10 pb-2 text-xl font-semibold items-center">
                    <h3 class="mr-8">Recherche par mot-clé</h3>
                    <input type="search" name="terme" class="rounded-lg p-2" placeholder="Tape UN mot puis entrée" size="30">
            </form>
        </fieldset>

        <!-- Ajout favori -->
        <fieldset class="flex items-center justify-center p-6 border-solid border-black border-2 bg-stone-300 m-6 rounded-2xl">
            <legend class="text-2xl text-white font-bold bg-stone-800 p-3 rounded-lg border-solid border-black border-2">Nouveau favori</legend>
            <a href="create.php" class="flex">
                <img src="images/file-circle-plus-regular.svg" alt="icone d'ajout de favori" width="80px" class="border-solid border-black border-2 rounded-2xl p-2 bg-lime-500 hover:bg-lime-600">
            </a>
        </fieldset>
        
    </section>



<!-- Affichage nombre de favoris -->
    <div class="w-1/4 flex justify-center">
        <?php
            echo "<p class='text-2xl font-bold flex items-center justify-center p-6 border-solid border-black border-2 bg-stone-300 rounded-2xl mt-20'>"."Tableau de&nbsp <i class='text-indigo-700'>".count($favoris)."</i>&nbspfavoris</p>";
        ?>
    </div>                       



<!-- Tableau -->
    <section>
        <table class="flex justify-center m-10">
            <tr class=" bg-stone-400">
                <th class="border-solid border-2 border-black p-2.5 text-2xl">Libellé</th>
                <th class="border-solid border-2 border-black p-2.5 text-2xl">Date d'ajout</th>
                <th class="border-solid border-2 border-black p-2.5 text-2xl">Lien</th>
                <th class="border-solid border-2 border-black p-2.5 text-2xl">Afficher</th>
                <th class="border-solid border-2 border-black p-2.5 text-2xl">Options</th>
            </tr>
            <?php 
                foreach($favoris as $favori){
                    ?>
                    <tr class="hover:bg-slate-300 odd:bg-slate-100 even:bg-slate-200">
                        <td class="border-solid border-2 border-black p-2.5 font-bold text-lg"><span class="ml-4"><?php echo $favori["libelle"];?></span></td>
                        <td class="border-solid border-2 border-black p-2.5 font-bold text-center text-lg"><?php echo $favori["date_creation"];?></td>
                        <td class="border-solid border-2 border-black p-2.5 font-bold text-lg"><span class="ml-4"><?php echo "<a href='".$favori['url']."' target='about_blank'class='text-teal-500 underline'>".$favori['url']."</a>";?></span></td>
                        <td class="border-solid border-2 border-black p-2.5 text-center">
                            <a href="seeFavori.php?id=<?php echo $favori["id_fav"]?>" target='_blank' class="flex justify-center"><img src="images/eye-regular.svg" alt="see_icon" width="35px" class="bg-violet-400 hover:bg-violet-500 rounded-lg p-1"/></a>
                        </td>
                        <td class="border-solid border-2 border-black p-2.5 text-center">
                            <div class="flex justify-center items-center">
                                <?php echo "<a href='update.php?id=".$favori["id_fav"]."'><img src='images/pen-to-square-regular.svg' alt='edit_icon' width='35px' class='bg-yellow-300 hover:bg-yellow-500 rounded-lg p-1'/></a>";
                                    ?>
                                <?php echo "<a href='delete.php?id_del=".$favori['id_fav']."' class='ml-2'><img src='images/trash-can-regular.svg' alt='delete_icon' width='32px' class='bg-red-500 hover:bg-red-700 rounded-lg p-1'/></a>";
                                    ?>
                            </div>
                        </td>
                    </tr>
                    <?php
                }
            ?>
        </table>
    </section>



<!-- Inclusion du footer -->
<?php
    include("footer.php");
?>