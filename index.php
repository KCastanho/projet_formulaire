<?php
include 'tableaux.php';

$warnings = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
      extract($_POST); 
    // Validation pour chaque champ
    if (empty($_POST["prenom"])) {
        $warnings["prenom"] = "Veuillez entrer votre prénom.";
    }
    if (empty($_POST["nom"])) {
        $warnings["nom"] = "Veuillez entrer votre nom.";
    }
    if (empty($_POST["email"])) {
        $warnings["email"] = "Veuillez entrer votre email.";
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $warnings["email"] = "Veuillez entrer un email valide.";
    }
    if (empty($_POST["telephone"])) {
        $warnings["telephone"] = "Veuillez entrer votre numéro de téléphone.";
    }
    if (empty($_POST["service"])) {
        $warnings["service"] = "Veuillez sélectionner un service.";
    }
    if (empty($_FILES["cv"]["name"])) {
        $warnings["cv"] = "Veuillez télécharger votre CV en format PDF.";
    } elseif (pathinfo($_FILES["cv"]["name"], PATHINFO_EXTENSION) !== "pdf") {
        $warnings["cv"] = "Le CV doit être au format PDF.";
    }
    if (empty($_POST["message"])) {
        $warnings["message"] = "Veuillez entrer un message de motivation.";
    }
    if (empty($_POST["disponibilite"])) {
        $warnings["disponibilite"] = "Veuillez indiquer votre disponibilité.";
    }

      

      // Définit la date d'aujourd'hui et la date limite
      $today = date("Y-m-d");
      $max_date = date("Y-m-d", strtotime("+3 weeks"));
      
}
// Fonction pour vérifier la longueur du prénom

      function verif_disponibilite($disponibilite) {
      global $today, $max_date; // Utilisation des variables globales

      if ($disponibilite < $today) {
            echo '<div class="alert alert-danger" role="alert">La date de disponibilité doit être supérieure ou égale à aujourd\'hui.</div>';
      } elseif ($disponibilite > $max_date) {
            echo '<div class="alert alert-danger" role="alert">La date de disponibilité ne peut pas dépasser 3 semaines à partir d\'aujourd\'hui.</div>';
      }
      }


      function verif_prenom($prenom) {
            if (mb_strlen($prenom) < 1 || mb_strlen($prenom) > 50) {
            return '<div class="alert alert-danger" role="alert">Le prénom doit contenir entre 1 et 50 caractères !</div>';
            }
            return '';
      }
      
      
      function verif_nom($nom) {
            if (mb_strlen($nom) < 1 || mb_strlen($nom) > 50) {
                  return '<div class="alert alert-danger" role="alert">Le nom doit contenir entre 1 et 50 caractères !</div>';
            }
            return '';
      }
  
      // Vérification de l'email
      function verif_email($email){
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                  return '<div class="alert alert-danger" role="alert">L\'adresse email n\'est pas valide !</div>';
              } 

            return'';
      }

      //Vérification du téléphone
      function verif_telephone($telephone) {
            // Supprime les espaces avant et après le numéro
            $telephone = trim($telephone);
        
            // Vérifie que le numéro de téléphone est composé de 10 chiffres et commence par 0
            if (!preg_match('/^0[1-9][0-9]{8}$/', $telephone)) {
                echo '<div class="alert alert-danger" role="alert">Le numéro de téléphone doit contenir 10 chiffres et commencer par 0.</div>';
            }
        }


?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Candidature</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container my-5">
        <h2 class="mb-4">Formulaire de Candidature</h2>

        <form action="" method="post" enctype="multipart/form-data">
            <!-- Prénom -->
            <div class="mb-3">
                <label for="prenom" class="form-label">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" value="">
                <?= verif_prenom($_POST['prenom'] ?? '') ?>
                <?php if (isset($warnings['prenom'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <?= htmlspecialchars($warnings['prenom']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Nom -->
            <div class="mb-3">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom">
                <?= verif_nom($_POST['nom'] ?? '') ?>
                <?php if (isset($warnings['nom'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <?= htmlspecialchars($warnings['nom']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email">
                <?= verif_email($_POST['email'] ?? '') ?>
                <?php if (isset($warnings['email'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <?= htmlspecialchars($warnings['email']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Téléphone -->
            <div class="mb-3">
                <label for="telephone" class="form-label">Téléphone</label>
                <input type="tel" class="form-control" id="telephone" name="telephone">
                <?= verif_telephone($_POST['telephone'] ?? '') ?>
                <?php if (isset($warnings['telephone'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <?= htmlspecialchars($warnings['telephone']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Service -->
            <div class="mb-3">
                <label for="service" class="form-label">Service de transmission du CV</label>
                <select class="form-select" id="service" name="service">
                    <option value="">-- Sélectionnez un service --</option>
                    <option value="communication">Service communication</option>
                    <option value="rh">Service ressources humaines</option>
                    <option value="logistique">Service logistique</option>
                    <option value="technique">Service technique</option>
                </select>
                <?php if (isset($warnings['service'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <?= htmlspecialchars($warnings['service']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- CV Upload -->
            <div class="mb-3">
                <label for="cv" class="form-label">CV (format PDF)</label>
                <input type="file" class="form-control" id="cv" name="cv" accept=".pdf">
                <?php if (isset($warnings['cv'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <?= htmlspecialchars($warnings['cv']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Message de motivation -->
            <div class="mb-3">
                <label for="message" class="form-label">Message de motivation</label>
                <textarea class="form-control" id="message" name="message"></textarea>
                <?php if (isset($warnings['message'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <?= htmlspecialchars($warnings['message']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- LinkedIn -->
            <div class="mb-3">
                <label for="linkedin" class="form-label">Lien LinkedIn (optionnel)</label>
                <input type="url" class="form-control" id="linkedin" name="linkedin" placeholder="https://linkedin.com/in/votreprofil">
            </div>

            <!-- Portfolio -->
            <div class="mb-3">
                <label for="portfolio" class="form-label">Lien vers votre portfolio (optionnel)</label>
                <input type="url" class="form-control" id="portfolio" name="portfolio" placeholder="https://votreportfolio.com">
            </div>

            <!-- Disponibilité -->
            <div class="mb-3">
                <label for="disponibilite" class="form-label">Disponibilité</label>
                <input type="date" class="form-control" id="disponibilite" name="disponibilite">
                <?= verif_disponibilite($_POST['disponibilite'] ?? '') ?>
                <?php if (isset($warnings['disponibilite'])): ?>
                    <div class="alert alert-warning" role="alert">
                        <?= htmlspecialchars($warnings['disponibilite']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Bouton de soumission -->
            <button type="submit" class="btn btn-primary">Envoyer la Candidature></button>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>