let btnsNav = document.querySelectorAll('.nav__item');
Array.from(btnsNav);
btnsNav.forEach(function (btn) {
    
    btn.onclick = function () {
        this.classList.add('active')
    }
})