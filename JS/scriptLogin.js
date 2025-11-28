// Toggle tra form login e registrazione
$(document).ready(function() {
    $('.message a').click(function() {
        $('form').animate({ height: "toggle", opacity: "toggle" }, "slow");
    });
});

// Nascondi messaggi di errore dopo 5 secondi
var timedelay = 1;
function delayCheck() {
    if (timedelay == 5) {
        $('.non').fadeOut();
        timedelay = 1;
    }
    timedelay = timedelay + 1;
}

_delay = setInterval(delayCheck, 800);
