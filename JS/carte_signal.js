document.getElementById("btn-localiser").addEventListener("click", function () {
    const info = document.getElementById("localisation-info");
    const inputLieu = document.getElementById("lieu");

    info.innerText = "📍 Localisation en cours...";

    if (!navigator.geolocation) {
        info.innerText = "❌ Géolocalisation non supportée par votre navigateur.";
        return;
    }

    navigator.geolocation.getCurrentPosition(
        async function (position) {
            const lat = position.coords.latitude;
            const lon = position.coords.longitude;

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`);
                const data = await response.json();

                if (data && data.display_name) {
                    inputLieu.value = data.display_name;
                    info.innerText = "✅ Position détectée automatiquement.";
                } else {
                    info.innerText = "❗ Impossible de trouver une adresse.";
                }
            } catch (error) {
                info.innerText = "⚠️ Erreur lors de la récupération de l'adresse.";
            }
        },
        function (error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    info.innerText = "❌ Autorisation de localisation refusée.";
                    break;
                case error.POSITION_UNAVAILABLE:
                    info.innerText = "🌐 Position non disponible.";
                    break;
                case error.TIMEOUT:
                    info.innerText = "⏳ Temps de localisation dépassé.";
                    break;
                default:
                    info.innerText = "❗ Erreur inconnue.";
            }
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
});
