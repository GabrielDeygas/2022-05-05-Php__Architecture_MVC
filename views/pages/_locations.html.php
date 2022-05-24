<h1><?php echo $h1_tag ?></h1>

<?php if( empty( $rooms_all ) ): ?>
    <div>Aucune chambre disponible en ce moment</div>
<?php else: ?>
    <ul>
        <?php foreach( $rooms_all as $room ): ?>
            <br><br>
            <li style="list-style-type: none; max-width: 250px;"><a href="/chambre/<?php echo $room->id?>">
                <img style="width: 250px; height: 120px;"
                src="<?php echo './assets/img/' . $room->path_pics ?>" alt="photo de la maison"><br>
                La magnifique chambre d'hôtes
                <?php echo $room->description ?> vous propose de vous héberger pour seulement
                ( <?php echo $room->price ?> € )<br><br>
                <?php echo $room->surface ?> <?php echo $room->nb_sleep ?><br>
                <?php echo $room->addresses->address . ' ' . $room->addresses->city . ' ' . $room->addresses->country ?>
                <?php foreach($room->equipments as $equipment): ?>
                <?php
                    echo $equipment->equipment; ?>
                <?php endforeach; ?>
                </a>
            </li>
        <?php endforeach ?>
    </ul>
<?php endif ?>