class Train {
    constructor() {
        this.r = 70,
        this.x = width - this.r; 
        this.y = height - this.r;
    }
    move () {
        this.x -= 6;
    }
    show () {
        image(cImg, this.x, this.y, this.r, this.r);
        //fill(255, 50);
        //rect(this.x, this.y, this.r, this.r);

    }
}