import React, { Component } from "react";
import { Button, Form, Modal, Table } from "react-bootstrap";
import NavBarMenu from "./NavBarMenu";
import { fetchAPIData, isAuthenticated, SUCCESS, FAILED } from "./Common";
import { Redirect } from "react-router-dom";
import Message from "../assets/utility/Message";
import { Link } from "react-router-dom";
import LastUpdated from "../assets/utility/LastUpdated";
import Spinner from "../assets/utility/Loader";

class DisplaySettingsUpdate extends Component {
  constructor() {
    super();
    this.state = {
      ID: JSON.parse(sessionStorage.getItem("API_DATA"))[
        sessionStorage.getItem("CURRENT_SETTINGS_ITEM_ID") - 1
      ].ID,
      DISPLAY_NAME: JSON.parse(sessionStorage.getItem("API_DATA"))[
        sessionStorage.getItem("CURRENT_SETTINGS_ITEM_ID") - 1
      ].DISPLAY_NAME,
      MOBILE: JSON.parse(sessionStorage.getItem("API_DATA"))[
        sessionStorage.getItem("CURRENT_SETTINGS_ITEM_ID") - 1
      ].MOBILE,
      DESKTOP: JSON.parse(sessionStorage.getItem("API_DATA"))[
        sessionStorage.getItem("CURRENT_SETTINGS_ITEM_ID") - 1
      ].DESKTOP,
      show: false,
      APIReturnMessage: "",
      APIReturnStatus: "",
      PageVistTime: JSON.parse(sessionStorage.getItem("menuRouteLinkDetails"))
        .records[1].LIST_ITEM_LAST_UPDATED,
      loading: null,
    };
  }

  API_Update_Func() {
    if (this.state.MOBILE && this.state.DESKTOP === null) {
      alert("Please Enter Values into the Input Fields");
    } else {
      fetchAPIData(
        "/api/settings/update_settings.php",
        this.state,
        "POST"
      ).then((json) => {
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
      });
    }
  }

  handleModal() {
    this.setState({ show: !this.state.show });
  }
  closeFirstModal() {
    this.setState({ show: false });
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
            <b>UPDATE VIDEO</b>
          </Modal.Header>
          <Modal.Body>
            <b>
              If You want to update your VIDEO click the button below. You can
              see the Results In the List Page
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
                this.API_Update_Func();
                this.closeFirstModal();
                this.setState({ loading: true });
              }}
            >
              OK
            </Button>
          </Modal.Footer>
        </Modal>

        <NavBarMenu />
        {this.state.APIReturnStatus === SUCCESS ? (
          <Message
            color="success"
            message={`${this.state.APIReturnMessage} GO TO LIST PAGE TO CHECK THE RESULTS`}
          />
        ) : null}
        {this.state.APIReturnStatus === FAILED ? (
          <Message color="danger" message={this.state.APIReturnMessage} />
        ) : null}
        <LastUpdated time={this.state.PageVistTime} />
        <Table striped bordered responsive hover className="mt-1 mx-auto">
          <thead>
            <tr style={{ background: "#ddd" }}>
              <th>ID</th>
              <th>DISPLAY NAME</th>
              <th>MOBILE</th>
              <th>DESKTOP</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style={{ background: "#A9F5BC" }}>
                {
                  JSON.parse(sessionStorage.getItem("API_DATA"))[
                    sessionStorage.getItem("CURRENT_SETTINGS_ITEM_ID") - 1
                  ].ID
                }
              </td>
              <td style={{ background: "#F3E2A9" }}>
                {
                  JSON.parse(sessionStorage.getItem("API_DATA"))[
                    sessionStorage.getItem("CURRENT_SETTINGS_ITEM_ID") - 1
                  ].DISPLAY_NAME
                }
              </td>
              <td style={{ background: "#A9F5F2" }}>
                <Form.Control
                  type="number"
                  required
                  value={this.state.MOBILE}
                  className="mb-2"
                  onChange={(event) => {
                    this.setState({
                      MOBILE: event.target.value.replace(/\D/, ""),
                    });
                  }}
                />
              </td>
              <td style={{ background: "#BBFF99" }}>
                <Form.Control
                  type="number"
                  required
                  min="2"
                  value={this.state.DESKTOP}
                  className="mb-2"
                  onChange={(event) => {
                    this.setState({
                      DESKTOP: event.target.value.replace(/\D/, ""),
                    });
                  }}
                />
              </td>
            </tr>
          </tbody>
        </Table>
        <Button
          variant="success"
          size="lg"
          onClick={() => {
            this.handleModal();
          }}
        >
          Update Settings
        </Button>
        <Button
          variant="success"
          as={Link}
          to="/displaysettings"
          className="mx-3"
          size="lg"
        >
          Go To Display Settings Page
        </Button>
        {/* {console.clear()} */}
      </div>
    ) : (
      <Redirect to={{ pathname: "/login" }} />
    );
  }
}
export default DisplaySettingsUpdate;
