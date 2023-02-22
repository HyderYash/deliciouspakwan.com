import React, { Component } from "react";
import NavBarMenu from "./NavBarMenu";
import { Button, Card, Container, Form, Modal } from "react-bootstrap";
import { fetchAPIData, isAuthenticated } from "./Common";
import { Redirect } from "react-router-dom";
import Message from "../assets/utility/Message";
import LastUpdated from "../assets/utility/LastUpdated";
import Loader from "../assets/utility/Loader";

class UpdateVideo extends Component {
  constructor() {
    super();
    this.state = {
      URL: null,
      show: false,
      secondShow: false,
      URLModel: false,
      APIReturnMessage: "",
      APIReturnStatus: "",
      PageVistTime: JSON.parse(sessionStorage.getItem("menuRouteLinkDetails"))
        .records[sessionStorage.getItem("CURRENT_CLICKED_PAGE_ID")]
        .LIST_ITEM_LAST_UPDATED,
      loading: null,
    };
    // console.table(JSON.parse(sessionStorage.getItem("menuItems")).records);
  }

  API_Update_Video() {
    if (document.getElementById("UpdateVideo").value === "") {
      alert("TAKING YOU TO LIST PAGE BECAUSE THE FIELD IS EMPTY!");
      this.props.history.push("list");
    } else {
      fetchAPIData("/api/video/add_video.php", this.state.URL, "POST").then(
        (json) => {
          this.setState({
            loading: true,
          });
          this.setState({ APIReturnStatus: json.status });
          this.setState({ APIReturnMessage: json.message });
          document.getElementById("UpdateVideo").value = "";
          if (json.status === "Success") {
            this.setState({
              loading: false,
            });
          } else {
            this.setState({
              loading: false,
            });
          }
        }
      );
    }
  }
  handleModal() {
    this.setState({ show: !this.state.show });
  }

  secondHandleModal() {
    this.setState({ secondShow: !this.state.secondShow });
  }
  closeFirstModal() {
    this.setState({ show: false });
  }
  closeSecondModal() {
    this.setState({ secondShow: false });
  }
  showWrongURLModel() {
    this.setState({
      loading: false,
    });
    this.setState({ URLModel: !this.state.URLModel });
  }
  reloadThePage() {
    window.location.reload(false);
  }

  render() {
    return isAuthenticated ? (
      <div>
        {this.state.loading ? <Loader /> : null}
        <Modal
          backdrop="static"
          show={this.state.URLModel}
          onHide={() => {
            this.showWrongURLModel();
          }}
        >
          <Modal.Header>
            <b>WRONG URL!!!!!!</b>
          </Modal.Header>
          <Modal.Body>
            <b>RELOADING THE PAGE</b>
          </Modal.Body>
          <Modal.Footer>
            <Button
              variant="success"
              onClick={() => {
                this.reloadThePage();
              }}
            >
              OK
            </Button>
          </Modal.Footer>
        </Modal>

        <Modal
          backdrop="static"
          show={this.state.show}
          onHide={() => {
            this.handleModal();
          }}
        >
          <Modal.Header closeButton>
            <b>CONFIRMATION FOR VIDEO UPDATE</b>
          </Modal.Header>
          <Modal.Body>
            <b>
              If You want to update your VIDEO on YT by this URL click the
              button below.
            </b>
          </Modal.Body>
          <Modal.Footer>
            <Button
              onClick={() => {
                this.handleModal();
              }}
              variant="danger"
            >
              Cancel
            </Button>
            <Button
              variant="success"
              onClick={() => {
                this.secondHandleModal();
              }}
            >
              OK
            </Button>
          </Modal.Footer>
        </Modal>

        <Modal
          show={this.state.secondShow}
          backdrop="static"
          onHide={() => {
            this.secondHandleModal();
            this.closeFirstModal();
          }}
        >
          <Modal.Header closeButton>
            <b>SECOND CONFIRMATION FOR VIDEO UPDATE</b>
          </Modal.Header>
          <Modal.Body>
            <b>
              If You want to update your VIDEO on YT by this URL click the
              button below.
            </b>
          </Modal.Body>
          <Modal.Footer>
            <Button
              onClick={() => {
                this.secondHandleModal();
                this.closeFirstModal();
              }}
              variant="danger"
            >
              Cancel
            </Button>
            <Button
              variant="success"
              onClick={() => {
                this.closeSecondModal();
                this.closeFirstModal();
                this.API_Update_Video();
                this.setState({ loading: true });
              }}
            >
              OK
            </Button>
          </Modal.Footer>
        </Modal>
        <NavBarMenu />

        {this.state.APIReturnStatus === "Success" ? (
          <Message color="success" message={this.state.APIReturnMessage} />
        ) : null}
        {this.state.APIReturnStatus === "Failed" ? (
          <Message color="danger" message={this.state.APIReturnMessage} />
        ) : null}
        <LastUpdated time={this.state.PageVistTime} />
        <Container>
          <Card
            className="mx-auto mt-1 mb-5"
            style={{
              width: "auto",
              background: "azure",
              borderRadius: "1rem",
            }}
          >
            <Card.Header>
              <h3 style={{ fontWeight: "bold" }}>Video Update</h3>
            </Card.Header>
            <Card.Body>
              <Form>
                <Form.Group>
                  <Form.Control
                    size="lg"
                    type="url"
                    autoComplete="off"
                    id="UpdateVideo"
                    placeholder="Enter YT URL"
                    onChange={(event) => {
                      this.setState({ URL: event.target.value });
                    }}
                    required
                  />
                </Form.Group>
              </Form>
            </Card.Body>
            <Card.Footer>
              <Button
                variant="success"
                onClick={() => {
                  this.handleModal();
                }}
                size="lg"
              >
                Update Video
              </Button>
            </Card.Footer>
          </Card>
        </Container>
        {/* {console.clear()} */}
      </div>
    ) : (
      <Redirect to={{ pathname: "/login" }} />
    );
  }
}

export default UpdateVideo;
