/* Copy examples on the Usage admin page without additional dependencies. */
(function () {
    'use strict';
    document.addEventListener('click', function (event) {
        var button = event.target.closest('.wem-ps-copy');
        if (!button) { return; }
        var value = button.getAttribute('data-copy') || '';
        function feedback(message) {
            button.textContent = message;
            window.setTimeout(function () { button.textContent = 'Copy'; }, 1400);
        }
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(value).then(function () { feedback('Copied!'); }, function () { feedback('Copy failed'); });
        } else {
            var input = document.createElement('textarea');
            input.value = value;
            input.setAttribute('readonly', '');
            input.style.position = 'fixed';
            input.style.opacity = '0';
            document.body.appendChild(input);
            input.select();
            try { feedback(document.execCommand('copy') ? 'Copied!' : 'Copy failed'); } catch (e) { feedback('Copy failed'); }
            input.remove();
        }
    });
}());
