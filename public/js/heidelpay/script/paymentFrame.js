(function() {
    // consts
    var WRONG_PARAMETERS = 'PROCESSING.WRONG.PARAMETERS',
        MISSING_PARAMETERS = 'PROCESSING.MISSING.PARAMETERS',
        POST_VALIDATION = 'POST.VALIDATION',
        PROCESSING_RESULT = 'PROCESSING.RESULT',
        PROCESSING_STATUS = 'PROCESSING.STATUS',
        RESULT_NOK = 'NOK',
        REJECTED_VALIDATION = 'REJECTED_VALIDATION',
        REDIRECT_URL = 'PROCESSING.REDIRECT_URL',
        PROCESSING_PARAMS = 'PROCESSING.REDIRECT.PARAM.',
        NEXT_TARGET = 'FRONTEND.NEXT_TARGET',
        PROCESSING_RECOVERABLE = 'PROCESSING.RECOVERABLE',
        PROCESSING_RECOVERABLE_NEW_STATE = 'PROCESSING.RECOVERABLE.NEW_STATE',
        TRUE = 'TRUE',

        // get paymentFrame Form
        paymentFrameForm = document.getElementById('paymentFrameForm'),
        paymentFrameFormState = document.getElementById('stateId'),
        paymentFrameFormElements = paymentFrameForm.elements,
        yearDropdown = document.getElementById('expirationYear'),
        monthDropdown = document.getElementById('expirationMonth'),
        verificationContainer = document.getElementById('accountVerificationContainer'),
        verification = document.getElementById('verification'),
        accountBrandElement = document.getElementById('brand'),
        accountNumberElement = document.getElementById('cardNumber'),

        nGWInputValidation = new NGWInputValidation(),
        wrongParamaters = [],
        missingParameters = [];

    // ### PostMessages listener and sender ###

    // A function to process post messages received by the window.
    function receiveMessage(e) {
        // Check to make sure that this message came from the correct domain.
        if (e.origin !== paymentFrame.getFrontendPaymentFrameOrigin()) {
            console.log("eorigin", e.origin);
            console.log("paymentFrameOrigin", paymentFrame.getFrontendPaymentFrameOrigin());
            console.error('Your FRONTEND.PAYMENT_FRAME_ORIGIN domain is not equal to your origin domain');
            return;
        }

        resetErrors();

        // check all parameters to show all errors and remember if any field is wrong
        var check = nGWInputValidation.checkCardNumber(wrongParamaters, missingParameters);
        check = nGWInputValidation.checkExpiryDate(wrongParamaters, missingParameters) && check;
        check = nGWInputValidation.checkCVV(wrongParamaters) && check;
        check = nGWInputValidation.checkCreditCardHolder(missingParameters) && check;

        // if the javascript validation is successful send the json to the ngw
        if (check) {
            // disable all elements inside the form
            for (var i = 0; i < paymentFrameFormElements.length; ++i) {
                paymentFrameFormElements[i].readOnly = true;
            }
            // parse json received to object
            var jsonObject = JSON.parse(e.data);
            // remove paymantframe parameter
            var paymentFrameFields = [ "account.holder", "account.number",
                "account.brand", "account.verification",
                "account.expiry_year", "account.expiry_month" ];

            paymentFrameFields.forEach(function (entry) {
                for (var prop in jsonObject) {
                    if (prop.toLowerCase() === entry ) {
                        delete jsonObject[prop];
                    };
                }
            });

            // add paymentFrame form parameter to jsonObject
            for (var i = 0; i < paymentFrameForm.length; ++i) {
                var input = paymentFrameForm[i];
                if (input.name) {
                    jsonObject[input.name] = input.value;
                }
            }
            e.preventDefault();
            // send jsonObject data to the hPP
            jsonAjaxCall(paymentFrame.getNgwPostUrl(), transactionPostCallback, JSON.stringify(jsonObject));
        } else {
            // send a nok response
            reviewWrongParameters(wrongParamaters);
            reviewWrongParameters(missingParameters);
            var response = {};
            response[PROCESSING_STATUS] = REJECTED_VALIDATION;
            response[PROCESSING_RESULT] = RESULT_NOK;
            response[POST_VALIDATION] = RESULT_NOK;
            response[MISSING_PARAMETERS] = missingParameters;
            response[WRONG_PARAMETERS] = wrongParamaters;
            response[PROCESSING_RECOVERABLE] = TRUE;
            sendPostMessageToShop(JSON.stringify(response));
        }
    }

    // callback for hpp ajax request
    function transactionPostCallback(response) {
        // Send a message back to the shop
        sendPostMessageToShop(response);

        var jsonResponse = JSON.parse(response);

        // if jsonResponse contains the key PROCESSING.WRONG.PARAMETERS something went wrong
        // show an error if a paymentFrame form element is wrong
        if (WRONG_PARAMETERS in jsonResponse) {
            reviewWrongParameters(jsonResponse[WRONG_PARAMETERS]);
        }

        // if jsonResponse contains the key PROCESSING.MISSING.PARAMETERS some parameters are missing
        // show an error if a paymentFrame form element is missing
        if (MISSING_PARAMETERS in jsonResponse) {
            reviewWrongParameters(jsonResponse[MISSING_PARAMETERS]);
        }

        // if jsonResponse contains the key PROCESSING.REDIRECT_URL a redirect is necessary
        if (REDIRECT_URL in jsonResponse) {
            redirectCustomer(jsonResponse);
        }

        // if the transaction is recoverable and there is a new state set new state to form
        if (PROCESSING_RECOVERABLE in jsonResponse
            && jsonResponse[PROCESSING_RECOVERABLE].toUpperCase() === TRUE
            && PROCESSING_RECOVERABLE_NEW_STATE in jsonResponse) {
            // clear elements
            if (accountNumberElement) {
                accountNumberElement.value = "";
            }
            if (verification) {
                verification.value = "";
            }
            if (yearDropdown) {
                yearDropdown.selectedIndex = 0;
            }
            if (monthDropdown) {
                monthDropdown.selectedIndex = 0;
            }
            // set new stateId in hidden form field
            paymentFrameFormState.value = jsonResponse[PROCESSING_RECOVERABLE_NEW_STATE];
        }

        // enable all elements inside the form
        for (var i = 0; i < paymentFrameFormElements.length; ++i) {
            paymentFrameFormElements[i].readOnly = false;
        }
    }

    // check if any wrong parameter is in the paymentframe form
    function reviewWrongParameters(wrongParamaters) {
        for (var i = 0; i < wrongParamaters.length; ++i) {
            for (var j = 0; j < paymentFrameFormElements.length; ++j) {
                if (paymentFrameFormElements[j].name === wrongParamaters[i]) {
                    showError(paymentFrameFormElements[j]);
                }
            }
        }
    }

    function redirectCustomer(jsonResponse) {
        var redirectMethod = 'get',
            params = addGetParameterToArray(jsonResponse[REDIRECT_URL]),
            redirectUrl,
            target;
        for (var key in jsonResponse) {
            // if key starts with
            if (key.indexOf(PROCESSING_PARAMS) === 0) {
                redirectMethod = 'post';
                params[key.substring(PROCESSING_PARAMS.length)] = jsonResponse[key];
            }
        }
        if (jsonResponse[REDIRECT_URL].indexOf('?') !== -1) {
            redirectUrl = jsonResponse[REDIRECT_URL].substring(0, jsonResponse[REDIRECT_URL].indexOf('?'));
        } else {
            redirectUrl = jsonResponse[REDIRECT_URL];
        }
        if (NEXT_TARGET in jsonResponse) {
            target = jsonResponse[NEXT_TARGET];
        }
        submitRedirectForm(redirectUrl, params, redirectMethod, target);
    }

    // ### Initialize ###

    // Setup an event listener that calls receiveMessage() when the window
    // receives a new MessageEvent.
    if (window.addEventListener)  // W3C DOM
        window.addEventListener('message', receiveMessage);
    else if (window.attachEvent) { // IE DOM
        window.attachEvent('onmessage', receiveMessage);
    }

    addYearsToDropdown(yearDropdown);

    hideCVVForMRCash();
    if (accountBrandElement.addEventListener)  // W3C DOM
        accountBrandElement.addEventListener('change', function () {
            hideCVVForMRCash();
        });
    else if (accountBrandElement.attachEvent) { // IE DOM
        accountBrandElement.attachEvent('onchange', function () {
            hideCVVForMRCash();
        });
    }

    // hide errors on input fields when they are about to change
    for (var i = 0; i < paymentFrameFormElements.length; ++i) {
        if (paymentFrameFormElements[i].addEventListener)  // W3C DOM
            paymentFrameFormElements[i].addEventListener('change', function () {
                hideError(this);
            });
        else if (paymentFrameFormElements[i].attachEvent) { // IE DOM
            paymentFrameFormElements[i].attachEvent('onchange', function (event) {
                if ( !event ) {
                    event = window.event;
                }
                hideError(event.target || event.srcElement);
            });
        }
    }

    // bind cardNumber input to change event for automatic brand selection
    if (accountNumberElement.addEventListener) {
        accountNumberElement.addEventListener("keyup", setBrandForCreditCard);
    } else {
        accountNumberElement.attachEvent("onkeyup", setBrandForCreditCard);
    }


    // ### Utils ###

    // call brand change
    function setBrandForCreditCard() {
        nGWInputValidation.setBrandForCreditCard();
    }

    // extract protocol, domain and port from url
    function getDomainFromUrl(url) {
        var arr = url.split('/');
        return arr[0] + '//' + arr[2];
    }


    // asynchronous ajax call
    function jsonAjaxCall(url, callback, json){
        var httpRequest;
        httpRequest = new XMLHttpRequest();
        httpRequest.onreadystatechange = function(){
            if (httpRequest.readyState === 4 && httpRequest.status === 200){
                callback(httpRequest.responseText);
            }
        }
        httpRequest.open('POST', url, true);
        httpRequest.setRequestHeader('Content-Type', 'application/json')
        httpRequest.send(json);
    }

    // submit a redirectForm
    function submitRedirectForm(path, params, method, target) {
        var form = document.createElement('form');
        form.setAttribute('method', method);
        form.setAttribute('action', path);
        if (target && target !== "") {
            form.setAttribute('target', target);
        } else {
            form.setAttribute('target', '_top');
        }

        for(var key in params) {
            if(params.hasOwnProperty(key)) {
                var hiddenField = document.createElement('input');
                hiddenField.setAttribute('type', 'hidden');
                hiddenField.setAttribute('name', key);
                hiddenField.setAttribute('value', params[key]);

                form.appendChild(hiddenField);
            }
        }

        document.body.appendChild(form);
        form.submit();
    }

    // populate year dropdown
    function addYearsToDropdown(dropdown) {
        var year = new Date().getFullYear();
        for (var i = 0; i< 14; i++){
            var thisYear = year + i;
            var option = document.createElement('option');
            option.setAttribute('value', thisYear);
            option.innerHTML = thisYear;
            dropdown.appendChild(option);
        }
    }

    // hide cvv for mister cash
    function hideCVVForMRCash() {
        if (accountBrandElement.value === 'MRCASH') {
            verificationContainer.style.display = "none";
        } else {
            verificationContainer.style.display = "block";
        }

    }

    // show an error on a paymentframe form element
    function showError(element) {
        element.className += ' error'
    }

    // hide an error on a paymentframe form element
    function hideError(element) {
        if (hasClass(element, 'error')) {
            var reg = new RegExp('(\\s|^)' + 'error' + '(\\s|$)');
            element.className=element.className.replace(reg,' ');
        }
    }

    function hasClass(ele, className) {
        return !!ele.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'));
    }

    // remove error classes from all inputs
    function resetErrors() {
        wrongParamaters = [];
        missingParameters = [];
        // remove all error classes
        for (var i = 0; i < paymentFrameFormElements.length; ++i) {
            hideError(paymentFrameFormElements[i]);
        }
    }

    function sendPostMessageToShop(response) {
        parent.postMessage(response, paymentFrame.getFrontendPaymentFrameOrigin());
    }

    function addGetParameterToArray(url) {
        var match,
            pl = /\+/g, // replace + with space
            search = /([^&=]+)=?([^&]*)/g,
            decode = function(s) {
                return decodeURIComponent(s.replace(pl, " "));
            },
            query,
            urlParams = {};

        if (url.indexOf('?') !== -1 ) {
            query = url.substring(url.indexOf('?') + 1);
            while (match = search.exec(query))
                urlParams[decode(match[1])] = decode(match[2]);
        }
        return urlParams;
    }

}());
