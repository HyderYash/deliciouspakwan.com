import React, { Component } from "react";
import { Button, Col, Container, Row } from "react-bootstrap";
import { Link } from "react-router-dom";

export default class MobileHeader extends Component {
  render() {
    return (
      <Container fluid>
        <Row style={{ height: "30px" }}>
          <Col xs={12} md={12} lg={12} style={{ paddingLeft: "0px" }}>
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
        </Row>
        <Row style={{ height: "30px" }}>
          <Col xs={12} md={12} lg={12} style={{ paddingLeft: "5px" }}>
            <Link to="/">
              <Button
                className="text-left"
                size="sm"
                style={{ height: "28px", lineheight: "10px" }}
              >
                Back To Albums
              </Button>
            </Link>
          </Col>
        </Row>
      </Container>
    );
  }
}
