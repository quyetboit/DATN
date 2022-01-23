let btnSearch = document.querySelector('.head__search-btn');
btnSearch.onclick = function () {
    let inputKeyword = document.querySelector('.head__search-value');
    let keyWord = removeVietnameseTones(inputKeyword.value);
    
    const ajax_xhr = new XMLHttpRequest();
    ajax_xhr.open("POST", "../model/php/handle-search.php", true);
    ajax_xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    ajax_xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            let wrapper = document.querySelector('.content_wrapper');
            let pickGenreElement = document.querySelector('.content__pick-genre');
            
            if (pickGenreElement) {
                pickGenreElement.classList.add('hidden');
            }
            wrapper.outerHTML = this.responseText;
            handelPickOptionresult();
            let btnSong = document.querySelector('.search__option-songs');
            btnSong.click();
        }
    }
    let dataSend = `action=search&keyword=${keyWord}`;
    ajax_xhr.send(dataSend);
}

function handelPickOptionresult () {
    let songOptionResult = document.querySelector('.search__option-songs');
    let artistOptionResult = document.querySelector('.search__option-artists');
    let keyWord = document.querySelector('.search__keyword i').innerText;

    songOptionResult.onclick = function () {
        const ajax_xhr = new XMLHttpRequest();
        ajax_xhr.open("POST", "../model/php/handle-search.php", true);
        ajax_xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let wrapResult = document.querySelector('.search__result');
                wrapResult.innerHTML = this.responseText;
                // handelPickOptionresult();
                setTimeout(function () {
                    play.start();
                }, 20);
            }
        }
        let dataSend = `action=get_song_search&keyword=${keyWord}`;
        ajax_xhr.send(dataSend);
    }

    artistOptionResult.onclick = function () {
        const ajax_xhr = new XMLHttpRequest();
        ajax_xhr.open("POST", "../model/php/handle-search.php", true);
        ajax_xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                let wrapResult = document.querySelector('.search__result');
                wrapResult.innerHTML = this.responseText;
                handleClickArtistInSearch();
            }
        }
        let dataSend = `action=get_artists_search&keyword=${keyWord}`;
        ajax_xhr.send(dataSend);
    }
}

function handleClickArtistInSearch () {
    let listArtists = document.querySelectorAll('.result__artist');
    Array.from(listArtists);
    listArtists.forEach(function (element, index) {
        element.onclick = function () {
            let idArtist = this.getAttribute('data-id-artist');
            const ajax_xhr = new XMLHttpRequest();
            ajax_xhr.open("POST", "../model/php/handle-search.php", true);
            ajax_xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    let wrapSongArtist = document.querySelector('.search__result');
                    wrapSongArtist.innerHTML = this.responseText;
                    play.start();
                }
            }
            let dataSend = `action=get_song_of_artist_search&id_artist=${idArtist}`;
            ajax_xhr.send(dataSend);
        }
    })
}

function removeVietnameseTones(str) {
    str = str.replace(/à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ/g,"a"); 
    str = str.replace(/è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/g,"e"); 
    str = str.replace(/ì|í|ị|ỉ|ĩ/g,"i"); 
    str = str.replace(/ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ/g,"o"); 
    str = str.replace(/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ/g,"u"); 
    str = str.replace(/ỳ|ý|ỵ|ỷ|ỹ/g,"y"); 
    str = str.replace(/đ/g,"d");
    str = str.replace(/À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ/g, "A");
    str = str.replace(/È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/g, "E");
    str = str.replace(/Ì|Í|Ị|Ỉ|Ĩ/g, "I");
    str = str.replace(/Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ/g, "O");
    str = str.replace(/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ/g, "U");
    str = str.replace(/Ỳ|Ý|Ỵ|Ỷ|Ỹ/g, "Y");
    str = str.replace(/Đ/g, "D");
    // Some system encode vietnamese combining accent as individual utf-8 characters
    // Một vài bộ encode coi các dấu mũ, dấu chữ như một kí tự riêng biệt nên thêm hai dòng này
    str = str.replace(/\u0300|\u0301|\u0303|\u0309|\u0323/g, ""); // ̀ ́ ̃ ̉ ̣  huyền, sắc, ngã, hỏi, nặng
    str = str.replace(/\u02C6|\u0306|\u031B/g, ""); // ˆ ̆ ̛  Â, Ê, Ă, Ơ, Ư
    // Remove extra spaces
    // Bỏ các khoảng trắng liền nhau
    str = str.replace(/ + /g," ");
    str = str.trim();
    // Remove punctuations
    // Bỏ dấu câu, kí tự đặc biệt
    str = str.replace(/!|@|%|\^|\*|\(|\)|\+|\=|\<|\>|\?|\/|,|\.|\:|\;|\'|\"|\&|\#|\[|\]|~|\$|_|`|-|{|}|\||\\/g," ");
    return str;
}