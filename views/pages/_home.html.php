<br><br>
<?php if( !empty($error) ): ?>
<div style="color:red"><?php echo $error ?></div>
<?php endif?>
<form action="/" method="post">
    <label for="nickname">Username</label>
    <input type="text" name="nickname" value="">
    <label for="pseudo">Votre mot de passe</label>
    <input type="password" name="password" value="">
    <label for="mail">Votre mail</label>
    <input type="email" name="mail" value="">
    <label for="type_account">Type utilisateur</label>
    <input type="checkbox" name="user_type" value="2">Annonceur <br> <br>
    <input type="submit"><br><br><br>
</form>

Vous êtes déjà inscrit ? <a href="/connection">Connectez-vous</a> <br><br>