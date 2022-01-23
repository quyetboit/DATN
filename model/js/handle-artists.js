function handleArtist() {
    const listArtists = document.querySelectorAll('.artists__item');
    Array.from(listArtists);
    listArtists.forEach(function (artists, index) {
        artists.onclick = function () {
            
            const ajax_xhr = new XMLHttpRequest();
            let idArtist = this.getAttribute('data-id-artist');
            ajax_xhr.onload = function () {
                document.querySelector('.songs__wrapper.artists__wrap-songs').innerHTML = this.responseText;
                let btn = document.querySelector('.control__top-play-and-pause');
                btn.classList.remove('playing');
                play.start();
            }
            ajax_xhr.open("GET", "../model/php/handle-artists.php?id_artist=" + idArtist);
            ajax_xhr.send();

        }
    })
    listArtists[0].click();
}
