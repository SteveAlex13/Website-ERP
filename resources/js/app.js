

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('click', (event) => {
    const printLink = event.target.closest('[data-print-invoice]');

    if (! printLink) {
        return;
    }

    event.preventDefault();

    const printFrame = document.createElement('iframe');
    printFrame.hidden = true;
    printFrame.src = printLink.href;
    printFrame.title = 'Cetak invoice';

    printFrame.addEventListener('load', () => {
        const printWindow = printFrame.contentWindow;

        if (! printWindow) {
            printFrame.remove();

            return;
        }

        printWindow.addEventListener('afterprint', () => printFrame.remove(), { once: true });
        printWindow.focus();
        printWindow.print();
    }, { once: true });

    document.body.appendChild(printFrame);
});
