<!doctype html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title><?php echo $title_tag ?></title>
</head>
<body>
	<header>
		<div>Mon Site</div> <br>
		<nav>



            <?php if(!empty($_SESSION['USER'])): ?>
                <a href="/chambres">Les chambres</a>
                <a href="/reservations">Vos réservations</a>
            <?php endif; ?>
                <?php if(!empty($_SESSION['USER']) && $_SESSION['USER']->user_type === 2): ?>
                    <a href="/creer_chambre">Créer une chambre</a>
                <?php endif; ?>
            <?php if(!empty($_SESSION['USER'])): ?>
            <a href="/disconnect">Se déconnecter</a>
            <?php endif; ?>
		</nav>
	</header>

	<main>

