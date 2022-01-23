function Validator(option) {

    var selectorRules = {}

    // Xử lý validate
    function validate (inputElement, rule) {
        var elementContain = inputElement.closest('.form__group')
        console.log(elementContain)
        var formMessage = elementContain.querySelector(option.errorMessageSelector)
        var messageError

        var rules = selectorRules[rule.selector]

        console.log(selectorRules)
        
        for(var i = 0; i < rules.length; ++i) {
            messageError = rules[i](inputElement.value)
            if(messageError) {
                break
            }
        }

        if(messageError) {
            formMessage.innerHTML = messageError
            elementContain.classList.add('invalid')
        } else {
            formMessage.innerHTML = ''
            elementContain.classList.remove('invalid')
        }

        return !messageError
    }
    
    var formElement = document.querySelector(option.form)


    if (formElement) {
        // xử lý validate khi formsubmit
        formElement.onsubmit = function (e) {
            // hủy bỏ hành vi mặc định
            e.preventDefault()

            var formValidate = true
            // validate khi submit
            option.rules.forEach(function(rule) {
                var inputElement = formElement.querySelector(rule.selector)
                var isValidate = validate(inputElement, rule)
                if(!isValidate) {
                    formValidate = false
                }
            })

            if(formValidate) {
                // trường hợp có phương thức Submit đc gọi
                if(typeof option.submit === 'function') {
                    var formInputElements = formElement.querySelectorAll('[name]:not([disabled])')
                    var dataForm = Array.from(formInputElements).reduce(function(values, input) {
                        values[input.name] = input.value
                        return values
                    }, {})
                    option.submit(dataForm)
                }
                // trường hợp submit theo hành vi mặc định của trình duyệt
                else {
                    formElement.submit()
                }
            }

        }

        // xử lý validate khi onblur, oninput
        option.rules.forEach(function (rule) {
            
            if (Array.isArray(selectorRules[rule.selector])) {
                selectorRules[rule.selector].push(rule.test)
            } else {
                selectorRules[rule.selector] = [rule.test]
            }

            var inputElement = formElement.querySelector(rule.selector)
            // xử lý khi blur khỏi input
            inputElement.onblur = function () {
                validate(inputElement, rule)
            }
            // xử lý khi nhập input
            inputElement.oninput = function () {
                var elementContain = inputElement.closest('.form__group')
                var formMessage = elementContain.querySelector(option.errorMessageSelector)
                formMessage.innerHTML = ''
                elementContain.classList.remove('invalid')
            }

        })
    }

}


Validator.isRequired = function (selector, message) {
    return {
        selector,
        test: function (value) {
            return value.trim() ? undefined : message || 'Vui lòng nhập trường này'
        }
    }
}

Validator.isEmail = function (selector, message) {
    return {
        selector,
        test: function (value) {
            var emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/
            return emailRegex.test(value) ? undefined : message || 'Trường này phải là email'
        }
    }
}

Validator.minLength = function (selector, min, message) {
    return {
        selector,
        test: function (value) {
            return value.length >= min ? undefined : message || `Mật khẩu tối thiểu ${min} kí tự`
        }
    }
}

Validator.confirm = function (selector, getData, message) {
    return {
        selector,
        test: function (value) {
            return value === getData() ? undefined : message || 'Xác nhận không chính xác'
        }
    }
}