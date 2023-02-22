import React, { Component } from "react";
import { Button, Form, Modal } from "react-bootstrap";
import HomePageLayout from "../components/HomePageLayout";
import "../assets/css/HomeModal.css";
import { fetchAPIData } from "../utility/Common";

export default class Home extends Component {
  constructor() {
    super();
    this.state = {
      showModal: false,
      adminKey: "",
    };
    this.closeModal = this.closeModal.bind(this);
  }
  closeModal() {
    this.setState({ showModal: false });
  }
  componentDidMount() {
    if (sessionStorage.getItem("adminKeyEntered") === null) {
      this.setState({ showModal: true });
    }
  }
  handleSubmit(e) {
    e.preventDefault();
    const formData = {
      ADMIN_KEY: this.state.adminKey,
    };
    fetchAPIData("/api/albums/check_home_page_accesskey.php", formData, "POST")
      .then((json) => {
        if (json.status === "Success") {
          sessionStorage.setItem("adminKeyEntered", true);
          this.setState({ showModal: false });
        } else {
          sessionStorage.setItem("adminKeyEntered", false);
          alert("Invalid Admin Key");
        }
      })
      .catch((err) => {
        console.log(err);
      });
  }
  render() {
    return (
      <>
        <Modal
          show={this.state.showModal}
          onHide={this.closeModal}
          backdrop="static"
          keyboard={false}
          centered
        >
          <Modal.Header>
            <Modal.Title>Enter Access Key</Modal.Title>
          </Modal.Header>
          <Form onSubmit={this.handleSubmit.bind(this)}>
            <Modal.Body>
              <Form.Control
                type="password"
                placeholder="Enter Access Key"
                onChange={(e) => this.setState({ adminKey: e.target.value })}
                maxLength="4"
              />
            </Modal.Body>
            <Modal.Footer>
              <Button variant="primary" type="submit">
                Submit
              </Button>
            </Modal.Footer>
          </Form>
        </Modal>
        <HomePageLayout />
      </>
    );
  }
}
