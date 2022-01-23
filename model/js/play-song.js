// get element node
let wrapSong;
let songsElement;
let slidersElement;

let cdThumb;
let audio;
let controlSongName;
let controlAuthor;
let controlTime;
let controlCurrentTime;
let playAndPauseBtn;
let nextBtn;
let preBtn;
let randomBtn;
let loopBtn;
let durationBar;
let speakRange;
let idsSong;
let cdThumbAnimate;

const play = {
    currentIndex:  0,
    isLoop:  false,
    isRandom:  false,
    isPlay: false,

    getElements: function () {
        wrapSong = document.querySelector('.songs__wrapper');
        songsElement = wrapSong.querySelectorAll('.song__item');
        slidersElement = document.querySelectorAll('.songs__item');
        Array.from(songsElement);
        Array.from(slidersElement);

        cdThumb = document.querySelector('.control__song-img');
        audio = document.querySelector('#control__audio');
        controlSongName = document.querySelector('.control__song-name');
        controlAuthor = document.querySelector('.control__song-artists');
        controlTime = document.querySelector('.control__bottom-total-time');
        controlCurrentTime = document.querySelector('.control__bottom-current-time');
        playAndPauseBtn = document.querySelector('.control__top-play-and-pause');
        nextBtn = document.querySelector('.control__top-next-icon');
        preBtn = document.querySelector('.control__top-pre-icon');
        randomBtn = document.querySelector('.control__top-random-icon');
        loopBtn = document.querySelector('.control__top-loop-icon');
        durationBar = document.querySelector('.control__bottom-duration');
        speakRange = document.querySelector('#control__speak-range');
        idsSong = wrapSong.querySelectorAll('.song__action-tym');
    },

    getDataFormSongItem: function (element) {
        let pathThumb = element.querySelector('.song__thumb').getAttribute('src');
        let pathAudio = element.getAttribute('data-path');
        let songName = element.querySelector('.song__name').innerText;
        let author = element.querySelector('.song__artists').innerHTML;
        let time = element.querySelector('.song__time').innerText;
        
        return {
            thumb: pathThumb,
            audio: pathAudio,
            name: songName,
            author,
            time
        }
    },

    increaseNumPlay: function(id_song) {

        const ajax_xhr = new XMLHttpRequest();
        ajax_xhr.open("GET", "../model/php/handle-action-user.php?action=increase_num_play&id_song=" + id_song);
        ajax_xhr.send();

    },

    increaseNumDownload: function(id_song) {

        const ajax_xhr = new XMLHttpRequest();
        ajax_xhr.open("GET", "../model/php/handle-action-user.php?action=increase_num_download&id_song=" + id_song);
        ajax_xhr.send();

    },

    formatTime: function(second) {
        if(second <= 9.5) {
            return `00:0${second.toFixed(0)}`;
        } else if (second <= 59.5) {
            return `00:${second.toFixed(0)}`
        } else if ((second/60).toFixed(0) < 10) {
            var tg = (second % 60).toFixed(0)
            if(tg <= 9.5) tg = `0${tg}`
            return `0${(second/60).toFixed(0)}:${tg}`;
        } else {
            var tg = (second % 60).toFixed(0)
            if(tg <= 9.5) tg = `0${tg}`
            return `${(second/60).toFixed(0)}:${(second % 60).toFixed(0)}`;
        }
    },

    randomIdex: function(max, min, current) {
        var numSong;
        do {
            numSong = Math.ceil(Math.random(min, max) * max);
        } while (numSong === current);
        return numSong;
    },

    loadCurrentSong: function() {
        let data = this.getDataFormSongItem(songsElement[this.currentIndex]);
        songsElement[this.currentIndex].classList.add('active');
        cdThumb.setAttribute('src', data.thumb);
        audio.setAttribute('src', data.audio);
        controlSongName.innerText = data.name;
        controlAuthor.innerHTML = data.author;
        controlTime.innerText = data.time;
    },

    handleEvents: function() {
        _this = this;
        // active current song
        function activeCurrentSong() {
            songsElement.forEach(function(element, index) {
                element.classList.remove('active');

                if(_this.currentIndex === index) {
                    element.classList.add('active');
                    _this.increaseNumPlay(songsElement[index].getAttribute('data-id-song'));
                }
            })
        }

        // play and pause 
        playAndPauseBtn.onclick = function() {
            this.classList.toggle('playing', !_this.isPlay);
            _this.isPlay = !_this.isPlay;
            
            if(_this.isPlay) {
                audio.play();
                cdThumbAnimate.play();
            } else {
                audio.pause();
                cdThumbAnimate.pause();
            }
        }

        // duration bar and current time
        audio.ontimeupdate = function() {
            controlCurrentTime.innerText = _this.formatTime(this.currentTime) || '00:00';
            durationBar.value = (this.currentTime / this.duration * 100) | 0;
        }

        // seek
        durationBar.onmousedown = function(e) {
            audio.currentTime = (e.offsetX / this.offsetWidth) * audio.duration;
        }

        // next when end
        audio.onended = function() {
            if (_this.isLoop) {
                audio.play();
            } else {
                nextBtn.click();
            }
        }

        // next and pre when click btn
        nextBtn.onclick = function() {
            if(_this.isRandom) {
                _this.currentIndex = _this.randomIdex(songsElement.length - 1, 0, _this.currentIndex);
            } else {
                _this.currentIndex++;
            }

            if(_this.currentIndex >= songsElement.length) {
                _this.currentIndex = 0;
            }
            _this.loadCurrentSong();
            if(!_this.isPlay) {
                playAndPauseBtn.click();
            } else {
                audio.play();
            }
            activeCurrentSong();
        }

        preBtn.onclick = function() {
            if(_this.isRandom) {
                _this.currentIndex = _this.randomIdex(songsElement.length - 1, 0, _this.currentIndex);
            } else {
                _this.currentIndex--;
            }

            if(_this.currentIndex < 0) {
                _this.currentIndex = (songsElement.length - 1);
            }
            _this.loadCurrentSong();
            if(!_this.isPlay) {
                playAndPauseBtn.click();
            } else {
                audio.play();
            }
            activeCurrentSong();
        }

        // random
        randomBtn.onclick = function() {
            this.classList.toggle('active', !_this.isRandom);
            _this.isRandom = !_this.isRandom;
        }

        // loop
        loopBtn.onclick = function() {
            this.classList.toggle('active', !_this.isLoop);
            _this.isLoop = !_this.isLoop;
        }

        // rotate cdthumbneil
        const cdThumbAnimate = cdThumb.animate([
            {transform: 'rotate(360deg)'}
        ], {
            duration: 10000,
            iterations: Infinity
        })
        cdThumbAnimate.pause();

        // speaker
        speakRange.onmousedown = function(e) {
            audio.volume = (e.offsetX / this.offsetWidth).toFixed(1);
        }

        // click song in list
        songsElement.forEach(function(element, index) {
            element.onclick = function(e) {
                let idSong = this.getAttribute('data-id-song');
                // handle click like
                if (e.target.closest('.song__action-tym')) {

                    let currentElement = e.target.closest('.song__action-tym');
                    const ajax_xhr = new XMLHttpRequest();
                    let str = "";
                    if(currentElement.classList.contains('liked')) {
                        currentElement.classList.remove('liked');
                        str = `action=remove_favourite&id_song=${idSong}`;
                    } else {
                        currentElement.classList.add('liked');
                        str = `action=add_favourite&id_song=${idSong}`;
                    }
                    ajax_xhr.open("GET", "../model/php/handle-action-user.php?"+str);
                    ajax_xhr.send();

                // handle click option
                } else if (e.target.closest('#song__option-link-download')) {
                    _this.increaseNumDownload(idsSong[index].getAttribute('data-id-song'));
                }
                
                else if (e.target.closest('.song__option-del-song-user')) {
                    const ajax_xhr = new XMLHttpRequest();
                    ajax_xhr.open("POST", "../model/php/handle-upload.php", true);
                    ajax_xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    ajax_xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            if (this.responseText === 'del_success') {
                                songsElement[index].remove();
                                slidersElement[index].remove();
                                alert('Xoá thành công');
                            } else {
                                alert('Xoá thất bại');
                            }
                        }
                    }
                    let dataSend = `action=remove_song_user&id_song=${idSong}`;
                    ajax_xhr.send(dataSend);
                }

                else if (e.target.closest('.song__album-item')) {
                    let currentElement = e.target.closest('.song__album-item');
                    let idAlbum = currentElement.getAttribute('data-id-album');
                    const ajax_xhr = new XMLHttpRequest();
                    ajax_xhr.onload = function () {
                        alert(this.responseText);
                    }
                    let url = `../model/php/handle-albums.php?action=add_song_album&id_album=${idAlbum}&id_song=${idSong}`;
                    ajax_xhr.open("GET", url);
                    ajax_xhr.send();
                }
                else if (e.target.closest('.song__del_song_album')) {
                    let idAlbum = this.closest('.songs__wrapper').getAttribute('data-id-album');
                    const ajax_xhr = new XMLHttpRequest();
                    ajax_xhr.onload = function () {
                        alert(this.responseText);
                    }
                    let url = `../model/php/handle-albums.php?action=del_song_album&id_album=${idAlbum}&id_song=${idSong}`;
                    ajax_xhr.open("GET", url);
                    ajax_xhr.send();
                    this.remove();

                } else if (e.target.closest('.song__option-comment')) {
                    let boxComments = document.querySelector('#app__comments');
                    boxComments.classList.remove('hidden');
                    boxComments.setAttribute('data-id-song', idSong);
                    const ajax_xhr = new XMLHttpRequest();
                    ajax_xhr.onload = function () {
                        let wrapListComments = document.querySelector('#app__comments .comment__list');
                        wrapListComments.innerHTML = this.responseText;
                        crudComments();
                    }
                    let url = `../model/php/handle-comment.php?action=show_comments&id_song=${idSong}`;
                    ajax_xhr.open("GET", url);
                    ajax_xhr.send();
                }
                
                else if (e.target.closest('.song__artist')) {
                    let currentElement = e.target.closest('.song__artist');
                    let idArtist = currentElement.getAttribute('data-id-artist');
                    const ajax_xhr = new XMLHttpRequest();
                    ajax_xhr.open("POST", "../model/php/handle-action-user.php", true);
                    ajax_xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    ajax_xhr.onreadystatechange = function() {
                        if (this.readyState == 4 && this.status == 200) {
                            let wrapper = document.querySelector('.content_wrapper');
                            let pickGenre = document.querySelector('.content__pick-genre');
                            if (pickGenre) {
                                pickGenre.remove();
                            }

                            wrapper.innerHTML = this.responseText;
                            let listArtists = document.querySelectorAll('.artists__wrap-artist .artists__item');
                            Array.from(listArtists);
                            listArtists.forEach(function (artist) {
                                if (artist.getAttribute('data-id-artist') == idArtist) {
                                    handleArtist();
                                    artist.click();
                                }
                            });
                            play.start();
                        }
                    }
                    let dataSend = `action=nav_to_artist&id_artist=${idArtist}`;
                    ajax_xhr.send(dataSend);
                } else {
                    _this.currentIndex = index;
                    _this.loadCurrentSong();
                    if (!_this.isPlay) {
                        playAndPauseBtn.click();
                    } else {
                        audio.play();
                    }
                    activeCurrentSong();
                }
            }
        });

        // click onslider
        slidersElement.forEach(function(element, index) {
            element.onclick = function() {
                _this.currentIndex = index;
                _this.loadCurrentSong();
                if (!_this.isPlay) {
                    playAndPauseBtn.click();
                } else {
                    audio.play();
                }
                activeCurrentSong();
            }
        })
    },

    start: function() {
        this.getElements();
        this.loadCurrentSong();
        this.handleEvents();
    }
}