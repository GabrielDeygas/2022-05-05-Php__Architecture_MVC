<h2><?php echo $title ?></h2>

<?php if( empty( $room_by_id ) ): ?>
    <div>Cette chambre n'existe plus</div>
<?php else: ?>

    <ul>
            <li style="list-style-type: none">
                <a href="<?php echo '/ajout_favoris/' . $room_by_id->id ?>">
                    <?php if ($is_faved): ?>Supprimer des favoris
                    <?php else: ?>Ajouter aux favoris
                    <?php endif; ?></a> <br><br>
                <img style="width: 250px; height: 120px;"
                     src="<?php echo '/assets/img/' . $room_by_id->path_pics ?>" alt="photo de la maison"><br>
                <?php echo $room_by_id->description ?><br>
                <?php echo $room_by_id->surface . ' m2' ?><br>
                La chambre est disponible du <?php echo $room_by_id->dispo_from ?><br> au <?php echo $room_by_id->dispo_to ?><br>
                <?php echo $room_by_id->description ?><br>
                <?php echo $room_by_id->description ?><br>

                <?php if(!empty($_SESSION['USER']) && $_SESSION['USER']->user_type === 1): ?>
                    <form action="<?php echo '/chambre/' . $room_by_id->id ?>" method="post">
                    <input type="hidden" name="room_id" value="<?php echo $room_by_id->id ?>">
                    <label for="date_start">Date d'entrée :</label>
                    <input type="date" id="date_start" name="date_start" value=""
                           min="<?php echo $room_by_id->dispo_from ?>" max="<?php echo $room_by_id->dispo_to ?>"><br>
                    <label for="date_end">Date de sortie:</label>
                    <input type="date" id="date_end" name="date_end" value=""
                           min="<?php echo $room_by_id->dispo_from ?>" max="<?php echo $room_by_id->dispo_to ?>"><br>
                    <input type="submit">
                    </form>
                <?php endif; ?>
            </li>
    </ul>
    <?php if(!empty($_SESSION['USER']) && $_SESSION['USER']->user_type === 1): ?>
    <?php if(!empty($_SESSION['FORM_RESULT'])) echo $_SESSION['FORM_RESULT']; $_SESSION['FORM_RESULT'] = '' ?>
    <?php endif ?>
<?php endif ?>