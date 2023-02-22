import React, { Component } from "react";
import { Badge } from "react-bootstrap";

export default class Footer extends Component {
  render() {
    return (
      <footer className="footer font-small blue">
        <div className="footer-copyright text-center py-3">
          Copyright © {new Date().getFullYear()}
          <a href="/"> albums.deliciouspakwan.com</a>
          <Badge pill className="text-white ml-2 badgeCss">
            Visit Counter: {this.props.albumVisitCounter}
          </Badge>
        </div>
      </footer>
    );
  }
}
