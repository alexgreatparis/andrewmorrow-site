<?php
// Activer le logging des erreurs
error_log("Process order script accessed");
ini_set('display_errors', 1);
error_reporting(E_ALL);

$orderSuccess = false;
$orderError = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_form'])) {
    error_log("POST request received with order form");
    
    // Récupération et nettoyage des données
    $nom = htmlspecialchars(trim($_POST['nom'] ?? ''));
    $prenom = htmlspecialchars(trim($_POST['prenom'] ?? ''));
    $adresse = htmlspecialchars(trim($_POST['adresse'] ?? ''));
    $code_postal = htmlspecialchars(trim($_POST['code_postal'] ?? ''));
    $ville = htmlspecialchars(trim($_POST['ville'] ?? ''));
    $email = htmlspecialchars(trim($_POST['email'] ?? ''));
    $telephone = htmlspecialchars(trim($_POST['telephone'] ?? ''));
    $quantite = intval($_POST['quantite'] ?? 1);
    
    error_log("Data received: $nom, $prenom, $email");
    
    if (!empty($nom) && !empty($prenom) && !empty($adresse) && !empty($code_postal) && 
        !empty($ville) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        // Email à l'éditeur
        $to = 'nat.bissey@andrewmorrowdetective.com';
        $subject = 'Nouvelle commande BD "Vol de la Palme d\'Or"';
        
        $message = "Bonjour,\n\n";
        $message .= "Nouvelle commande reçue :\n\n";
        $message .= "PRODUIT : Edition Papier - BD + Coloriage Flipbook (12€)\n";
        $message .= "QUANTITÉ : $quantite\n\n";
        $message .= "CLIENT :\nNom : $nom $prenom\nEmail : $email\n";
        if (!empty($telephone)) {
            $message .= "Téléphone : $telephone\n";
        }
        $message .= "\nADRESSE DE LIVRAISON :\n$adresse\n$code_postal $ville\n\n";
        $message .= "Merci de confirmer la commande au client.\n\nAndrew Morrow Detective - Système de commande automatique";
        
        $headers = "From: noreply@andrewmorrowdetective.com\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        error_log("Attempting to send email to editor");
        $mail_sent = mail($to, $subject, $message, $headers);
        
        if ($mail_sent) {
            error_log("Editor email sent, attempting client email");
            $subject_client = 'Confirmation de votre commande Andrew Morrow Detective';
            $message_client = "Bonjour $prenom,\n\nMerci pour votre commande !\n\n";
            $message_client .= "RÉSUMÉ :\n• Edition Papier - BD + Coloriage Flipbook (12€)\n";
            $message_client .= "• Quantité : $quantite\n• Frais de port : 2,50€\n\n";
            $message_client .= "Livraison : 2 à 5 jours ouvrés\n\n";
            $message_client .= "Contact : nat.bissey@andrewmorrowdetective.com\nInstagram : @andrewmorrowbd\n\n";
            $message_client .= "Merci de votre confiance !";
            
            $headers_client = "From: nat.bissey@andrewmorrowdetective.com\r\n";
            $headers_client .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            $client_mail_sent = mail($email, $subject_client, $message_client, $headers_client);
            error_log("Client email send attempt: " . ($client_mail_sent ? "success" : "failure"));
        }
        
        if ($mail_sent) {
            error_log("Redirecting to success");
            header('Location: index.html?order=success');
            exit;
        } else {
            error_log("Redirecting to error");
            header('Location: index.html?order=error');
            exit;
        }
    } else {
        error_log("Validation failed, redirecting to error");
        header('Location: index.html?order=error');
        exit;
    }
}

error_log("Unexpected end of script");
header('Location: index.html?order=error');
exit;
?>
