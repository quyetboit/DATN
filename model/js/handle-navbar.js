const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);

let btnsNav = $$('.nav__item');
Array.from(btnsNav);
btnsNav.forEach(function (btn) {
    
    btn.onclick = function () {
        this.classList.add('active')
    }
})