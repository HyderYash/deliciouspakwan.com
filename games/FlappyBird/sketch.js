var bird;
var pipes = [];
function setup () {
    createCanvas(1000, 699);
    bird = new Bird();
    pipes.push(new Pipe());
}
function draw () {
    background(0);
    for(var i = pipes.length - 1; i >= 0; i-- ){
        pipes[i].show();
        pipes[i].update();
        if(pipes[i].hits(bird)){
            console.log("HIT");
        }
        if(pipes[i].offScreen()){
            pipes.splice(i, 1);
        }
    }
    bird.update();
    bird.show();
    if(frameCount % 100 == 0) {
        pipes.push(new Pipe());  
    }
}
function keyPressed () {
    if (key == ' ', 'Click'){
        bird.up();
        //console.log('Click');
    }
}