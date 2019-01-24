var paymentFrame = {
    getFrontendPaymentFrameOrigin: function () {
        return document.getElementById("paymentFrameConf").getAttribute("frontend-payment-frame-origin");
    },
    getNgwPostUrl: function () {
        return document.getElementById("paymentFrameConf").getAttribute("payment-frame-url");
    },
    explainVerification: function () {
        var screenWidth = screen.availWidth;
        var screenHeight = screen.availHeight;

        var leftPos = (screenWidth - 500) / 2;
        var topPos = (screenHeight - 600) / 2;

        var parstring = "status=no,width=500,height=600,top=" + topPos + ",left=" + leftPos + ",hotkeys=no,resizable=yes,scrollbars=false";

        var explainVerificationPopupWindow = window.open("explainCCV?lang=" + document.getElementById("paymentFrameConf").getAttribute("lang"), 'POPUP', parstring);
    }
};
