import React, { useState } from "react";
import {
  Button,
  Card,
  Col,
  Container,
  Form,
  FormControl,
  Row,
} from "react-bootstrap";
import "../assets/css/AdminProfile.css";
import { API_ROOT_PATH } from "./Common";
import Dropzone from "react-dropzone";

function Admin() {
  const [fileNames, setFileNames] = useState([]);
  const [userName, setUserName] = useState(
    sessionStorage.getItem("AdminUserName")
  );
  const [email, setEmail] = useState("yashsharma.karate@gmail.com");
  const [pass, setPass] = useState(sessionStorage.getItem("login"));
  const handleDrop = (acceptedFiles) => {
    setFileNames(acceptedFiles.map((file) => file.name));
    console.log(fileNames);
  };
  const handleSubmit = (e) => {
    e.preventDefault();
    // const formData = {
    //   userName: userName,
    //   email: email,
    // };
  };
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
                  src={`${API_ROOT_PATH}/images/img_avatar.png`}
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
                  {sessionStorage.getItem("AdminUserName")}
                </h1>
                <p style={{ textAlign: "left", fontSize: "15px" }}>
                  <a href="mailto:yashsharma.karate@gmail.com">
                    yashsharma.karate@gmail.com
                  </a>{" "}
                  - Administrator
                </p>
                <Dropzone onDrop={handleDrop} multiple={false}>
                  {({ getRootProps, getInputProps }) => (
                    <div {...getRootProps({ className: "dropzone" })}>
                      <input {...getInputProps()} />
                      <p>Drag'n'drop file to change profile photo</p>
                    </div>
                  )}
                </Dropzone>
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
          <Form onSubmit={handleSubmit}>
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
                    value={userName}
                    type="text"
                    required
                    onChange={(e) => setUserName(e.target.value)}
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
                    value={email}
                    type="email"
                    required
                    onChange={(e) => setEmail(e.target.value)}
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
                    type="password"
                    required
                    value={pass}
                    onChange={(e) => setPass(e.target.value)}
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
                    Title
                  </h3>
                </Col>
                <Col sm={12} md={8} lg={8}>
                  {" "}
                  <FormControl
                    placeholder="Title"
                    aria-label="Title"
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
                    Enable 2 Factor Authentication
                  </h3>
                </Col>
                <Col sm={12} md={8} lg={8}>
                  {" "}
                  <div className="mt-3 mb-3" style={{ float: "left" }}>
                    <label className="switch">
                      <input type="checkbox" />
                      <span className="slider round"></span>
                    </label>
                  </div>
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
                    Submit Credentials
                  </h3>
                </Col>
                <Col sm={12} md={8} lg={8}>
                  {" "}
                  <Button
                    type="submit"
                    className="mt-3 mb-3"
                    style={{ float: "left" }}
                  >
                    Submit
                  </Button>
                </Col>
              </Row>
            </Container>
          </Form>
        </Card.Body>
      </Card>
    </div>
  );
}

export default Admin;
