import React from "react";
import "../assets/css/NotFound.css";

const NotFound = () => {
  return (
    <div>
      <section className="notFoundSection">
        <div className="container1">
          <h1 className="h1" id="firstH1">
            404
          </h1>
        </div>
        <div className="container">
          <h1 style={{ textAlign: "center" }}>Page not Found</h1>
        </div>
        <div
          style={{
            margin: "0 auto",
            display: "flex",
            justifyContent: "center",
          }}
        >
          <a href="/">
            <button className="notFoundButton" style={{ color: "black" }}>
              Return To Album Home
            </button>
          </a>
        </div>
      </section>
    </div>
  );
};

export default NotFound;
