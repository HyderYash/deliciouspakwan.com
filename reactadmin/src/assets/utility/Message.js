import React, { Component } from "react";
import { Alert } from "react-bootstrap";

class Message extends Component {
  render(props) {
    return (
      <Alert
        variant={this.props.color}
        style={{
          color: "black",
          fontWeight: "bold",
        }}
      >
        {this.props.message}
      </Alert>
    );
  }
}
export default Message;
