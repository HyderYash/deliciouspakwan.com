function Cell (i, j, w) {
    this.i = i;
    this.j = j;
    this.x = i * w;
    this.y = j * w;
    this.w = w;
    this.neighbourCount = 0; 
    if(random(1) < 0.5) {
        this.bee = true;
    }else {
        this.bee = false;
    }
    this.revealed = false;
}
Cell.prototype.show = function () {
    stroke(0);
    noFill(255);
    rect(this.x, this.y, this.w, this.w)
    if(this.revealed) {
        if(this.bee) {
            fill(127);
            ellipse(this.x + this.w * 0.5, this.y + this.w * 0.5, this.w * 0.5);
        }else {
            fill(200);
            rect(this.x, this.y, this.w, this.w);
            textAlign(CENTER);
            fill(0);
            text(this.neighbourCount, this.x + this.w * 0.5, this.y + this.w - 10);
        }
    }
}
Cell.prototype.contBees = function () {
    if(this.bee) {
        return -1;
    }
    var total = 0;
    for(var i = -1; i <= 1; i++) {
        for(var j = -1; j <= 1; j++) {
            var neighbor = grid[this.i + 1][this.j + j];
            if(neighbor.bee) {
                total++;
            }
        }
    }
    this.neighbourCount = total;
}
Cell.prototype.contains = function (x, y) {
    return (x > this.x && x < this.x + this.w && y > this.y && y < this.y + this.w);
}
Cell.prototype.reveal = function () {
    this.revealed = true;
}