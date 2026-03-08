function startLoader(action) {


    const loaderScreen = document.getElementById('loader-screen');
        loaderScreen.setAttribute('data-end', 'false');
    loaderScreen.setAttribute('data-action', action);
    loaderScreen.setAttribute('data-open', 'true');
    
}

function stopLoader() {
    const loaderScreen = document.getElementById('loader-screen');
    
    // Lancer l'animation de fin (90% -> 100%)
    loaderScreen.setAttribute('data-end', 'true');
    
    // Attendre 1.5 seconde avant de fermer complètement
    setTimeout(() => {
        loaderScreen.setAttribute('data-open', 'false');
        loaderScreen.setAttribute('data-action', 'default');
        loaderScreen.setAttribute('data-end', 'false');
    }, 1500);
}