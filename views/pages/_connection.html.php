<br><br>
<form action="/registered_l" method="post">
    <label for="mail">Mail</label>
    <input type="text" name="mail" value="">
    <label for="pseudo">Votre mot de passe</label>
    <input type="password" name="password" value="">Password
    <input type="submit">
</form>
<br>

<?php if(!empty($_SESSION['FORM_RESULT'])) echo $_SESSION['FORM_RESULT']; $_SESSION['FORM_RESULT'] = '' ?>

Je veux <a href="/">m'inscrire</a><br><br>
