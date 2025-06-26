CREATE DATABASE InfraWatch;
USE InfraWatch;

-- Table: utilisateur
CREATE TABLE utilisateur (
    utilisateur_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(200) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'utilisateur'
) ENGINE=InnoDB;

-- Table: type_signalement
CREATE TABLE type_signalement (
    type_signalement_id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL UNIQUE
) ENGINE=InnoDB;

-- Table: signalement
CREATE TABLE signalement (
    signalement_id INT AUTO_INCREMENT PRIMARY KEY,
    description TEXT NOT NULL,
    date_signalement DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    photo TEXT NULL,
    etat ENUM('en attente', 'en cours', 'terminé') NOT NULL DEFAULT 'en attente',
    lieu TEXT NOT NULL,
    utilisateur_id INT NOT NULL,
    type_signalement_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id) ON DELETE CASCADE,
    FOREIGN KEY (type_signalement_id) REFERENCES type_signalement(type_signalement_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: intervention
CREATE TABLE intervention (
    intervention_id INT AUTO_INCREMENT PRIMARY KEY,
    date_intervention DATE NOT NULL,
    statut_intervention ENUM('planifiée', 'en cours', 'terminée') NOT NULL DEFAULT 'planifiée',
    utilisateur_id INT NOT NULL,
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: notification
CREATE TABLE notification (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    contenu TEXT NOT NULL,
    date_notification DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    statut ENUM('non lu', 'lu') NOT NULL DEFAULT 'non lu'
) ENGINE=InnoDB;

-- Table: utilisateur_signalement (Relation entre utilisateur et signalement)
CREATE TABLE utilisateur_signalement (
    utilisateur_id INT NOT NULL,
    signalement_id INT NOT NULL,
    PRIMARY KEY (utilisateur_id, signalement_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id) ON DELETE CASCADE,
    FOREIGN KEY (signalement_id) REFERENCES signalement(signalement_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: intervention_signalement (Relation entre intervention et signalement)
CREATE TABLE intervention_signalement (
    intervention_id INT NOT NULL,
    signalement_id INT NOT NULL,
    PRIMARY KEY (intervention_id, signalement_id),
    FOREIGN KEY (intervention_id) REFERENCES intervention(intervention_id) ON DELETE CASCADE,
    FOREIGN KEY (signalement_id) REFERENCES signalement(signalement_id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- Table: utilisateur_notification (Relation entre utilisateur et notification)
CREATE TABLE utilisateur_notification (
    utilisateur_id INT NOT NULL,
    notification_id INT NOT NULL,
    PRIMARY KEY (utilisateur_id, notification_id),
    FOREIGN KEY (utilisateur_id) REFERENCES utilisateur(utilisateur_id) ON DELETE CASCADE,
    FOREIGN KEY (notification_id) REFERENCES notification(notification_id) ON DELETE CASCADE
) ENGINE=InnoDB;

