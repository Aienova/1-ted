// Script pour imprimer le CV
document.addEventListener('DOMContentLoaded', () => {
    const printButton = document.getElementById('print-cv-button');
    const cvContainer = document.querySelector('.cv-container');

    const getPrintStyles = () => {
        const links = Array.from(document.querySelectorAll('link[rel="stylesheet"]'))
            .map(link => `<link rel="stylesheet" href="${link.href}">`)
            .join('');

        const inlineStyles = Array.from(document.querySelectorAll('style'))
            .map(style => `<style>${style.innerHTML}</style>`)
            .join('');

        return `${links}${inlineStyles}`;
    };

    const printCvOnly = () => {
        if (!cvContainer) {
            console.error('Aucun élément .cv-container trouvé pour l\'impression.');
            return;
        }

        const printWindow = window.open('', '_blank', 'width=1024,height=768');
        if (!printWindow) {
            console.error('Impossible d\'ouvrir la fenêtre d\'impression (popup bloquée).');
            return;
        }

        printWindow.document.open();
        printWindow.document.write(`
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Impression CV</title>
                ${getPrintStyles()}
                <style>
                    body { margin: 0; padding: 0; background: #fff; }
                    .cv-container { margin: 0 !important; max-width: 100% !important; }
                    @page { size: auto; margin: 10mm; }
                </style>
            </head>
            <body>
                ${cvContainer.outerHTML}
            </body>
            </html>
        `);
        printWindow.document.close();

        printWindow.onload = () => {
            printWindow.focus();
            printWindow.print();
            printWindow.close();
        };
    };
    
    if (printButton) {
        printButton.addEventListener('click', () => {
            printCvOnly();
        });
    }
});
