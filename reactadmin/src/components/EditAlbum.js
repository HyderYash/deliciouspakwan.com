import React, { Component } from "react";
import {
  Col,
  Container,
  Table,
  Card,
  Modal,
  OverlayTrigger,
  Tooltip,
  Button,
  Row,
} from "react-bootstrap";
import NavBarMenu from "./NavBarMenu";
import { fetchAPIData, API_ROOT_PATH } from "./Common";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faEdit } from "@fortawesome/free-solid-svg-icons";
import Spinner from "../assets/utility/Loader";
import { Link } from "react-router-dom";
import EditPhotoStatus from "./EditPhotoStatus";
import "../assets/css/AlbumList.css";
import AddEditAlbum from "./AddEditAlbum";

class EditAlbum extends Component {
  constructor() {
    super();
    this.state = {
      ApiAlbumPhotosData: [],
      AlbumID: window.location.pathname.split("/")[4],
      AlbumDisplayTitle: window.location.pathname
        .split("/")[3]
        .replace("-", " "),
      AlbumFolderTitle: window.location.pathname.split("/")[2],
      editAlbumUrl: "",
      NoOfAlbum: "",
      show: false,
      editShow: false,
      loading: true,
      imageUrl: "",
      photoStatus: "",
      photoID: "",
    };
  }
  componentDidMount() {
    window.scrollTo(0, 0);
    sessionStorage.setItem("InitialAlbumTitle", this.state.AlbumDisplayTitle);
    sessionStorage.setItem("AlbumID", this.state.AlbumID);
    const formData = {
      ALBUM_ID: this.state.AlbumID,
      DISPLAY: "BE",
    };
    fetchAPIData("/api/albums/album_photos_list.php", formData, "POST")
      .then((json) => {
        this.setState({ NoOfAlbum: json.message });
        this.setState({ ApiAlbumPhotosData: json.records[1] });
        this.setState({ loading: false });
      })
      .catch((err) => {
        console.log(err);
      });
  }
  handleClose() {
    this.setState({ show: false });
  }

  handleShow() {
    this.setState({ show: true });
  }

  handleEditClose = () => this.setState({ editShow: false });
  handleEditShow = () => this.setState({ editShow: true });

  addVideoIdToArr(videoId) {
    const cb = document.getElementById(videoId);
    if (cb.checked === true) {
      this.videoIdarr.push(videoId);
    } else {
      const index = this.videoIdarr.indexOf(videoId);
      if (index > -1) {
        this.videoIdarr.splice(index, 1);
      }
    }
  }
  ParentCheckboxFunc() {
    this.videoIdarr = [];
    const cb = document.getElementById("selectAllChk");
    var items = document.getElementsByName("chkbox");
    for (var i = 0; i < items.length; i++) {
      if (cb.checked === true) {
        items[i].checked = true;
        this.videoIdarr.push(items[i].value);
      } else {
        items[i].checked = false;
        const index = this.videoIdarr.indexOf(items[i].value);
        if (index > -1) {
          this.videoIdarr.splice(index, 1);
        }
      }
    }
  }

