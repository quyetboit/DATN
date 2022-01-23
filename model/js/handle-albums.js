let wrapAlbumsList = document.querySelector('.albums__list-album');
    let albumsElement = wrapAlbumsList.querySelectorAll('.albums__list-item');
    let closeAlterBoxBtn = wrapAlbumsList.querySelector('.albums__alter-icon');
    
    // handle create album
    let btnSendCreate = document.querySelector('.albums__form-create-btn');
    btnSendCreate.onclick = function () {
        let inputNameAlbum = document.querySelector('#albums__create-from input[name=album-name]');
        let albumName = inputNameAlbum.value;
        let btnCloseBoxCreate = document.querySelector('#albums__create-from .albums__box-close-icon');
        const ajax_xhr = new XMLHttpRequest();
        ajax_xhr.onload = function () {
            let newNodeAlbum = document.createElement('li');
            wrapAlbumsList.appendChild(newNodeAlbum);
            newNodeAlbum.outerHTML = this.responseText;
            // reload elemnt node and event
            albumsElement = wrapAlbumsList.querySelectorAll('.albums__list-item');
            hanldeActionAlBum();
        }
        let url = `../model/php/handle-albums.php?action=create_album&album_name=${albumName}`;
        ajax_xhr.open("GET", url);
        ajax_xhr.send();
        btnCloseBoxCreate.click();
        inputNameAlbum.value = "";
    }
    // handle modify, delete album and get song of albums
    closeAlterBoxBtn.onclick = function () {
        this.closest('.albums__alter-box').classList.add('hidden');
    }

    Array.from(albumsElement);
    function hanldeActionAlBum() {
        albumsElement.forEach(function (album, index) {
            album.onclick = function (e) {
                let oldNameAlbum = this.querySelector('.albums__item-link').innerText;
                let idAlbum = this.getAttribute('data-id-album');
                
                if (e.target.closest('.albums__option')) {
                    if (e.target.closest('.albums__option-link-delete')) {
    
                        let currentElement = e.target.closest('.albums__option-link-delete');
                        let isConfirm = confirm('Bạn có chắc muốn xoá album: ' + oldNameAlbum);
                        if (isConfirm) {
                            const ajax_xhr = new XMLHttpRequest();
                            ajax_xhr.onload = function () {
                                alert(this.responseText);
                            }
                            ajax_xhr.open("GET", "../model/php/handle-albums.php?action=delete_album&id_album=" + idAlbum);
                            ajax_xhr.send();
                            currentElement.closest('.albums__list-item').remove();
                        }
    
                    } else if (e.target.closest('.albums__option-link-update')) {
                        
                        let currentElement = e.target.closest('.albums__option-link-update');
                        let wrapCurrent = currentElement.closest('.albums__list-item');
                        let boxAlter = wrapAlbumsList.querySelector('.albums__alter-box');
                        let inputAlterName = boxAlter.querySelector('#album_alter-name');
    
                        inputAlterName.setAttribute('data-id-album', idAlbum);
                        inputAlterName.setAttribute('index-element', index);
                        inputAlterName.value = oldNameAlbum;
                        boxAlter.classList.remove('hidden');
    
                    }
                } else {
                    
                    let elementWrapSong = document.querySelector('.songs__wrapper');
                    elementWrapSong.setAttribute('data-id-album', idAlbum);
                    const ajax_xhr = new XMLHttpRequest();
                    ajax_xhr.onload = function () {
                        elementWrapSong.innerHTML = this.responseText;
                        setTimeout(function () {
                            play.start();
                        }, 0);
                    }
                    let url = `../model/php/handle-albums.php?action=get_song_album&id_album=${idAlbum}`;
                    ajax_xhr.open("GET", url, false);
                    ajax_xhr.send();
                }
            }
        })
    }

    hanldeActionAlBum();
    setTimeout(function(){
        albumsElement[0].click();
    }, 5)
    // handle update
    let btnUpdate = wrapAlbumsList.querySelector('#album_update-name');
    let inputElement = wrapAlbumsList.querySelector('#album_alter-name');
    
    btnUpdate.onclick = function () {

        const ajax_xhr = new XMLHttpRequest();
        let idAlbum = inputElement.getAttribute('data-id-album');
        let newNameAlbum = inputElement.value;
        let indexElement = inputElement.getAttribute('index-element');
        let url = `../model/php/handle-albums.php?action=update_album&id_album=${idAlbum}&new_name_album=${newNameAlbum}`;
        ajax_xhr.open("GET", url);
        ajax_xhr.send();
        albumsElement[indexElement].querySelector('.albums__item-link').innerText = newNameAlbum;
        closeAlterBoxBtn.click();

    }