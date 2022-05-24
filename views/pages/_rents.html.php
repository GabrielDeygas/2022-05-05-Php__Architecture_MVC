<h1><?php echo $h1_tag ?></h1>

<?php if( empty( $rents ) ): ?>
    <div>Aucune chambre disponible en ce moment</div>
<?php else: ?>
    <ul>
        <?php $current_room = 0; $current_i = 0; ?>
        <?php foreach ($rents as $rent){
            if ( $current_room > 0 && $current_room != $rent->room_id ) echo '</li>';

            if ( $current_room == 0 || $current_room != $rent->room_id ){
                echo ' <br> <li style="list-style-type: none">
                <img style="width: 300px; height: 180px;"
                     src="./assets/img/' . $rent->room->path_pics . '" alt=""> <br>
                     La magnifique chambre d\'hôtes ' . $rent->room->description . ' est réservée du <br>';

            }
            $current_room = $rent->room_id;

            echo '- '. $rent->date_start .' à '. $rent->date_end .'<br>';
            $current_i ++;
            if ( $current_i >= count( $rents ) ) echo '</li>';
        } ?>

    </ul>
<?php endif ?>