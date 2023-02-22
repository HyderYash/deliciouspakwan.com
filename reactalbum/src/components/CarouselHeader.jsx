import React, { Component } from "react";
import { Button, Col, Container, Row } from "react-bootstrap";
import { Link } from "react-router-dom";

export default class CarouselHeader extends Component {
  render() {
    return (
      <Container fluid>
        <Row style={{ height: "30px", marginTop: "2px" }}>
          <Col xs={6} md={10} lg={10}>
            <h3
              className="text-left"
              style={{
                margin: "5px",
                fontSize: "16px",
                fontWeight: "bold",
                display: "flex",
              }}
            >
              {this.props.albumDisplayTitle} |&nbsp;
              <strong style={{ color: "navy" }}>
                {this.props.visitCounter} views
              </strong>
            </h3>
          </Col>
          <Col xs={6} md={2} lg={2}>
            <Link to="/">
              <Button className="text-left" size="sm">
                Back To Albums
              </Button>
            </Link>
          </Col>
        </Row>
      </Container>
    );
  }
}
