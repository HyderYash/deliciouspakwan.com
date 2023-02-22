import React, { Component } from "react";
import "../assets/css/Loader.css";

class Loader extends Component {
  render(props) {
    return (
      <>
        <div className="js">
          <div id="preloader"></div>
        </div>
      </>
    );
  }
}
export default Loader;
