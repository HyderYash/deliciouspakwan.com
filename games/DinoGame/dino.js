class Dino {
    constructor() {
        this.r = 100;
        this.x = this.r;
        this.y = height - this.r;
        this.vy = 0;
        this.gravity = 2;
    }
    jump () {
        if(this.y == height - this.r) {
            this.vy = -35;
        }
    }
    hits (train) {
        return collideRectRect(this.x,this.y,this.r,this.r, train.x,train.y,train.r,train.r);
}
    move () {
        this.y += this.vy;
        this.vy += this.gravity;
        this.y = constrain(this.y, 0, height - this.r)
    }
    show () {
        image(dImg,this.x, this.y, this.r, this.r);
        //fill(255, 50);
       // rect(this.x, this.y, this.r, this.r);
    }
}