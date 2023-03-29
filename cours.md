mise en place
création de la BDD : user, entités métier
peupler la BDD : ajouter des données de test (fixtures)
créer des pages de types CRUD
dev front : CSS, JS
gestion de la sécurité : 
    - création des formulaires d'inscription et de connexion
    - protection de certaines pages et/ou certaines actions
(optionnel) création automatique d'un back office
(optionnel) création automatique d'une API
livraison 

-------

route == URL + verbe HTTP + une fonction
controller == une ou plusieurs fonctions
vue == HTML + twig

design pattern MVC == Model Vue Controller

-------

commande pour vérifier que le contenu de la base de données correspond aux entités : ``` php bin/console doctrine:schema:validate ```
(on peut git les fichiers de migrations pour suivre l'évolution de la base de données).

- créer des entités (table dans la bbd) : ```php bin/console make:entity```

- pour générer le fichier de migration :```php bin/console doctrine:migration:diff```

- pour envoyer le fichier de migration dans la bdd : ```php bin/console doctrine:migration:migrate```

---------

1. git clone
2. composer inst
3. config
4. migr.
5. fixtures
6. web

-----

Fixtures
    pour injecter les données, il faut le faire dans un ordre particulier : il faut commencer par les class qui sont le moins connectées.

    permet de charger les fixtures : ```php bin/console doctrine:fixtures:load```


Pour mettre en ligne : 
tout se passe sur le serveur
    1. dossier
    2. BDD
    3. Apache/NGNIX
    4. (PHP FPM)
    5. git clone
    6. .env.local de la prod
    7. appliquer les fichiers de migration 
    8. (charger des fixtures de production)

    pour les mises à jour, faire un git pull, appliquer les fichiers de migration et c'est tout. 
    