  selectOffSelectAllChkbox() {
    let selectAllBtn = true;
    var items = document.getElementsByName("chkbox");
    for (var i = 0; i < items.length; i++) {
      if (items[i].type === "checkbox" && items[i].checked === false) {
        selectAllBtn = false;
      }
    }
    document.getElementById("selectAllChk").checked = selectAllBtn;
  }
  setData(imageUrl, status, id) {
    this.setState({ imageUrl: imageUrl });
    this.setState({ photoStatus: status });
    this.setState({ photoID: id });
    this.setState({
      editAlbumUrl: `/editalbum/${this.state.AlbumFolderTitle}/${this.state.AlbumDisplayTitle}/${this.state.AlbumID}`,
    });
  }
  render() {
    return (
      <div>
        {this.state.loading ? <Spinner /> : null}
        <NavBarMenu />
        <Modal
          backdrop="static"
          show={this.state.editShow}
          onHide={() => this.handleEditClose()}
          keyboard={false}
          size="lg"
        >
          <Modal.Header closeButton>
            <h3>Edit Photo</h3>
          </Modal.Header>
          <Modal.Body>
            <EditPhotoStatus
              imageUrl={this.state.imageUrl}
              photoStatus={this.state.photoStatus}
              id={this.state.photoID}
              editAlbumUrl={this.state.editAlbumUrl}
            />
          </Modal.Body>
        </Modal>
        <Modal
          backdrop="static"
          show={this.state.show}
          onHide={() => this.handleClose()}
          keyboard={false}
          size="lg"
        >
          <Modal.Header closeButton>
            <h3>Modify Album</h3>
          </Modal.Header>
          <Modal.Body>
            <AddEditAlbum albumId={this.state.AlbumID} />
          </Modal.Body>
        </Modal>
        <Container fluid className="mt-3">
          <Row>
            <Col className="text-left" xs={12} sm={12} md={8} lg={8}>
              <h3>
                {this.state.AlbumDisplayTitle}{" "}
                <span>
                  <OverlayTrigger
                    placement="right"
                    overlay={
                      <Tooltip>
                        <strong>Edit Album Data</strong>
                      </Tooltip>
                    }
                  >
                    <FontAwesomeIcon
                      style={{
                        cursor: "pointer",
                        fontSize: "20px",
                        marginBottom: "2px",
                      }}
                      icon={faEdit}
                      onClick={() => this.handleShow()}
                    />
                  </OverlayTrigger>
                </span>
              </h3>
              {this.state.ApiAlbumPhotosData.length > 0 ? (
                <p>{this.state.NoOfAlbum}</p>
              ) : null}
            </Col>
            <Col xs={12} sm={12} md={2} lg={2}>
              {/* <Button style={{ float: "left" }} variant="info" className="mb-2">
                <FontAwesomeIcon icon={faFileUpload} className="mr-2" />
                Upload Photos
              </Button> */}
            </Col>
            <Col xs={12} sm={12} md={2} lg={2}>
              <Button as={Link} to="/albumlist" style={{ float: "left" }}>
                Back to Album List
              </Button>
            </Col>
          </Row>
        </Container>
        {this.state.ApiAlbumPhotosData.length > 0 ? (
          <Table
            bordered
            responsive
            className="mt-1 mx-auto"
            variant="light"
            style={{
              width: "100%",
              tableLayout: "auto",
            }}
          >
            <thead>
              <tr
                style={{
                  background: "#ddd",
                  fontWeight: "800",
                  textTransform: "uppercase",
                }}
              >
                <th style={{ fontSize: "15px" }}>ID</th>
                <th style={{ fontSize: "15px" }}>PHOTO</th>
                <th style={{ fontSize: "15px" }}>ACTION</th>
              </tr>
            </thead>
            <tbody>
              {this.state.ApiAlbumPhotosData.map((item, key) => (
                <tr
                  key={key}
                  className={
                    item.PHOTO_STATUS === "Y"
                      ? "albumstatusactive"
                      : "albumstatusinactive"
                  }
                >
                  <td style={{ verticalAlign: "middle" }}>
                    <h5
                      style={{
                        fontSize: "13px",
                        lineHeight: "0px",
                        marginBottom: "0px",
                      }}
                    >
                      {item.ID}
                    </h5>
                  </td>
                  <td
                    style={{ verticalAlign: "middle" }}
                    align="center"
                    valign="middle"
                  >
                    <div
                      style={{
                        height: "40px",
                        display: "flex",
                        justifyContent: "center",
                        alignSelf: "center",
                      }}
                      className="vx-auto"
                    >
                      <img
                        draggable="false"
                        alt="albumThumbnail"
                        style={{
                          maxWidth: "100%",
                          maxHeight: "100%",
                          display: "block",
                          pointerEvents: "none",
                        }}
                        src={`${API_ROOT_PATH}/images/albums/${this.state.AlbumFolderTitle}/${item.IMAGE_THUMB_URL}`}
                      ></img>
                    </div>
                  </td>

                  <td style={{ fontWeight: "800", verticalAlign: "middle" }}>
                    <OverlayTrigger
                      placement="left"
                      overlay={
                        <Tooltip>
                          <strong>Edit</strong>
                        </Tooltip>
                      }
                    >
                      <Button
                        variant="info"
                        className="ml-2"
                        onClick={() => {
                          this.setData(
                            `${API_ROOT_PATH}/images/albums/${this.state.AlbumFolderTitle}/${item.IMAGE_THUMB_URL}`,
                            item.PHOTO_STATUS,
                            item.ID
                          );
                          this.handleEditShow();
                        }}
                      >
                        <FontAwesomeIcon icon={faEdit} />
                      </Button>
                    </OverlayTrigger>
                  </td>
                </tr>
              ))}
            </tbody>
          </Table>
        ) : (
          <Container>
            <Card className="mt-3">
              <Card.Body>
                <h5>No Photos In This Album</h5>
              </Card.Body>
            </Card>
          </Container>
        )}
        {/* <Button
          style={{ float: "right" }}
          className="mr-3 mt-3 mb-3"
          variant="danger"
        >
          <FontAwesomeIcon
            style={{
              cursor: "pointer",
              fontSize: "20px",
            }}
            className="mr-1"
            icon={faTrash}
          />
          Delete Album
        </Button> */}
      </div>
    );
  }
}

export default EditAlbum;
