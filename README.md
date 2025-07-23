# AbdallahTedene

## Description
AbdallahTedene est un package PHP disponible sur Packagist, développé pour fournir des fonctionnalités utiles pour vos projets PHP.

## Core Package
Le Core Package est le cœur de notre bibliothèque, contenant les fonctionnalités essentielles.

### Structure du Core
```

├── Core/
    ├── abstract
    │   ├── AbstractController.php
    │   ├── AbstractEntity.php
    │   └── AbstractRepository.php
    ├── App.php
    ├── Database.php
    ├── Error_404.php
    ├── ErrorMessage.php
    ├── FileUpload.php
    ├── middlewares
    │   ├── Auth.php
    │   └── CryptPassword.php
    ├── Router.php
    ├── Session.php
    ├── Singleton.php
    └── Validator.php
```

### Fonctionnalités principales
- **Configuration**: Gestion centralisée des paramètres
- **Gestion des erreurs**: Système robuste de gestion des exceptions
- **Services de base**: Fonctionnalités essentielles réutilisables

### Utilisation du Core
```php
use Abdoulaye\AbdallahTedene\Core\Config;
use Abdoulaye\AbdallahTedene\Core\Services;

// Exemple d'utilisation
$config = new Config();
$service = new Services($config);
```

### Configuration requise
- PHP 5.6 ou supérieur
- Extensions PHP requises:
  - json
  - mbstring
  - xml

### Bonnes pratiques
1. Toujours utiliser les interfaces fournies
2. Suivre le pattern singleton pour la config
3. Utiliser la gestion d'erreurs intégrée

### Exemple complet
```php
try {
    $core = new Core();
    $core->initialize([
        'debug' => true,
        'environment' => 'development'
    ]);
    
    // Votre code ici
    
} catch (CoreException $e) {
    // Gestion des erreurs
}
```

## Support et Maintenance
- Documentation API complète
- Tests unitaires
- Mises à jour régulières

## Installation
Vous pouvez installer ce package via Composer en utilisant la commande suivante :

```bash
composer require abdoulaye-tedene/abdallah-tedene
```
