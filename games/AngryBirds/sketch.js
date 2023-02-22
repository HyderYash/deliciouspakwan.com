const { Engine, World, Bodies, Mouse, MouseConstraint, Constraint } = Matter;

let ground;
const boxes = [];
let bird;
let world, engine;
let mConstraint;
let slingshot;

let dotImg;
let boxImg;
let bkgImg;

function preload() {
  dotImg = loadImage('images/angry.png');
  boxImg = loadImage('images/equals.png');
  bkgImg = loadImage('images/background.png');
}
function setup() {
  const canvas = createCanvas(1500, 500);
  engine = Engine.create();
  world = engine.world;
  ground = new Ground(width / 2, height - 10, width, 20);
  for (let i = 0; i < 5; i++) {
    boxes[i] = new Box(1300, 300 - i * 75, 84, 100);
  }
  bird = new Bird(350, 300, 40);

  slingshot = new SlingShot(350, 300, bird.body);

  const mouse = Mouse.create(canvas.elt);
  const options = {
    mouse: mouse
  };

  mouse.pixelRatio = pixelDensity();
  mConstraint = MouseConstraint.create(engine, options);
  World.add(world, mConstraint);
}

function keyPressed() {
  if (key == ' ') {
    World.remove(world, bird.body);
    bird = new Bird(350, 300, 40);
    slingshot.attach(bird.body);
    
  }
}
function keyTyped() {
  if (key === 'b') {
    for (let i = 0; i < 5; i++) {
      boxes[i] = new Box(1300, 300 - i * 75, 84, 100);
    }
  } return true;
}
function mouseReleased() {
  setTimeout(() => {
    slingshot.fly();
  }, 100);
}

function draw() {
  background(bkgImg);
  Matter.Engine.update(engine);
  ground.show();
  for (let box of boxes) {
    box.show();
  }
  slingshot.show();
  bird.show();
}