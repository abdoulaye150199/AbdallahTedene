<?php
    namespace App\Core;

    enum ErrorMessage: string{
        
        case OBLIGATOIRE = "Le champ est requis.";
        case EMAIL_INVALIDE = "Le login est invalide.";
        case MOIN_DE_CARACTERE = "Le champ doit contenir plus de caractères.";


        case NUMERO_INVALIDE = "Le numéro de téléphone est invalide.";
        case CNI_INVALIDE = "Le numéro de CNI est invalide.";
        case PHOTO_VERSO = "Le photo format verso est obligatoire et ne doit pas depasser 2Mo";
        case PHOTO_RECTO = "Le photo format Recto est obligatoire et ne doit pas depasser 2Mo";
    }




