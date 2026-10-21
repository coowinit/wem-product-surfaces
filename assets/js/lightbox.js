(function () {
    'use strict';

    var dialog = null;
    var image = null;
    var caption = null;

    function buildDialog() {
        dialog = document.createElement('dialog');
        dialog.className = 'wem-ps-dialog';
        dialog.setAttribute('aria-label', 'Surface image preview');
        var close = document.createElement('button');
        close.type = 'button';
        close.className = 'wem-ps-dialog-close';
        close.setAttribute('aria-label', 'Close image preview');
        close.textContent = '×';
        image = document.createElement('img');
        image.className = 'wem-ps-dialog-image';
        image.alt = '';
        caption = document.createElement('p');
        caption.className = 'wem-ps-dialog-caption';
        dialog.append(close, image, caption);
        document.body.appendChild(dialog);
        close.addEventListener('click', function () { dialog.close(); });
        dialog.addEventListener('click', function (event) {
            if (event.target === dialog) {
                var rect = dialog.getBoundingClientRect();
                if (event.clientX < rect.left || event.clientX > rect.right ||
                    event.clientY < rect.top || event.clientY > rect.bottom) {
                    dialog.close();
                }
            }
        });
        dialog.addEventListener('close', function () {
            image.removeAttribute('src');
        });
    }

    document.addEventListener('click', function (event) {
        var target = event.target.closest('button[data-wem-ps-lightbox]');
        if (!target) { return; }
        if (typeof HTMLDialogElement === 'undefined' || !HTMLDialogElement.prototype.showModal) {
            window.open(target.getAttribute('data-wem-ps-src'), '_blank', 'noopener');
            return; // Older browsers still provide access to the original image.
        }
        // Intercept before Elementor or theme-level delegated lightboxes handle the link.
        event.preventDefault();
        event.stopPropagation();
        event.stopImmediatePropagation();
        if (!dialog) { buildDialog(); }
        image.src = target.getAttribute('data-wem-ps-src');
        image.alt = target.getAttribute('data-title') || '';
        caption.textContent = target.getAttribute('data-title') || '';
        if (!dialog.open) { dialog.showModal(); }
    }, true);
}());
