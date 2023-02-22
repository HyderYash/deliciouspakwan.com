import React, { Component } from "react";
import { Alert, Container } from "react-bootstrap";

export default class LastUpdated extends Component {
  render(props) {
    return (
      <Container>
        <Alert
          className="mt-3"
          style={{
            width: "auto",
            backgroundColor: "#FFBAD2",
          }}
        >
          <Alert.Heading className="h5" style={{ fontWeight: "300" }}>
            {`You have Updated this Page on ${new Date(
              this.props.time
            ).toDateString()} ${new Date(
              this.props.time
            ).toLocaleTimeString()}`}
          </Alert.Heading>
        </Alert>
      </Container>
    );
  }
}
