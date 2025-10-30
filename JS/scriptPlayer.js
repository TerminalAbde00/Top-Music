// Gestione form di segnalazione
$(document).ready(function(){
    $(".app-form-button").click(function(){
        $(".background").hide();
    });
    $("#show").click(function(){
        $(".background").show();
    });

    // Gestione submit form di segnalazione
    $("form").on("submit", function(e) {
        var dataString = $(this).serialize();
        
        $.ajax({
            type: "POST",
            url: "segnala.php",
            data: dataString,
            success: function () {
                alert('Grazie della segnalazione');
                $("#show").css("display", "none");
                $("#show").removeClass("segnala");
                $(".segnala").hide();
            }
        });
        
        e.preventDefault();
    });

    // Gestione player audio/video
    const audio = document.getElementById('myVideo');
    const progressBar = document.querySelector('.progress-bar');
    const now = document.querySelector('.now');

    if (audio && progressBar && now) {
        function conversion(value) {
            let minute = Math.floor(value / 60);
            minute = minute.toString().length === 1 ? ('0' + minute) : minute;
            let second = Math.round(value % 60);
            second = second.toString().length === 1 ? ('0' + second) : second;
            return `${minute}:${second}`;
        }

        progressBar.addEventListener('click', function (event) {
            let coordStart = this.getBoundingClientRect().left;
            let coordEnd = event.pageX;
            let p = (coordEnd - coordStart) / this.offsetWidth;
            now.style.width = p.toFixed(3) * 100 + '%';
            audio.currentTime = p * audio.duration;
        });

        setInterval(() => {
            if (audio.duration) {
                now.style.width = (audio.currentTime / audio.duration) * 100 + '%';
            }
        }, 1000);
    }

    // Funzione play/pause
    window.myFunction = function() {
        var video = document.getElementById("myVideo");
        var video2 = document.getElementById("myVideo2");
        var btn = document.getElementById("myBtn");
        
        if (video) {
            if (video2) {
                // Caso MP3 con video di background
                if (video.paused) {
                    video.play();
                    video2.play();
                    btn.innerHTML = "Pause";
                } else {
                    video.pause();
                    video2.pause();
                    btn.innerHTML = "Play";
                }
            } else {
                // Caso video normale
                if (video.paused) {
                    video.play();
                    btn.innerHTML = "Pause";
                } else {
                    video.pause();
                    btn.innerHTML = "Play";
                }
            }
        }
    };
});

// Gestione UI per video MP4
var timedelay = 1;
var _delay;

function delayCheck() {
    // Verifichiamo se è un video MP4 (no #myVideo2 = non è MP3)
    if (!document.getElementById('myVideo2')) {
        if (timedelay == 5) {
            // Nascondi tutti gli elementi con animazione
            $('.box, .progress, .segnala, .back').css({
                'opacity': '0',
                'transition': 'opacity 0.3s ease-in-out'
            }).one('transitionend', function() {
                $(this).css('display', 'none');
            });
            
            $('#myVideo').css({
                "filter": "none",
                "transform": "scale(1)",
                "transition": "all 0.3s ease-in-out"
            });
            timedelay = 1;
        }
        timedelay = timedelay + 1;
    }
}

$(document).mousemove(function() {
    // Verifichiamo se è un video MP4
    if (!document.getElementById('myVideo2')) {
        // Mostra tutti gli elementi con animazione
        $('.progress, .segnala, .box, .back').css({
            'opacity': '1',
            'transition': 'opacity 0.3s ease-in-out',
            'display': 'block'
        });
        $('#myVideo').css({
            "filter": "blur(4px)",
            "transform": "scale(2)",
            "transition": "all 0.3s ease-in-out"
        });
        
        timedelay = 1;
        clearInterval(_delay);
        _delay = setInterval(delayCheck, 700);
    }
});

// Avvia delay timer al caricamento pagina solo se è un video MP4
$(document).ready(function() {
    if (!document.getElementById('myVideo2')) {
        _delay = setInterval(delayCheck, 800);
    }
});
