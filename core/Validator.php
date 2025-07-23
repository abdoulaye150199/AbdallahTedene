<?php
namespace App\Core;

class Validator
{
    private $erreurs = [];

    private static $regles = [];

    private static function initialiserRegles()
    {
        if (empty(self::$regles)) {
            self::$regles = [
                'obligatoire' => function($valeur, $champ, $message) {
                    return !empty(trim($valeur)) ? true : $message;
                },

                'email' => function($valeur, $champ, $message) {
                    return filter_var($valeur, FILTER_VALIDATE_EMAIL) ? true : $message;
                },

                'telephone_senegal' => function($valeur, $champ, $message) {
                    $pattern = '/^(\+221|7[056789])[0-9]{7}$/';
                    return preg_match($pattern, $valeur) ? true : $message;
                },

                'min_longueur' => function($valeur, $champ, $message, $parametres = []) {
                    $min = $parametres['longueur'] ?? 3;
                    return strlen(trim($valeur)) >= $min ? true : $message;
                },

                'max_longueur' => function($valeur, $champ, $message, $parametres = []) {
                    $max = $parametres['longueur'] ?? 255;
                    return strlen(trim($valeur)) <= $max ? true : $message;
                },

                'lettres_espaces' => function($valeur, $champ, $message) {
                    $pattern = '/^[A-Za-zÀ-ÖØ-öø-ÿ\s\'-]+$/';
                    return preg_match($pattern, $valeur) ? true : $message;
                },

                'alphanumerique' => function($valeur, $champ, $message) {
                    $pattern = '/^[A-Za-z0-9]+$/';
                    return preg_match($pattern, $valeur) ? true : $message;
                },

                'fichier_obligatoire' => function($valeur, $champ, $message) {
                    return isset($_FILES[$champ]) && $_FILES[$champ]['error'] === UPLOAD_ERR_OK ? true : $message;
                },

                'fichier_image' => function($valeur, $champ, $message) {
                    if (!isset($_FILES[$champ]) || $_FILES[$champ]['error'] !== UPLOAD_ERR_OK) {
                        return true;
                    }

                    $typesAutorises = ['image/jpeg', 'image/jpg', 'image/png'];
                    $type = $_FILES[$champ]['type'];

                    return in_array($type, $typesAutorises) ? true : $message;
                },

                'fichier_taille_max' => function($valeur, $champ, $message, $parametres = []) {
                    if (!isset($_FILES[$champ]) || $_FILES[$champ]['error'] !== UPLOAD_ERR_OK) {
                        return true;
                    }

                    $tailleMax = $parametres['taille'] ?? (5 * 1024 * 1024);
                    $taille = $_FILES[$champ]['size'];

                    return $taille <= $tailleMax ? true : $message;
                },

                'accepte' => function($valeur, $champ, $message) {
                    return in_array($valeur, ['1', 'on', 'yes', 'true', true, 1], true) ? true : $message;
                },

                'numerique' => function($valeur, $champ, $message) {
                    return is_numeric($valeur) ? true : $message;
                }
            ];
        }
    }

    public function __construct()
    {
        $this->erreurs = [];
    }

    public static function valider($donnees, $regles, $messages = [])
    {
        self::initialiserRegles();

        $validateur = new self();

        foreach ($regles as $champ => $reglesChamp) {
            $valeur = $donnees[$champ] ?? '';

            if (is_string($reglesChamp)) {
                $reglesChamp = explode('|', $reglesChamp);
            }

            foreach ($reglesChamp as $regle) {
                $validateur->appliquerRegle($champ, $valeur, $regle, $messages, $donnees);
            }
        }

        return $validateur->obtenirErreurs();
    }

