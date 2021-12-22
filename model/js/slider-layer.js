function sliderLayerChange(wrapSilderSelector, sliderItemSelector) {
    let wrapSlider = document.querySelector(wrapSilderSelector);
    let sliderItem = wrapSlider.querySelectorAll(sliderItemSelector);
    let currentIndex = 0;
    let numItem = sliderItem.length;

    if (wrapSlider && sliderItem) {
        setInterval(function () {
            if (currentIndex === numItem) {
                currentIndex = 0;
            }
            addThird(sliderItem[currentIndex])
    
            if (currentIndex + 1 < numItem) {
                addFirst(sliderItem[currentIndex + 1]);
            } else {
                addFirst(sliderItem[0]);
            }
    
            if (currentIndex === numItem - 2) {
                addSecond(sliderItem[0])
            } else if (currentIndex === numItem - 1) {
                addSecond(sliderItem[1])
            } else {
                addSecond(sliderItem[currentIndex + 2]);
            }
    
            currentIndex++;
        }, 2000)
    }
}

function addFirst(element) {
    element.classList.remove('second');
    element.classList.add('first');
}

function addSecond(element) {
    element.classList.remove('third');
    element.classList.add('second');
}

function addThird(element) {
    element.classList.remove('first');
    element.classList.add('third');
}