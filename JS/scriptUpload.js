'use strict';

// Funzione di conversione gradi in radianti
let toRadians = deg => deg * Math.PI / 180;

// Funzione di mappatura valori
let map = (val, a1, a2, b1, b2) => b1 + (val - a1) * (b2 - b1) / (a2 - a1);

// Classe Pizza per l'animazione
class Pizza {
    constructor(id) {
        this.canvas = document.getElementById(id);
        if (!this.canvas) return;
        
        this.ctx = this.canvas.getContext('2d');
        this.sliceCount = 6;
        this.sliceSize = 80;
        this.width = this.height = this.canvas.height = this.canvas.width = this.sliceSize * 2 + 50;
        this.center = this.height / 2 | 0;
        this.sliceDegree = 360 / this.sliceCount;
        this.sliceRadians = toRadians(this.sliceDegree);
        this.progress = 0;
        this.cooldown = 10;
    }

    update() {
        let ctx = this.ctx;
        ctx.clearRect(0, 0, this.width, this.height);

        if (--this.cooldown < 0) this.progress += this.sliceRadians * 0.01 + this.progress * 0.07;

        ctx.save();
        ctx.translate(this.center, this.center);

        for (let i = this.sliceCount - 1; i > 0; i--) {
            let rad;
            if (i === this.sliceCount - 1) {
                let ii = this.sliceCount - 1;
                rad = this.sliceRadians * i + this.progress;

                ctx.strokeStyle = '#FBC02D';
                cheese(ctx, rad, .9, ii, this.sliceSize, this.sliceDegree);
                cheese(ctx, rad, .6, ii, this.sliceSize, this.sliceDegree);
                cheese(ctx, rad, .5, ii, this.sliceSize, this.sliceDegree);
                cheese(ctx, rad, .3, ii, this.sliceSize, this.sliceDegree);
            } else rad = this.sliceRadians * i;

            ctx.beginPath();
            ctx.lineCap = 'butt';
            ctx.lineWidth = 11;
            ctx.arc(0, 0, this.sliceSize, rad, rad + this.sliceRadians);
            ctx.strokeStyle = '#F57F17';
            ctx.stroke();

            let startX = this.sliceSize * Math.cos(rad);
            let startY = this.sliceSize * Math.sin(rad);
            let endX = this.sliceSize * Math.cos(rad + this.sliceRadians);
            let endY = this.sliceSize * Math.sin(rad + this.sliceRadians);
            ctx.fillStyle = '#FBC02D';
            ctx.beginPath();
            ctx.moveTo(0, 0);
            ctx.lineTo(startX, startY);
            ctx.arc(0, 0, this.sliceSize, rad, rad + this.sliceRadians);
            ctx.lineTo(0, 0);
            ctx.closePath();
            ctx.fill();
            ctx.lineWidth = .3;
            ctx.stroke();

            let x = this.sliceSize * .65 * Math.cos(rad + this.sliceRadians / 2);
            let y = this.sliceSize * .65 * Math.sin(rad + this.sliceRadians / 2);
            ctx.beginPath();
            ctx.arc(x, y, this.sliceDegree / 6, 0, 2 * Math.PI);
            ctx.fillStyle = '#D84315';
            ctx.fill();
        }

        ctx.restore();

        if (this.progress > this.sliceRadians) {
            ctx.translate(this.center, this.center);
            ctx.rotate(-this.sliceDegree * Math.PI / 180);
            ctx.translate(-this.center, -this.center);
            this.progress = 0;
            this.cooldown = 20;
        }
    }
}

// Funzione cheese per le linee animate
function cheese(ctx, rad, multi, ii, sliceSize, sliceDegree) {
    let x1 = sliceSize * multi * Math.cos(toRadians(ii * sliceDegree) - .2);
    let y1 = sliceSize * multi * Math.sin(toRadians(ii * sliceDegree) - .2);
    let x2 = sliceSize * multi * Math.cos(rad + .2);
    let y2 = sliceSize * multi * Math.sin(rad + .2);

    let csx = sliceSize * Math.cos(rad);
    let csy = sliceSize * Math.sin(rad);

    var d = Math.sqrt((x1 - csx) * (x1 - csx) + (y1 - csy) * (y1 - csy));
    ctx.beginPath();
    ctx.lineCap = 'round';

    let percentage = map(d, 15, 70, 1.2, 0.2);

    let tx = x1 + (x2 - x1) * percentage;
    let ty = y1 + (y2 - y1) * percentage;
    ctx.moveTo(x1, y1);
    ctx.lineTo(tx, ty);

    tx = x2 + (x1 - x2) * percentage;
    ty = y2 + (y1 - y2) * percentage;
    ctx.moveTo(x2, y2);
    ctx.lineTo(tx, ty);

    ctx.lineWidth = map(d, 0, 100, 20, 2);
    ctx.stroke();
}

// Crea e avvia animazione pizza
let pizza = new Pizza('pizza');

(function update() {
    requestAnimationFrame(update);
    if (pizza && pizza.canvas) {
        pizza.update();
    }
})();

// Funzione per nascondere form e mostrare loader
function hide() {
    var x = document.getElementById("mandadati");
    var y = document.getElementById("loading");
    if (x && y) {
        y.style.display = "block";
        x.style.display = "none";
    }
}

// Gestione input file (per mostrare "Cover Caricata" o "Video/Audio Caricato")
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = document.querySelectorAll('input[type="file"]');
    
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function() {
            const fileDummy = this.closest('.file-area').querySelector('.file-dummy');
            if (this.files.length > 0) {
                fileDummy.querySelector('.success').style.display = 'block';
                fileDummy.querySelector('.default').style.display = 'none';
            } else {
                fileDummy.querySelector('.success').style.display = 'none';
                fileDummy.querySelector('.default').style.display = 'block';
            }
        });
    });
});
