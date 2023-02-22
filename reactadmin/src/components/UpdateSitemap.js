import React, { Component } from "react";
import NavBarMenu from "./NavBarMenu";
import { Button, Card, Container, Modal } from "react-bootstrap";
import { Redirect } from "react-router-dom";
import { fetchAPIData, isAuthenticated } from "./Common";
import Message from "../assets/utility/Message";
import LastUpdated from "../assets/utility/LastUpdated";
import Spinner from "../assets/utility/Loader";
// import { Editor } from "@tinymce/tinymce-react";

class UpdateSitemap extends Component {
  constructor() {
    super();
    this.state = {
      status: "",
      show: false,
      APIReturnMessage: "",
      APIReturnStatus: "",
      PageVistTime: JSON.parse(sessionStorage.getItem("menuRouteLinkDetails"))
        .records[sessionStorage.getItem("CURRENT_CLICKED_PAGE_ID")]
        .LIST_ITEM_LAST_UPDATED,
      loading: null,
    };
  }

  handleModal() {
    this.setState({ show: !this.state.show });
  }
  closeFirstModal() {
    this.setState({ show: false });
  }
  Update_Sitempap_Func() {
    fetchAPIData("/api/sitemap/update_sitemapxml.php", "", "GET").then(
      (json) => {
        this.setState({
          loading: true,
        });
        this.setState({ APIReturnStatus: json.status });
        this.setState({ APIReturnMessage: json.message });
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

  render() {
    return isAuthenticated ? (
      <div>
        {this.state.loading ? <Spinner /> : null}
        <Modal
          backdrop="static"
          show={this.state.show}
          onHide={() => {
            this.handleModal();
          }}
        >
          <Modal.Header closeButton>
            <b>DO YOU REALLY WANT TO UPDATE THE SITEMAP</b>
          </Modal.Header>
          <Modal.Body>
            If You want to update your <b>SITEMAP</b> then click the button
            below.
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
                this.closeFirstModal();
                this.Update_Sitempap_Func();
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
            className="mx-auto mt-3 mb-5"
            style={{
              background: "#BBFF99",
              width: "auto",
              borderRadius: "1rem",
            }}
          >
            <Card.Header>
              <h3 style={{ fontWeight: "bold" }}>Click The Button To</h3>
            </Card.Header>
            <Card.Body>
              <Button
                style={{ background: "#05A85C" }}
                size="lg"
                className="btn-block"
                onClick={() => {
                  this.handleModal();
                }}
              >
                Update Sitemap
              </Button>
            </Card.Body>
          </Card>
        </Container>
        {/* <Container>
          <Editor
            initialValue=""
            init={{
              height: 300,
              menubar: true,
              plugins: [
                "advlist autolink lists link image charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table paste code wordcount",
              ],
              toolbar:
                // eslint-disable-next-line
                "undo redo | formatselect | bold italic backcolor | \
             alignleft aligncenter alignright alignjustify | \
             bullist numlist outdent indent | removeformat",
            }}
            onEditorChange={this.handleEditorChange}
          />
        </Container> */}
      </div>
    ) : (
      <Redirect to={{ pathname: "/login" }} />
    );
  }
}
export default UpdateSitemap;
