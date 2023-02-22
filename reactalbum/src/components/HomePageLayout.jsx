import React, { Component } from "react";
import {
  Button,
  Card,
  Col,
  Container,
  Row,
  Badge,
  FormControl,
  Form,
} from "react-bootstrap";
import { Redirect } from "react-router-dom";
import { fetchAPIData, API_ROOT_PATH, encryptText } from "../utility/Common";
import Loader from "./Loader";

class HomePageLayout extends Component {
  constructor() {
    super();
    this.state = {
      search: "",
      albumsList: [],
      countOfAlbums: "",
      loading: true,
      isDisabledVisitAlbumBTN: [],
      adminKey: "0000",
      redirectToInternal: false,
      redirectURL: "",
    };
    this.checkAlbumAccessKey = this.checkAlbumAccessKey.bind(this);
  }
  componentDidMount() {
    this.getData();
  }
  getData() {
    const formData = {
      DISPLAY: "FE",
    };
    fetchAPIData("/api/albums/album_list.php", formData, "POST")
      .then((json) => {
        this.setState({ albumsList: json.records });
        this.setState({ countOfAlbums: json.message });
        this.setState({ loading: false });
      })
      .catch((err) => {
        console.log(err);
      });
  }

  async prepareAlbumPhotosURL(album) {
    const encryptedAdminKey = encryptText(this.state.adminKey);
    let urlPrefix = "/albumcarousel";
    let url =
      urlPrefix +
      "/" +
      album.ALBUM_FOLDER_TITLE +
      "/" +
      album.ID +
      "/" +
      album.ALBUM_ACCESS_KEY +
      "/" +
      encryptedAdminKey +
      "/" +
      album.ALBUM_TYPE;
    localStorage.setItem("redirectFromParent", true);
    this.setState({ redirectURL: url });
    this.setState({ redirectToInternal: true });
  }
  checkAlbumAccessKey(e, albumId) {
    this.setState({ adminKey: e.target.value });
    if (e.target.value.length === 4) {
      this.setState({
        isDisabledVisitAlbumBTN: [
          ...this.state.isDisabledVisitAlbumBTN,
          albumId,
        ],
      });
    } else {
      this.setState({ isDisabledVisitAlbumBTN: [] });
    }
  }
  renderAlbums = (albums, key) => {
    if (
      this.state.search !== "" &&
      albums.ALBUM_DISPLAY_TITLE.toLowerCase().indexOf(
        this.state.search.toLowerCase()
      ) === -1
    ) {
      return null;
    }

    return (
      <Col xs={12} md={2} lg={2} key={key}>
        <Card
          className="mt-3 mb-1"
          style={{ backgroundColor: "azure", minHeight: "215px" }}
        >
          <div style={{ width: "100%" }}>
            <img
              variant="top"
              src={`${API_ROOT_PATH}/images/albums/${albums.ALBUM_FOLDER_TITLE}/thumbnail/${albums.IMAGE_THUMB_URL}`}
              draggable="false"
              alt="Album Thumbnail"
              style={{
                height: "100px",
                width: "100%",
                objectFit: "contain",
                pointerEvents: "none",
                backgroundColor: "black",
              }}
            />
          </div>
          <Card.Body style={{ padding: "0.50rem" }}>
            <Card.Title
              style={{
                marginBottom: "3px",
                fontSize: "1rem",
                fontWeight: "bold",
              }}
            >
              {albums.ALBUM_DISPLAY_TITLE}
            </Card.Title>
            <Card.Text
              style={{
                marginBottom: "0px",
                color: "green",
                fontSize: "0.75rem",
              }}
            >
              <strong>
                {albums.photoCount} Photos{" "}
                <span style={{ color: "black" }}>|</span> {albums.videoCount}{" "}
                Videos{" "}
                <span style={{ color: "navy" }}>
                  <span style={{ color: "black" }}>|</span>{" "}
                  {albums.VISIT_COUNTER} views
                </span>
              </strong>
            </Card.Text>
            <Card.Text style={{ marginBottom: "5px", fontSize: "0.75rem" }}>
              {albums.ALBUM_DESC.slice(0, 20).concat("...")}
            </Card.Text>
            {albums.ALBUM_TYPE === "PR" ? (
              <div style={{ display: "flex", justifyContent: "start" }}>
                <Form.Control
                  style={{ width: "50%" }}
                  type="password"
                  placeholder="Enter Key"
                  maxLength={4}
                  onChange={(e) => {
                    this.checkAlbumAccessKey(e, albums.ID);
                  }}
                />
                <Button
                  variant="primary"
                  style={{ marginLeft: "8px" }}
                  disabled={
                    this.state.isDisabledVisitAlbumBTN.indexOf(albums.ID) !== -1
                      ? false
                      : true
                  }
                  onClick={() => this.prepareAlbumPhotosURL(albums)}
                  size="sm"
                >
                  Visit Album
                </Button>
              </div>
            ) : (
              <Button
                variant="primary"
                onClick={() => this.prepareAlbumPhotosURL(albums)}
                size="sm"
              >
                Visit Album
              </Button>
            )}
          </Card.Body>
        </Card>
      </Col>
    );
  };

  onchange = (e) => {
    this.setState({ search: e.target.value });
  };

  render() {
    const filteredAlbums = this.state.albumsList.filter((albums) => {
      return (
        albums.ALBUM_DISPLAY_TITLE.toLowerCase().indexOf(
          this.state.search.toLowerCase()
        ) !== -1
      );
    });
    if (this.state.redirectToInternal) {
      return <Redirect to={this.state.redirectURL} />;
    }
    return (
      <div>
        {this.state.loading === true ? <Loader /> : null}
        <Container fluid>
          <Row>
            <Col xs={12} md={10} lg={10}>
              <h2 className="mt-3">
                <Badge variant="primary">
                  Recent Albums{" "}
                  <Badge variant="light">{this.state.countOfAlbums}</Badge>
                </Badge>
              </h2>
            </Col>
            <Col xs={12} md={2} lg={2}>
              <FormControl
                style={{ float: "right" }}
                onChange={this.onchange}
                className="ml-2 mt-3"
                placeholder="Search"
                aria-label="Search"
              />
            </Col>
          </Row>
        </Container>
        <Container fluid>
          <Row>
            {filteredAlbums.length > 0 ? (
              filteredAlbums.map((albums, key) => {
                return this.renderAlbums(albums, key);
              })
            ) : (
              <div
                style={{
                  width: "100%",
                  border: "1px solid",
                  padding: "10px",
                  textAlign: "center",
                  marginTop: "15px",
                }}
              >
                <h6 className="ml-2">No Albums To Display</h6>
              </div>
            )}
          </Row>
        </Container>
      </div>
    );
  }
}

export default HomePageLayout;
