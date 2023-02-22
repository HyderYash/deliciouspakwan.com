import React, { Component } from "react";
import { Button, Card, Container, Form, Table } from "react-bootstrap";
import Message from "../assets/utility/Message";
import { fetchAPIData } from "./Common";
import NavBarMenu from "./NavBarMenu";

export default class AlbumAccess extends Component {
  constructor() {
    super();
    this.state = {
      adminKey: "",
      accessLimit: "",
      retMessage: "",
    };
  }
  componentDidMount() {
    fetchAPIData("/api/albums/album_access_details.php", "", "GET")
      .then((res) => {
        this.setState({ adminKey: res.records.ADMIN_KEY });
        this.setState({ accessLimit: res.records.ACCESS_LIMIT });
      })
      .catch((err) => {
        console.error(err);
      });
  }
  handleAlbumAccess() {
    const formData = {
      ADMIN_KEY: this.state.adminKey,
      ACCESS_LIMIT: this.state.accessLimit,
    };
    fetchAPIData(
      "/api/albums/change_album_access_details.php",
      formData,
      "POST"
    )
      .then((res) => {
        this.setState({ retMessage: res.message });
      })
      .catch((err) => {
        console.error(err);
      });
  }
  render() {
    return (
      <div>
        <NavBarMenu />
        <Container>
          <Card
            className="mx-auto mt-5 mb-5"
            style={{
              background: "azure",
              width: "auto",
              borderRadius: "1rem",
            }}
          >
            <Card.Body>
              <Card.Title>
                <h2>Access Settings</h2>
              </Card.Title>
              {this.state.retMessage !== "" ? (
                <Message message={this.state.retMessage} />
              ) : null}
              <Form>
                <Table striped bordered hover size="sm">
                  <thead>
                    <tr>
                      <th style={{ fontSize: "15px" }}>ADMIN_KEY</th>
                      <th style={{ fontSize: "15px" }}>ACCESS_LIMIT</th>
                      <th style={{ fontSize: "15px" }}>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style={{ fontSize: "15px" }}>
                        <Form.Control
                          type="text"
                          value={this.state.adminKey}
                          onChange={(e) =>
                            this.setState({ adminKey: e.target.value })
                          }
                        />
                      </td>
                      <td style={{ fontSize: "15px" }}>
                        <Form.Control
                          type="text"
                          value={this.state.accessLimit}
                          onChange={(e) =>
                            this.setState({ accessLimit: e.target.value })
                          }
                        />
                      </td>
                      <td style={{ fontSize: "15px" }}>
                        <Button
                          variant="info"
                          size="sm"
                          onClick={() => {
                            this.handleAlbumAccess();
                          }}
                        >
                          Submit
                        </Button>
                      </td>
                    </tr>
                  </tbody>
                </Table>
              </Form>
            </Card.Body>
          </Card>
        </Container>
      </div>
    );
  }
}
