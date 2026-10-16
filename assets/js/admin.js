(function ($) {
    'use strict';
    $(function () {
        $('.wem-ps-image-wrap').each(function () {
            var $wrap = $(this);
            $wrap.on('click', '.wem-ps-upload', function (event) {
                event.preventDefault();
                var frame = wp.media({ title: 'Choose surface image', button: { text: 'Use this image' }, library: { type: 'image' }, multiple: false });
                frame.on('select', function () {
                    var item = frame.state().get('selection').first().toJSON();
                    var source = item.sizes && item.sizes.medium ? item.sizes.medium.url : item.url;
                    $wrap.find('.wem-ps-image-id').val(item.id);
                    $wrap.find('.wem-ps-preview').empty().append($('<img>', { src: source, alt: '' }));
                    var filename = (item.filename || '').trim();
                    if (!filename && item.url) {
                        var lastPart = item.url.split('/').pop().split('?')[0];
                        try { filename = decodeURIComponent(lastPart); } catch (error) { filename = lastPart; }
                    }
                    $wrap.find('.wem-ps-filename').text(filename);
                });
                frame.open();
            });
            $wrap.on('click', '.wem-ps-remove', function (event) {
                event.preventDefault();
                $wrap.find('.wem-ps-image-id').val('');
                $wrap.find('.wem-ps-preview').empty();
                $wrap.find('.wem-ps-filename').empty();
            });
        });
    });
})(jQuery);
