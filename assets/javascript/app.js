document.getElementById('submitBtn').addEventListener('click', function() {
    // Sélectionne tous les éléments avec la classe .error-value
    const elements = document.querySelectorAll('.error-value', '.error-message', 'succes-message');

    // Itère sur tous les éléments sélectionnés
    elements.forEach(function(element) {
        // Vérifie si l'élément est actuellement affiché
        if (window.getComputedStyle(element).display === 'none') {
            // Si l'élément est caché, le rendre visible
            element.style.display = 'block';
        } else {
            // Sinon, le cacher
            element.style.display = 'none';
        }
    });
});