    private function appliquerRegle($champ, $valeur, $regle, $messages, $donnees)
    {
        $parties = explode(':', $regle);
        $nomRegle = $parties[0];
        $parametres = [];

        if (isset($parties[1])) {
            switch ($nomRegle) {
                case 'min_longueur':
                case 'max_longueur':
                    $parametres['longueur'] = (int)$parties[1];
                    break;
                case 'fichier_taille_max':
                    $parametres['taille'] = (int)$parties[1];
                    break;
            }
        }

        $cleMessage = "$champ.$nomRegle";
        $messageParDefaut = $this->messageParDefaut($champ, $nomRegle, $parametres);
        $message = $messages[$cleMessage] ?? $messages[$champ] ?? $messageParDefaut;

        if (isset(self::$regles[$nomRegle])) {
            $resultat = self::$regles[$nomRegle]($valeur, $champ, $message, $parametres, $donnees);

            if ($resultat !== true) {
                $this->ajouterErreur($champ, $resultat);
            }
        }
    }

    private function messageParDefaut($champ, $regle, $parametres = [])
    {
        $nomAffiche = $this->nomChampLisible($champ);

        $messages = [
            'obligatoire' => "Le champ $nomAffiche est obligatoire.",
            'email' => "Le champ $nomAffiche doit être un email valide.",
            'telephone_senegal' => "Le champ $nomAffiche doit être un numéro sénégalais valide.",
            'min_longueur' => "Le champ $nomAffiche doit contenir au moins " . ($parametres['longueur'] ?? 3) . " caractères.",
            'max_longueur' => "Le champ $nomAffiche ne peut pas dépasser " . ($parametres['longueur'] ?? 255) . " caractères.",
            'lettres_espaces' => "Le champ $nomAffiche ne peut contenir que des lettres et espaces.",
            'alphanumerique' => "Le champ $nomAffiche ne peut contenir que lettres et chiffres.",
            'fichier_obligatoire' => "Le fichier $nomAffiche est obligatoire.",
            'fichier_image' => "Le fichier $nomAffiche doit être une image JPG, JPEG ou PNG.",
            'fichier_taille_max' => "Le fichier $nomAffiche ne peut pas dépasser " . $this->formaterTailleFichier($parametres['taille'] ?? (5 * 1024 * 1024)) . ".",
            'accepte' => "Vous devez accepter $nomAffiche.",
            'numerique' => "Le champ $nomAffiche doit être numérique."
        ];

        return $messages[$regle] ?? "Le champ $nomAffiche est invalide.";
    }

    private function nomChampLisible($champ)
    {
        $traductions = [
            'prenom' => 'Prénom',
            'nom' => 'Nom',
            'adresse' => 'Adresse',
            'telephone' => 'Téléphone',
            'loginTelephone' => 'Téléphone',
            'numero_piece_identite' => 'Numéro de pièce d\'identité',
            'photo_recto' => 'Photo recto',
            'photo_verso' => 'Photo verso',
            'terms' => 'les conditions d\'utilisation'
        ];

        return $traductions[$champ] ?? ucfirst(str_replace('_', ' ', $champ));
    }

    private function formaterTailleFichier($octets)
    {
        if ($octets >= 1048576) {
            return round($octets / 1048576, 1) . ' MB';
        } elseif ($octets >= 1024) {
            return round($octets / 1024, 1) . ' KB';
        }
        return $octets . ' octets';
    }

    public static function ajouterRegle($nom, $callback)
    {
        self::initialiserRegles();
        self::$regles[$nom] = $callback;
    }

    public function obtenirErreurs()
    {
        return $this->erreurs;
    }


    public function ajouterErreur($champ, $message)
    {
        $this->erreurs[$champ] = $message;
    }

    
    public function estValide()
    {
        return empty($this->erreurs);
    }

    // Méthodes anciennes conservées pour compatibilité
    public function estEmail($champ, $valeur, $message)
    {
        if (!filter_var($valeur, FILTER_VALIDATE_EMAIL)) {
            $this->ajouterErreur($champ, $message);
        }
    }

    public function estVide($champ, $valeur, $message)
    {
        if (empty($valeur)) {
            $this->ajouterErreur($champ, $message);
        }
    }
}
