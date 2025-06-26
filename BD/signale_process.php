  <?php
    require_once '../includes/init.php';
    require 'connect.php';

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Récupération des champs
        $description        = trim($_POST['description']);
        $date_signalement   = $_POST['date_signalement'];
        $type_signalement   = $_POST['type_signalement'];
        $lieu               = trim($_POST['lieu']);

        // Vérifie que l'utilisateur est connecté
        if (!isset($_SESSION['utilisateur_id'])) {
            $_SESSION['message'] = "Vous devez être connecté pour signaler un problème.";
            header("Location: ../login.php");
            exit();
        }
        $utilisateur_id = $_SESSION['utilisateur_id'];

        // --- Gestion de la photo ---
        $photo_path = null;
        if (!empty($_FILES['photo']['name'])) {
            $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];
            $max_size    = 5 * 1024 * 1024; // 5 Mo

            // Dossier d'upload (chemin système)
            $upload_dir = __DIR__ . '/../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Infos fichier
            $file_tmp  = $_FILES['photo']['tmp_name'];
            $file_name = basename($_FILES['photo']['name']);
            $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

            // Vérifications
            if (!in_array($file_ext, $allowed_ext)) {
                $_SESSION['message'] = "Extension de fichier non autorisée.";
                header("Location: ../signaler.php");
                exit();
            }
            if ($_FILES['photo']['size'] > $max_size) {
                $_SESSION['message'] = "Fichier trop volumineux (max 5 Mo).";
                header("Location: ../signaler.php");
                exit();
            }

            // Génère un nom unique et déplace
            $new_file_name = time() . '_' . uniqid() . '.' . $file_ext;
            $target_file   = $upload_dir . $new_file_name;

            if (move_uploaded_file($file_tmp, $target_file)) {
                // Stocke ici le chemin **relatif web** pour l'insertion DB
                // Sans "../" si tu affiches depuis le même niveau que index PHP
                $photo_path = 'uploads/' . $new_file_name;
            } else {
                $_SESSION['message'] = "Erreur lors de l'envoi du fichier.";
                header("Location: ../signaler.php");
                exit();
            }
        }

        // --- Vérification du type de signalement ---
        $stmt = $pdo->prepare("SELECT type_signalement_id FROM type_signalement WHERE nom = ?");
        $stmt->execute([$type_signalement]);
        $row = $stmt->fetch();
        if (!$row) {
            $_SESSION['message'] = "Type de signalement non valide.";
            header("Location: ../signaler.php");
            exit();
        }
        $type_signalement_id = $row['type_signalement_id'];

        // --- Insertion en base ---
        $stmt = $pdo->prepare(
            "INSERT INTO signalement 
           (description, date_signalement, photo, lieu, utilisateur_id, type_signalement_id) 
           VALUES (?, ?, ?, ?, ?, ?)"
        );
        $success = $stmt->execute([
            $description,
            $date_signalement,
            $photo_path,
            $lieu,
            $utilisateur_id,
            $type_signalement_id
        ]);

        $_SESSION['message'] = $success
            ? "✅ Signalement ajouté avec succès !"
            : "❌ Erreur lors de l'ajout du signalement.";

        header("Location: ../signaler.php");
        exit();
    }

?>    