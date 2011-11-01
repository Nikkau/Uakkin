Uakkin
======

`Uakkin` est un microframework PHP utilisant quelques nouvelles fonctionnalités de `PHP5.3`.

Inspiré notamment par Sinatra, le principe est simplement d'associer une URL à une function pour générer la page.

    require_once('lib/uakkin.php');

    $f = new Uakkin();
    
    $f['/^\/$/'] = function () {
      echo "Hello world!";
    };

Je voulais une syntaxe très simple, donc les "routes" se configurent sous forme de tableau, une regexp représentant l'URL comme clef, et la fonction en valeur.

Le "dispatch" est automatique, dès qu'on arrête de mettre des routes, le résultat est généré immédiatement.

Il faut bien sûr rediriger toutes les requêtes sur notre script.

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^(.*)$ index.php [L]

Par défaut, une "route" non-prévue renverra une simple 404, on peut modifier ce comportement en configurant une fonction "custom" de fallback.

    require_once('lib/uakkin.php');

    $f = new Uakkin();
    
    $f['/^\/$/'] = function () {
      echo "Hello world!";
    };
    
    $f->fallback = function () {
      echo " No way!";
    };

Il est évidement très facile d'utiliser des variables dans notre route avec la gestion de base des regexp.

    require_once('lib/uakkin.php');

    $f = new Uakkin();
    
    $f['/^\/(?<key>[a-z0-9]+)$/'] = function ($args) {
    	echo (int) base_convert($args['key'], 36, 10);
    };

La moitié du code sert à rendre l'objet élégant à mes yeux (aucun objectif de prod' ou de performance donc), ce qui signifie que ça se "configure" comme un tableau mais qu'on peut également dispatch directement un tableau.

    require_once('lib/uakkin.php');

    $f = new Array('/^\/$/' => function () { echo "Hello world!"; });
    $fb = function () { echo " No way!"; };
    
    Uakkin::dispatch($f, $fb);

Y a sûrement pleins d'autres trucs rigolos à faire sur cette voie, mais j'avais juste fait ça en 1 heure y a quelques temps pour monter mon URL Shortener, c'était un mini-projet sympa pour tester la 5.3 (<http://uakk.in>).

Enjoy !
-------