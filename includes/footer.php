<footer class="footer">
    <div class="footer-container">
        <!-- Section Contact -->
        <div class="footer-section contact">
            <h3>Contact</h3>
            <p>Email : contact@sneakerhaven.com</p>
            <p>Téléphone : +243 84176686</p>
            <p>Adresse : 123 Rue des Sneakers, Paris, France</p>
        </div>

        <!-- Section Informations supplémentaires -->
        <div class="footer-section info">
            <h3>Informations supplémentaires</h3>
            <p>Politique de confidentialité</p>
            <p>Conditions générales</p>
            <p>FAQ</p>
        </div>

        <!-- Section Réseaux sociaux -->
        <div class="footer-section social">
            <h3>Suivez-nous</h3>
            <a href="#" class="social-icon">Facebook</a>
            <a href="#" class="social-icon">Instagram</a>
            <a href="#" class="social-icon">Twitter</a>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2025 charis. Tous droits réservés.</p>
    </div>
</footer>
<style>
    /* Footer global styles */
.footer {
    background-color: #212529; /* Fond sombre */
    color: #fff; /* Texte blanc */
    padding: 70px 20px 20px 20px; /* Espacement augmenté */
}

.footer-container {
    display: flex;
    flex-wrap: wrap; /* Permet de passer en colonne sur petit écran */
    justify-content: space-between;
    gap: 30px; /* Espacement entre les sections augmenté */
    max-width: 1200px;
    margin: 0 auto;
}

/* Sections */
.footer-section {
    flex: 1; /* Prend un espace égal */
    min-width: 250px;
}

.footer-section h3 {
    font-size: 22px;
    margin-bottom: 20px; /* Espacement augmenté sous les titres */
    color: #fff;
}

.footer-section p,
.footer-section a,
.footer-section ul li {
    font-size: 16px; /* Texte légèrement agrandi */
    color: #ccc;
    margin-bottom: 15px; /* Espacement augmenté entre les lignes */
    text-decoration: none;
}

.footer-section a:hover {
    color: #fff; /* Couleur orange au survol */
}

.footer-section ul {
    padding: 0;
    list-style: none;
}

.footer-section ul li {
    margin-bottom: 10px;
}

/* Carte Google Map */
.footer-section.map iframe {
    border: 0;
    border-radius: 8px; /* Coins arrondis */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Légère ombre */
}

/* Réseaux sociaux */
.footer-section.social .social-icon {
    display: inline-block;
    margin-right: 15px; /* Plus d'espacement entre les icônes */
    color: #ccc;
    transition: color 0.3s;
}

.footer-section.social .social-icon:hover {
    color: #fff;
}

/* Footer bottom */
.footer-bottom {
    text-align: center;
    margin-top: 30px;
    border-top: 1px solid #555;
    padding-top: 20px;
    font-size: 14px;
    color: #ccc;
}
/* Responsive Design */
@media (max-width: 768px) {
    .footer-container {
        flex-direction: column; /* Passe en colonne */
        text-align: center;
    }
    .footer-section {
        min-width: 100%;
    }
}
</style>