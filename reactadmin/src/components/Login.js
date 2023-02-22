import React, { Component } from "react";
import {
  Button,
  ButtonGroup,
  Card,
  Container,
  Form,
  Modal,
} from "react-bootstrap";
import Logo from "../assets/images/logo.svg";
import { fetchAPIData } from "./Common";
import Spinner from "../assets/utility/Loader";
const crypto = require("crypto");

class Login extends Component {
  constructor() {
    super();
    this.login = this.login.bind(this);
    this.checkOTP = this.checkOTP.bind(this);
    this.state = {
      USER_NAME: null,
      USER_PASS: null,
      USER_OTP: null,
      show: false,
      showPassModal: false,
      showPassWarnModal: false,
      APIReturnMessage: "",
      loading: null,
    };
  }
  setEncryptedPass(rawPass) {
    const hashPwd = crypto.createHash("sha1").update(rawPass).digest("hex");
    return hashPwd;
  }
  login() {
    this.setState({ loading: true });
    sessionStorage.setItem("CURRENT_CLICKED_PAGE_ID", 1);
    fetchAPIData("/api/login/check_login.php", this.state, "POST").then(
      (json) => {
        sessionStorage.setItem("AdminUserName", this.state.USER_NAME);
        sessionStorage.setItem(
          "login",
          this.state.USER_NAME && this.state.USER_PASS
        );
        this.setState({ APIReturnStatus: json.status });
        this.setState({ APIReturnMessage: json.message });
        if (json.status === "Success") {
          this.handlePassModal();
          fetchAPIData("/api/menuitems/menu_items.php", "", "GET").then(
            (resp) => {
              sessionStorage.setItem(
                "menuRouteLinkDetails",
                JSON.stringify(resp)
              );
            }
          );
        } else {
          this.handleModal();
        }
      }
    );
  }
  checkOTP(e) {
    e.preventDefault();
    this.closePassModal();
    this.OTPKeyVal = {
      USER_NAME: this.state.USER_NAME,
      USER_OTP: this.state.USER_OTP,
    };
    fetchAPIData("/api/login/check_login_otp.php", this.OTPKeyVal, "POST").then(
      (json) => {
        this.setState({
          loading: false,
        });
        this.setState({ APIReturnMessage: json.message });
        if (json.status === "Success") {
          this.props.history.push("displaysettings");
        } else {
          this.handlePassWarnModal();
        }
      }
    );
  }

  handleModal() {
    this.setState({ show: !this.state.show });
  }
  closeFirstModal() {
    this.setState({ show: false });
  }
  handlePassModal() {
    this.setState({ showPassModal: !this.state.showPassModal });
  }
  handlePassWarnModal() {
    this.setState({ showPassWarnModal: !this.state.showPassWarnModal });
  }
  closePassWarnModal() {
    this.setState({ showPassWarnModal: false });
  }
  closePassModal() {
    this.setState({ showPassModal: false });
  }

  render() {
    return (
      <div>
        <Modal
          show={this.state.show}
          onHide={() => {
            this.handlePassModal();
          }}
        >
          <Modal.Header closeButton>
            <h4>{this.state.APIReturnStatus}</h4>
          </Modal.Header>
          <Modal.Body>
            <h6>{this.state.APIReturnMessage}</h6>
          </Modal.Body>
          <Modal.Footer>
            <Button
              variant="success"
              onClick={() => {
                this.closeFirstModal();
              }}
            >
              OK
            </Button>
          </Modal.Footer>
        </Modal>
        <Modal
          show={this.state.showPassModal}
          backdrop="static"
          onHide={() => {
            this.handlePassModal();
          }}
        >
          <Form onSubmit={this.checkOTP}>
            <Modal.Header>
              <h4>Enter your OTP</h4>
            </Modal.Header>
            <Modal.Body>
              <p style={{ color: "green" }}>
                We have sent an <b>OTP</b> to your registered Email Address
              </p>
              <Form.Group>
                <Form.Control
                  placeholder="Enter OTP"
                  type="password"
                  required
                  onChange={(event) => {
                    this.setState({ USER_OTP: event.target.value });
                  }}
                  autoComplete="off"
                  className="mb-3 mt-3"
                />
              </Form.Group>
            </Modal.Body>
            <Modal.Footer>
              <Button variant="success" type="submit">
                OK
              </Button>
            </Modal.Footer>
          </Form>
        </Modal>

        <Modal
          show={this.state.showPassWarnModal}
          backdrop="static"
          onHide={() => {
            this.handlePassWarnModal();
          }}
        >
          <Modal.Header closeButton>
            <h4>{this.state.APIReturnMessage}</h4>
          </Modal.Header>
          <Modal.Body>
            <p style={{ color: "red" }}>
              <b>
                The OTP you have typed in is incorrect.. Click on OK to enter
                OTP again...
              </b>
            </p>
          </Modal.Body>
          <Modal.Footer>
            <Button
              variant="success"
              onClick={() => {
                this.closePassWarnModal();
                this.handlePassModal();
              }}
            >
              OK
            </Button>
          </Modal.Footer>
        </Modal>
        {this.state.loading ? (
          <Spinner />
        ) : (
          <Container className="mt-5">
            <Card
              className="mx-auto my-auto"
              style={{ width: "auto", background: "#3e8ef7" }}
            >
              <Form onSubmit={this.login}>
                <Card.Body>
                  <Card.Title>
                    <center>
                      <img alt="logo" src={Logo} className="mb-3 App-logo" />
                    </center>
                    <h3 className="text-white">Admin Panel Login</h3>
                  </Card.Title>
                  <Form.Group>
                    <Form.Control
                      type="text"
                      id="username"
                      placeholder="Enter Name"
                      autoComplete="off"
                      onChange={(event) =>
                        this.setState({ USER_NAME: event.target.value })
                      }
                      className="mb-3 mt-3"
                      required
                    />
                    <div style={{ display: "flex" }}>
                      <Form.Control
                        placeholder="Enter Password"
                        type="password"
                        name="USER_PASS"
                        required
                        id="password"
                        onChange={(event) =>
                          this.setState({
                            USER_PASS: this.setEncryptedPass(
                              event.target.value
                            ),
                          })
                        }
                        autoComplete="off"
                        className="mb-3 mt-3"
                      />
                    </div>
                  </Form.Group>
                </Card.Body>
                <Card.Footer style={{ display: "flex" }}>
                  <ButtonGroup className="mx-auto">
                    <Button
                      type="submit"
                      className="cursor"
                      style={{ background: "#05A85C" }}
                    >
                      Login to Admin Panel
                    </Button>
                  </ButtonGroup>
                </Card.Footer>
              </Form>
            </Card>
          </Container>
        )}
      </div>
    );
  }
}

export default Login;
