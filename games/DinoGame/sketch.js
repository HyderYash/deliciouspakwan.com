let dino;
let dImg;
let cImg;
let bImg;
let trains = [];


function preload () {
    dImg = loadImage('char.png');
    cImg = loadImage('ob.png');
    bImg = loadImage('back.jpg');
}
function mousePressed () {
    trains.push(new Train());
}
function setup () {
    createCanvas(1500, 450);
    dino = new Dino();
}

function keyPressed () {
    if (key == ' ') {
        dino.jump();
    }
}
function draw () {
    if(random(4) < 0.000001) {
        trains.push(new Train());
    }
    console.log(trains);
    background(bImg);
    dino.show();
    dino.move();
    for(let t of trains) {
        t.move();
        t.show();
        if(dino.hits(t)) {
            console.log('Game Over');
            noLoop();
        }
    }
}