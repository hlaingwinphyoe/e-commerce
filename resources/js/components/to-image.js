$(window).ready(function () {
    if ($('#to-image').length) {
        var node = document.getElementById('to-image');

        var id = node.getAttribute('data-id');

            htmlToImage.toJpeg(node, {quality: 0.95})
            .then(function (dataUrl) {
                // var container = document.getElementById('image-container');
                // var img = new Image();
                // img.src = dataUrl;
                // container.appendChild(img);
                var link = document.createElement('a');
                link.download = id + '.jpeg';
                link.href = dataUrl;
                link.innerHTML = 'Download';

                // var download = document.getElementById('download-btn-container');
                // download.appendChild(link);

                // document.getElementById('to-image').classList.add('d-none');

                link.click();
                window.history.go(-1);
            })
            .catch(function (error) {
                console.error('oops, something went wrong!', error);
            });
    }
});
