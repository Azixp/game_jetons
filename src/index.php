<?php
session_start();
if(!isset($_SESSION['jetons'], $_SESSION['scoreActuel'], $_SESSION['totalJetons'], 
$_SESSION['jetonActuel'], $_SESSION['jetonDefausse'])){
    $_SESSION['jetons'] = [];
    $_SESSION['scoreActuel'] = 0;
    $_SESSION['jetonActuel'] = 0;
    $_SESSION['jetonDefausse'] = 0;
    $_SESSION['totalJetons'] = 0;
}

$piocher = isset($_GET['action']) && $_GET['action'] === 'piocher' && $_SESSION['totalJetons'] < 100;
$reset = isset($_GET['action']) && $_GET['action'] === 'reset';
$defausser = isset($_GET['action']) && $_GET['action'] === 'defausser';

$valeurPossibles = [5, 10, 20, 50]; // valeurs possibles que l'utilisateur peut piocher

if($piocher){
    $indexJeton = array_rand($valeurPossibles); // On récupère aléatoirement l'index du tableau $valeurPossibles
    $_SESSION['jetonActuel'] = $valeurPossibles[$indexJeton]; // On utilise l'index récupéré aléatoirement pour afficher sa valeur et on l'affecte à $jeton
    array_push($_SESSION['jetons'], $_SESSION['jetonActuel']); // On empile/enfile la valeur aléatoire dans le tableau de session
    $_SESSION['scoreActuel']++;
}

if($defausser){
    $_SESSION['jetonDefausse'] = array_pop($_SESSION['jetons']);
    $_SESSION['scoreActuel']++;
}

if($_SESSION['totalJetons'] === 100 && !isset($_SESSION['meilleurScore'])){
    $_SESSION['meilleurScore'] = 9999;
}

if($reset){
    if($_SESSION['scoreActuel'] < $_SESSION['meilleurScore'] && $_SESSION['totalJetons'] === 100){
        $_SESSION['meilleurScore'] = $_SESSION['scoreActuel'];
    }
    $_SESSION['jetons'] = [];
    $_SESSION['scoreActuel'] = 0;
    $_SESSION['jetonActuel'] = 0;
    $_SESSION['jetonDefausse'] = 0;
}


$_SESSION['totalJetons'] = array_sum($_SESSION['jetons']);

//DEBUG
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
?>

<?php require "Elements/header.php"; ?>
<div class="d-flex justify-content-center mt-5 mb-3">
    <h1>Le 💯 gagnant</h1>
</div>

<div class="d-flex justify-content-center mb-3">
    <div class="d-flex justify-content-around col-5 col-md-3">
        <div class="try col-5">
            <i class="bi bi-hand-index"></i>
            <?= $_SESSION['scoreActuel'] ?> <!-- nombre de tour de la partie en cours -->
        </div>
        <div class="try col-5">
            <i class="bi bi-hand-thumbs-up"></i>
            <?= $_SESSION['meilleurScore'] ?? '-' ?> <!-- meilleur nombre de tour (le plus bas) du joueur -->
        </div>
    </div>
</div>

<div class="d-flex justify-content-center mb-1">
<?php if($piocher) : ?>
    <div class="alert alert-success">
        Vous avez pioché un jeton avec la valeur <?= $_SESSION['jetonActuel'] ?>.
    </div>
<?php elseif($defausser) : ?>
    <div class="alert alert-success">
        Vous vous êtes défaussé d'un jeton avec la valeur <?= $_SESSION['jetonDefausse'] ?>.
    </div>    
<?php endif; ?>
  
</div>
<div class="d-flex justify-content-center mb-1">
<?php if($_SESSION['totalJetons'] === 100) : ?>
    <div class="alert alert-success">
        Bravo ! Vous avez atteint le Le 💯 gagnant.
    </div> 
<?php endif; ?>
</div>
<div class="d-flex justify-content-center mb-2">
    <div class="score-container">
        <h1 class="display-4"><?= $_SESSION['totalJetons'] ?></h1> <!-- total des jetons possédés par le joueur -->
    </div>
</div>

<div class="d-flex justify-content-center mb-2">
    <div class="d-flex justify-content-around">
        <?php if(array_sum($_SESSION['jetons']) < 100) :?>
            <a class="btn btn-primary mr-1" href="/?action=piocher">
                <i class="bi bi-plus-square"></i> Piocher
            </a>
        <?php endif;?>
        <?php if(!empty($_SESSION['jetons']) && array_sum($_SESSION['jetons']) > 0) : ?>
            <a class="btn btn-danger" href="/?action=defausser">
                <i class="bi bi-dash-square"></i> Défausser
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="d-flex justify-content-around">
    <a class="btn btn-secondary" href="/?action=reset">Réinitialiser</a>
</div>
<?php require "Elements/footer.php"; ?>
