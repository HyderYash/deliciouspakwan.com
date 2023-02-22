import React from "react";
import { Card, Col, Container, FormControl, Row } from "react-bootstrap";
import { clientId } from "./Common";

export default function GoogleAdmin() {
  return (
    <div>
      <Card>
        <Card.Body>
          <Container fluid>
            <Row>
              <Col sm={12} md={4} lg={4}>
                <Card.Img
                  variant="top"
                  className="mt-2 mb-2"
                  style={{
                    width: "200px",
                    height: "200px",
                    pointerEvents: "none",
                    borderRadius: "100%",
                  }}
                  src={sessionStorage.getItem("GoogleUserImage")}
                />
              </Col>
              <Col sm={12} md={8} lg={8}>
                <h1
                  style={{
                    textAlign: "left",
                    textTransform: "capitalize",
                  }}
                  className="mt-3"
                >
                  {sessionStorage.getItem("GoogleUserName")}
                </h1>
                <p style={{ textAlign: "left", fontSize: "15px" }}>
                  <a href="mailto:yashsharma.karate@gmail.com">
                    {sessionStorage.getItem("GoogleUserEmail")}
                  </a>{" "}
                  - Administrator
                </p>
              </Col>
            </Row>
          </Container>

          <Container fluid style={{ borderBottom: "1px solid #F2E3E3" }}>
            <Row>
              <Col sm={12} md={4} lg={4}>
                <h3
                  style={{ textAlign: "left", textTransform: "capitalize" }}
                  className="mt-3"
                >
                  Account
                </h3>
              </Col>
              <Col sm={12} md={8} lg={8}></Col>
            </Row>
          </Container>
          <Container fluid style={{ borderBottom: "1px solid #F2E3E3" }}>
            <Row>
              <Col sm={12} md={4} lg={4}>
                <h3
                  style={{
                    textAlign: "left",
                    textTransform: "capitalize",
                    fontSize: "18px",
                    marginTop: "25px",
                  }}
                >
                  Username
                </h3>
              </Col>
              <Col sm={12} md={8} lg={8}>
                {" "}
                <FormControl
                  placeholder="Username"
                  aria-label="Username"
                  value={sessionStorage.getItem("GoogleUserName")}
                  type="text"
                  required
                  disabled
                  className="mt-3 mb-3"
                />
              </Col>
            </Row>
          </Container>

          <Container fluid style={{ borderBottom: "1px solid #F2E3E3" }}>
            <Row>
              <Col sm={12} md={4} lg={4}>
                <h3
                  style={{
                    textAlign: "left",
                    textTransform: "capitalize",
                    fontSize: "18px",
                    marginTop: "25px",
                  }}
                >
                  Email
                </h3>
              </Col>
              <Col sm={12} md={8} lg={8}>
                {" "}
                <FormControl
                  placeholder="Email"
                  aria-label="Email"
                  className="mt-3 mb-3"
                  value={sessionStorage.getItem("GoogleUserEmail")}
                  type="email"
                  required
                  disabled
                />
              </Col>
            </Row>
          </Container>

          <Container fluid style={{ borderBottom: "1px solid #F2E3E3" }}>
            <Row>
              <Col sm={12} md={4} lg={4}>
                <h3
                  style={{
                    textAlign: "left",
                    textTransform: "capitalize",
                    fontSize: "18px",
                    marginTop: "25px",
                  }}
                >
                  Password
                </h3>
              </Col>
              <Col sm={12} md={8} lg={8}>
                {" "}
                <FormControl
                  placeholder="Password"
                  aria-label="Password"
                  className="mt-3 mb-3"
                  value={clientId}
                  type="password"
                  disabled
                />
              </Col>
            </Row>
          </Container>
          <Container fluid style={{ borderBottom: "1px solid #F2E3E3" }}>
            <Row>
              <Col sm={12} md={4} lg={4}>
                <h3
                  style={{
                    textAlign: "left",
                    textTransform: "capitalize",
                    fontSize: "18px",
                    marginTop: "25px",
                  }}
                >
                  Title
                </h3>
              </Col>
              <Col sm={12} md={8} lg={8}>
                {" "}
                <FormControl
                  placeholder="Title"
                  aria-label="Title"
                  className="mt-3 mb-3"
                  value="Administrator"
                  disabled
                />
              </Col>
            </Row>
          </Container>
        </Card.Body>
      </Card>
    </div>
  );
}
