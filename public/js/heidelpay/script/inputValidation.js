var NGWInputValidation = function () {

    var cardNumberInput = document.getElementById("cardNumber"),
        brandInput = document.getElementById("brand");
    // ### check creditcard brand and credit/debit card luhn algorithm ###

    this.checkCardNumber = function (wrongParameters, missingParameters) {

        if (cardNumberInput === null) {
            missingParameters.push(cardNumberInput.name);
            return false;
        }

        if (brandInput === null) {
            missingParameters.push(brandInput.name);
            return false;
        }

        var cardNumber = cardNumberInput.value.replace(/ /g,""),
            brand = brandInput.value;

        if (!cardNumber || 0 === cardNumber.length || cardNumberInput === null) {
            missingParameters.push(cardNumberInput.name);
            return false;
        }


        if (containsAlpha(cardNumber)
            || !checkCreditCardNumberForBrandSpecifics(brand, cardNumber)
            || !checkLuhnAlgo(cardNumber)) {
            wrongParameters.push(cardNumberInput.name);
            return false;
        }


        return true;
    };

    // luhn check for credit/debit cards
    var checkLuhnAlgo = function (cardNumber) {
        var actDigit,
            sumOfAll = parseInt(cardNumber.substr(cardNumber.length - 1));

        for (var i = cardNumber.length - 2; i >= 0; i--)
        {
            actDigit = parseInt(cardNumber.substring(i, i + 1));
            if (((cardNumber.length - 2 - i) % 2) == 0)
            {
                // left shift (double the number)
                actDigit <<= 1;
                if (actDigit > 9)
                {
                    actDigit -= 10;
                    sumOfAll ++;
                }
            }
            sumOfAll += actDigit;
        }

        if ((sumOfAll % 10) != 0) {
            return false;
        }
        return true;
    };

    // check brand specific number for creditcards
    var checkCreditCardNumberForBrandSpecifics = function (brand, cardNumber) {

        var regExp = /^[0-9]*$/;

        if (brand == "VISA")
            regExp = /^4(?:(?:\d{12})|(?:\d{15}))$/;
        else if (brand == "MASTER")
            regExp = /^[25][1-5]\d{14}$/;
        else if (brand == "AMEX")
            regExp = /^3(?:4|7)\d{13}$/;
        else if (brand == "DISCOVER")
            regExp = /^6011\d{12}$/;
        else if (brand == "DINERS")
            regExp = /^3(?:(?:0[0-5]\d{11})|(?:(?:6|8)\d{12}))$/;
        else if (brand == "JCB")
            regExp = /^(?:(?:3\d{15})|(?:2131\d{11})|(?:1800\d{11}))$/;
        else if (brand == "ENROUTE")
            regExp = /^(?:(?:2014)|(?:2149))\d{11}$/;
        else if (brand == "CARTEBLEUE")
            regExp = /^4(?:(?:\d{12})|(?:\d{15}))$/;
        else  {
            // if the brand is unknown there is no check and the number is ok
            return true
        }

        if (!regExp.test(cardNumber)) {
            return false;
        }

        return true;
    };

    // set brand from creditcard
    this.setBrandForCreditCard = function () {

        if (cardNumberInput !== null) {
            // credit cards
            if (/^4(?:\d*)$/.test(cardNumberInput.value))
                selectBrand("VISA");
            else if (/^[25][1-5]\d*$/.test(cardNumberInput.value))
                selectBrand("MASTER");
            else if (/^3(?:4|7)\d*$/.test(cardNumberInput.value))
                selectBrand("AMEX");
            else if (/^6011\d*$/.test(cardNumberInput.value))
                selectBrand("DISCOVER");
            else if (/^3(?:(?:0[0-5]\d*)|(?:(?:6|8)\d*))$/.test(cardNumberInput.value))
                selectBrand("DINERS");
            else if (/^(?:(?:3\d*)|(?:2131\d*)|(?:1800\d*))$/.test(cardNumberInput.value))
                selectBrand("JCB");
        }

    };

    var selectBrand = function (value) {
        var options = brandInput.options;
        for(var option, j = 0; option = options[j]; j++) {
            if(option.value == value) {
                brandInput.selectedIndex = j;
                break;
            }
        }
    }

    // ### check card expiry ###

    this.checkExpiryDate = function (wrongParameters, missingParameters) {
        var expirationMonth = document.getElementById("expirationMonth").value,
            expirationYear = document.getElementById("expirationYear").value;

        if (isNaN(parseInt(expirationMonth)) || isNaN(parseInt(expirationYear))) {
            missingParameters.push(document.getElementById("expirationMonth").name);
            missingParameters.push(document.getElementById("expirationYear").name);
            return false;
        }

        if (new Date(expirationYear, expirationMonth, 0) < new Date()) {
            wrongParameters.push(document.getElementById("expirationMonth").name);
            wrongParameters.push(document.getElementById("expirationYear").name);
            return false;
        }

        return true;
    };

    // ### check cvv ###

    this.checkCVV = function (wrongParameters) {
        var verificationInput = document.getElementById("verification");

        // if the verification field is present
        if (verificationInput !== null && isVisible(verificationInput)) {
            // the cvv should be numeric value and it should be 3 or 4 characters long
            if (containsAlpha(verificationInput.value)
                || verificationInput.value.length < 3
                || verificationInput.value.length > 4) {
                wrongParameters.push(document.getElementById("verification").name);
                return false;
            }
        }

        return true;

    };

    // ### creditcard holder ###

    this.checkCreditCardHolder = function (missingParameters) {
        var cardName = document.getElementById("cardName").value;
        // is credit card holder empty
        if (!cardName || 0 === cardName.length) {
            missingParameters.push(document.getElementById("cardName").name);
            return false;
        }
        return true;
    };

    // ### Utils ###

    var containsAlpha = function (toTest) {
        var regExpr = /[A-Za-z]/;
        return regExpr.test(toTest);
    };

    var isVisible = function (element) {
        return (element.offsetWidth !== 0 || element.offsetHeight !== 0);
    };
};
