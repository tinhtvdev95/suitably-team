document.addEventListener('DOMContentLoaded', function () {
    console.log('gpw-admin-footer');

    let images = document.querySelectorAll('.upload-image');
    let zoomedImageContainer = document.createElement('div');
    zoomedImageContainer.classList.add('zoomed-image');
    document.body.appendChild(zoomedImageContainer);

    images.forEach(function (image) {
        image.addEventListener('click', function () {
            let imageUrl = image.src;

            let originalImageUrl = imageUrl.replace('-300x300', '');

            const imgClone = document.createElement('img');
            imgClone.src = originalImageUrl;
            zoomedImageContainer.innerHTML = '';
            zoomedImageContainer.appendChild(imgClone);
            zoomedImageContainer.classList.add('active');
        });
    });
    zoomedImageContainer.addEventListener('click', function () {
        zoomedImageContainer.classList.remove('active');
    });
});