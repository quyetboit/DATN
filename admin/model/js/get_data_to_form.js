
// account
function getCurrentAccountToForm(wrapSongsSelector, formSelector) {
    const wrapSongs = document.querySelector(wrapSongsSelector);
    const form = document.querySelector(formSelector);

    if(wrapSongs && form) {
        let fieldUsername = form.querySelector('input[name=account-username]');
        let fieldFullname = form.querySelector('input[name=account-fullname]');
        let fieldEmail = form.querySelector('input[name=account-email]');
        const btnDelForm = form.querySelector('input[name=delete]');

        const accountsElement = wrapSongs.querySelectorAll('.account__items');

        Array.from(accountsElement).forEach(function(accountElement) {
            accountElement.onclick = function(e) {
                const btnDel = this.querySelector('.btn');
                if (e.target === btnDel) {
                    btnDelForm.click();
                }

                fieldUsername.value = this.querySelector('.account__username').innerText;
                fieldFullname.value = this.querySelector('.account__fullname').innerText;
                fieldEmail.value = this.querySelector('.account__email').innerText;

            }
        })
    }
}

// genres
function getCurrentGenreToForm(wrapGenreSelector, formSelector) {
    const wrapGenres = document.querySelector(wrapGenreSelector);
    const form = document.querySelector(formSelector);

    if(wrapGenres && form) {
        let fieldGenreID = form.querySelector('input[name=genre-id]');
        let fieldGenreName = form.querySelector('input[name=genre-name]');
        const btnDelForm = form.querySelector('input[name=delete]');
    
        console.log(fieldGenreID, fieldGenreName, btnDelForm)
    
        const genresElement = wrapGenres.querySelectorAll('.genre__items');
        console.log(genresElement)
    
        Array.from(genresElement).forEach(function(genreElement) {
            genreElement.onclick = function(e) {
                const btnDel = this.querySelector('#btn-del');
                const btnModify = this.querySelector('#btn-modifi');
                if (e.target === btnDel) {
                    btnDelForm.click();
                } else if (e.target === btnModify) {
                    fieldGenreID.value = this.querySelector('.genre__id').innerText;
                    fieldGenreName.value = this.querySelector('.genre__name').innerText;
                }
            }
        })
    }
}

// artist
function getCurrentArtistToForm(wrapArtistSelector, formSelector) {
    const wrapArtist = document.querySelector(wrapArtistSelector);
    const form = document.querySelector(formSelector);

    if(wrapArtist && form) {
        let fieldArtistID = form.querySelector('input[name=artist-id]');
        let fieldArtistName = form.querySelector('input[name=artist-name]');
        const btnDelForm = form.querySelector('#btn-artist-del');
    
        const artistsElement = wrapArtist.querySelectorAll('.artists__items');
    
        Array.from(artistsElement).forEach(function(artistElement) {
            artistElement.onclick = function(e) {

                const btnDel = this.querySelector('#artists-delete-btn');
                const btnModify = this.querySelector('#artists-modify-btn');

                if (e.target === btnDel) {
                    btnDelForm.click();
                } else if (e.target === btnModify) {
                    fieldArtistID.value = this.querySelector('.artists__id').innerText;
                    fieldArtistName.value = this.querySelector('.artists__name').innerText;
                }
    
            }
        })
    }
}

// songs
function getCurrentSongToForm(wrapSongtSelector, formSelector) {
    const wrapSongsElement = document.querySelector(wrapSongtSelector);
    const form = document.querySelector(formSelector);

    if(wrapSongsElement && form) {
        let fieldSongID = form.querySelector('input[name=song-id]');
        let fieldSongtName = form.querySelector('input[name=song-name]');
        let fieldSongtTime = form.querySelector('input[name=song-time]');
        let fieldSongtAuthor = form.querySelector('select[name=song-author]');
        let fieldSongtGenre = form.querySelector('select[name=song-genres]');
        const btnDelForm = form.querySelector('#form__btn-song-del');
    
        const songsElement = wrapSongsElement.querySelectorAll('.songs__items');

        Array.from(songsElement).forEach(function(songElement) {
            songElement.onclick = function(e) {

                const btnDel = this.querySelector('.song-btn-del');
                const btnModify = this.querySelector('.song-btn-modify');

                if (e.target === btnDel) {
                    btnDelForm.click();
                } else if (e.target === btnModify) {
                    fieldSongID.value = this.querySelector('.songs__id').innerText;
                    fieldSongtName.value = this.querySelector('.songs__info-name').innerText;
                    fieldSongtTime.value = this.querySelector('.songs__time').innerText;
                    fieldSongtAuthor.value = this.querySelector('.songs__info-author').getAttribute('data-id-author');
                    fieldSongtGenre.value = this.querySelector('.songs__genre').getAttribute('data-id-genre');
                }
    
            }
        })
    }
}

function confirmDel() {
    return confirm("Bạn có chắc chắn muốn xoá không?");
}
