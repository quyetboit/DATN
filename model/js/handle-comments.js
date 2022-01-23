let btnCloseBoxAlbum = document.querySelector('#app__comments .comment__close-icon');
btnCloseBoxAlbum.onclick = function () {
    this.closest('#app__comments').classList.add('hidden');
}

function crudComments() {
    handleUpdateComment('.comment__list', '.comment__item');
    const ajax_xhr = new XMLHttpRequest();
    let boxComment = document.querySelector('#app__comments');
    let idSong = boxComment.getAttribute('data-id-song');
    let btnSendComment = boxComment.querySelector('button[name=comment__submit]');
    let btnUpdateComment = boxComment.querySelector('button[name=comment__update]');
    
    if (btnSendComment) {
        btnSendComment.onclick = function () {
            let commentContent = boxComment.querySelector('textarea[name=comment-content]').value;
            ajax_xhr.open("POST", "../model/php/handle-comment.php", true);
            ajax_xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajax_xhr.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    let wrapListCommentsElement = boxComment.querySelector('.comment__list');
                    let headMessEmpty = wrapListCommentsElement.querySelector('h1');
                    if (headMessEmpty) {
                        headMessEmpty.remove();
                    }
                    let newItemComment = document.createElement('div');
                    let newChildElement = wrapListCommentsElement.appendChild(newItemComment);
                    newChildElement.outerHTML = this.responseText;
                    boxComment.querySelector('textarea[name=comment-content]').value = "";
                    
                    handleUpdateComment('.comment__list', '.comment__item');
                }
            };
            let dataSend = `action=send_comment&id_song=${idSong}&content=${commentContent}`;
            ajax_xhr.send(dataSend);
        }
    }
}

function handleUpdateComment(wrapCommentSelector, commentItemSelector) {
    let wrapComment = document.querySelector(wrapCommentSelector);
    let commentsElement = wrapComment.querySelectorAll(commentItemSelector);
    Array.from(commentsElement);

    let btnUpdate = document.querySelector('button[name=comment__update]'); 
    let btnCancel = document.querySelector('button[name=comment__cancel]'); 
    let btnSubmit = document.querySelector('button[name=comment__submit]');
    let fieldComment = document.querySelector('.comment__my-content');

    
    commentsElement.forEach(function (comment, index) {
        comment.onclick = function (e) {
            _this = this;
            let idComment = this.getAttribute('data-id-comment');
            if (e.target.closest('#comment__edit-comment')) {
                let contentComment = this.querySelector('.comment__content').innerText;

                btnUpdate.classList.remove('hidden');
                btnCancel.classList.remove('hidden');
                btnSubmit.classList.add('hidden');
                fieldComment.value = contentComment;
                fieldComment.closest('.comment__my-comment').setAttribute('data-id-comment', idComment);
                fieldComment.closest('.comment__my-comment').setAttribute('comment-element-index', index);
            } if (e.target.closest('#comment__del-comment')) {
                const ajax_xhr = new XMLHttpRequest();
                ajax_xhr.open("POST", "../model/php/handle-comment.php", true);
                ajax_xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                ajax_xhr.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        _this.remove();
                        alert(this.responseText);
                    }
                };
                let dataSend = `action=del_comment&id_comment=${idComment}`;
                ajax_xhr.send(dataSend);
            }
        }
    });

    btnCancel.onclick = function() {
        btnUpdate.classList.add('hidden');
        btnCancel.classList.add('hidden');
        btnSubmit.classList.remove('hidden');
        fieldComment.value = '';
    }

    btnUpdate.onclick = function () {
        let idComment = this.closest('.comment__my-comment').getAttribute('data-id-comment');
        let indexComment = this.closest('.comment__my-comment').getAttribute('comment-element-index');
        let newCommentContent = fieldComment.value;
        const ajax_xhr = new XMLHttpRequest();
        ajax_xhr.open("POST", "../model/php/handle-comment.php", true);
        ajax_xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax_xhr.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                commentsElement[indexComment].querySelector('.comment__content').innerText = fieldComment.value;
                btnCancel.click();
                fieldComment.value = "";
            }
        }
        let dataSend = `action=update_comment&id_comment=${idComment}&new_comment=${newCommentContent}`;
        ajax_xhr.send(dataSend);
    }
}