<?php
// VÃ©rification que le formulaire a Ã©tÃ© soumis en POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // RÃ©cupÃ©ration et nettoyage des donnÃ©es
    $prenom = trim($_POST['prenom'] ?? '');
    $nom = trim($_POST['nom'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    $code_postal = trim($_POST['code_postal'] ?? '');
    $ville = trim($_POST['ville'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $quantite = intval($_POST['quantite'] ?? 1);
    
    // Validation des champs obligatoires
    $erreurs = [];
    if (empty($nom)) $erreurs[] = "Le nom est obligatoire";
    if (empty($prenom)) $erreurs[] = "Le prénom est obligatoire";
    if (empty($adresse)) $erreurs[] = "L'adresse est obligatoire";
    if (empty($code_postal)) $erreurs[] = "Le code postal est obligatoire";
    if (empty($ville)) $erreurs[] = "La ville est obligatoire";
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = "Une adresse email valide est obligatoire";
    }
    
    // Si pas d'erreurs, traitement de la commande
    if (empty($erreurs)) {
        
        // PrÃ©paration de l'email
        $destinataire = "nat.bissey@andrewmorrowdetective.com";
        $sujet = "Nouvelle commande BD \"Vol de la Palme d'Or\"";
        
        // Corps de l'email
        $message = "Bonjour,\n\n";
        $message .= "Nouvelle commande reçue via le site andrewmorrowdetective.com\n\n";
        $message .= "PRODUIT : Edition Papier - BD + Coloriage Flipbook (12€)\n";
        $message .= "QUANTITE : " . $quantite . "\n\n";
        $message .= "CLIENT :\n";
        $message .= "Nom : " . $prenom . " " . $nom . "\n";
        $message .= "Email : " . $email . "\n";
        if (!empty($telephone)) {
            $message .= "Téléphone : " . $telephone . "\n";
        }
        $message .= "\nADRESSE DE LIVRAISON :\n";
        $message .= $adresse . "\n";
        $message .= $code_postal . " " . $ville . "\n\n";
        $message .= "Merci de confirmer la commande au client.\n\n";
        $message .= "---\n";
        $message .= "Email automatique - Site Andrew Morrow Detective";
        
        // Headers de l'email
        $headers = "From: nat.bissey@andrewmorrowdetective.com\r\n";
        $headers .= "Reply-To: " . $email . "\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // Envoi de l'email
        if (mail($destinataire, $sujet, $message, $headers)) {
            // Succes - redirection avec message
            $success_message = "Votre commande a été transmise avec succès ! Vous recevrez un suivi par email.";

            header("Location: " . $_SERVER['HTTP_REFERER'] . "?success=" . urlencode($success_message));
            exit();
        } else {
            // Erreur d'envoi
            $error_message = "Erreur lors de l'envoi de votre commande. Veuillez réessayer ou nous envoyer un email.";
            header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=" . urlencode($error_message));
            exit();
        }
        
    } else {
        // Erreurs de validation
        $error_message = "Erreurs dans le formulaire : " . implode(", ", $erreurs);
        header("Location: " . $_SERVER['HTTP_REFERER'] . "?error=" . urlencode($error_message));
        exit();
    }
    
} else {
    // AccÃ¨s direct au script PHP non autorisÃ©
    header("Location: /");
    exit();
}
?>