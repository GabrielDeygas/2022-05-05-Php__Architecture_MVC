<h3> BIENVENUE <?php echo $user ?> </h3>

<form action="/creer_chambre" method="post" enctype="multipart/form-data">
    <label for="path_pics">Image de votre bien</label>
    <input type="file"
           id="path_pics" name="path_pics" value=""
           accept="image/png, image/jpeg">
    <fieldset>
        <legend>Veuillez sélectionner le type de chambre :</legend>
        <div>
            <input type="radio" id="type1" name="room_type" value="1">
            <label for="type1">Logement privé</label>
        </div>
        <div>
            <input type="radio" id="type2" name="room_type" value="2">
            <label for="type2">Chambre privée</label>
        </div>
        <div>
            <input type="radio" id="type3" name="room_type" value="3">
            <label for="type3">Chambre partagée</label>
        </div>
    </fieldset>
    <br>
    <label for="surface">Taille en mètres carrés</label>
    <input type="text" name="surface" value=""><br><br>
    <label for="description">Nom de la chambre</label>
    <input type="text" name="description" value=""><br><br>
    <label for="nb_sleep">Nombre de couchages (max 20):</label>
    <input type="number" id="nb_sleep" name="nb_sleep" value=""
           min="1" max="20"><br><br>
    <label for="dispo_from">Date de début de dispo :</label>
    <input type="date" id="dispo_from" name="dispo_from" value=""><br>
    <label for="dispo_to">Date de fin de dispo :</label>
    <input type="date" id="dispo_to" name="dispo_to" value=""><br>
    <label for="dispo_to">Prix à la nuit</label>
    <input type="text" name="price" value=""><br><br>
    <label for="country">Pays</label>
    <input type="text" name="country" value=""><br><br>
    <label for="city">Ville</label>
    <input type="text" name="city" value=""><br><br>
    <label for="address">Addresse Postale</label>
    <input type="text" name="address" value=""><br><br>
    <fieldset>
        <legend>Veuillez sélectionner les équipements :</legend>
        <div>
            <input type="checkbox" id="equipment1" name="equipments[]" value="1">
            <label for="equipment">Cheminée</label>
        </div>
        <div>
            <input type="checkbox" id="equipment2" name="equipments[]" value="2">
            <label for="equipment2">Piscine</label>
        </div>
        <div>
            <input type="checkbox" id="equipment3" name="equipments[]" value="3">
            <label for="equipment3">Hamac</label>
        </div>
        <div>
            <input type="checkbox" id="equipment4" name="equipments[]" value="4">
            <label for="equipment4">Baignoire</label>
        </div>
        <div>
            <input type="checkbox" id="equipment5" name="equipments[]" value="5">
            <label for="equipment5">Terrain de tennis</label>
        </div>
    </fieldset>
    <br>

    <input type="submit">
</form>
<br><br>

<?php if(!empty($_SESSION['FORM_RESULT'])) echo $_SESSION['FORM_RESULT']; $_SESSION['FORM_RESULT'] = '' ?>

