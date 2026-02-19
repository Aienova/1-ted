// Script pour imprimer le CV
document.addEventListener('DOMContentLoaded', () => {
    const printButton = document.getElementById('print-cv-button');
    
    if (printButton) {
        printButton.addEventListener('click', () => {
            // Lancer l'impression du CV
            window.print();
        });
    }
});
