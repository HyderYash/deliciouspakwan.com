import React, { Component } from "react";
import { Container } from "react-bootstrap";

export default class NoPhotoVideoInCarousel extends Component {
  render() {
    return (
      <>
        <Container
          fluid
          style={{
            padding: "5px",
            backgroundColor: "azure",
            height: "200px",
            display: "flex",
            justifyContent: "center",
            alignItems: "center",
            textAlign: "center",
          }}
        >
          <h2>
            No {this.props.type === "video" ? "Videos 🎥" : "Photos 📷"} in this
            album...
          </h2>
        </Container>
      </>
    );
  }
}
