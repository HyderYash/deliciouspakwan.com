import React, { Component } from "react";
import NavBarMenu from "./NavBarMenu";
import {
  Container,
  Table,
  Button,
  Modal,
  Form,
  ProgressBar,
  Tooltip,
  OverlayTrigger,
  Card,
} from "react-bootstrap";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faFileUpload,
  faEdit,
  faPlayCircle,
} from "@fortawesome/free-solid-svg-icons";
import { API_ROOT_PATH, fetchAPIData } from "./Common";
import axios from "axios";
import Message from "../assets/utility/Message";
import "../assets/css/AlbumList.css";

class AddVideoToAlbum extends Component {
  constructor() {
    super();
    this.state = {
      openUploadModal: false,
      file: "",
      fileName: "Choose Files",
      uploadPercentage: 0,
      videoTitle: "",
      videoStatus: "",
      message: "",
      buttonDisabled: false,
      loading: true,
      videosList: [],
      openEditVideoModal: false,
      videoID: "",
      videoDesc: "",
      videoSrcURL: "",
      openVideoModalState: false,
    };
    this.handleSubmit = this.handleSubmit.bind(this);
    this.handleSubmitEditData = this.handleSubmitEditData.bind(this);
  }
  handleOpen() {
    this.setState({ openUploadModal: true });
  }
  handleClose() {
    this.setState({ openUploadModal: false });
  }
  handleOpenEditModal() {
    this.setState({ openEditVideoModal: true });
  }
  handleCloseEditModal() {
    this.setState({ openEditVideoModal: false });
  }
  handleOpenVideoModal() {
    this.setState({ openVideoModalState: true });
  }
  handleCloseVideoModal() {
    this.setState({ openVideoModalState: false });
  }
  selectFileData(e) {
    console.log(e.target.files[0]);
    this.setState({ file: e.target.files[0] });
    this.setState({ fileName: e.target.files[0].name });
  }
  handleSubmit(e) {
    this.setState({ buttonDisabled: true });
    e.preventDefault();
    const formData = new FormData();
    formData.append("videoFile", this.state.file);
    formData.append("videoTitle", this.state.videoTitle);
    formData.append("videoDesc", this.state.videoDesc);
    formData.append("videoStatus", this.state.videoStatus);
    axios
      .post(
        `${API_ROOT_PATH}/api/addvideostoalbum/uploadVideoAndUpdateInDB.php`,
        formData,
        {
          headers: {
            "Content-Type": "multipart/form-data",
          },
          onUploadProgress: (ProgressEvent) => {
            var percentCompleted = Math.round(
              (ProgressEvent.loaded * 100) / ProgressEvent.total
            );
            this.setState({ uploadPercentage: percentCompleted });
          },
        }
      )
      .then((res) => {
        if (res.data.status === "Success") {
          this.getData();
          this.handleClose();
        }
        console.log(res);
      })
      .catch((err) => {
        this.setState({ message: "Failed To Upload" });
        console.log(err);
      });
  }
  componentDidMount() {
    this.getData();
  }
  getData() {
    fetchAPIData("/api/addvideostoalbum/getVideos.php", "", "GET").then(
      (json) => {
        if (json.status === "Success") {
          this.setState({ videosList: json.records });
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
  getDataToEdit(videoID) {
    this.setState({ videoID: videoID });
    const formData = {
      VIDEO_ID: videoID,
    };
    fetchAPIData(
      "/api/addvideostoalbum/getVideosDataByID.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        console.log(json.records[0].UPLOADED_DATE);
        this.setState({ videoTitle: json.records[0].VIDEO_TITLE });
        this.setState({ videoDesc: json.records[0].VIDEO_DESC });
        this.setState({ videoStatus: json.records[0].VIDEO_STATUS });
        this.setState({ openEditVideoModal: true });
      } else {
        this.setState({
          loading: false,
        });
      }
    });
  }

  handleSubmitEditData(e) {
    e.preventDefault();
    const formData = {
      VIDEO_ID: this.state.videoID,
      VIDEO_TITLE: this.state.videoTitle,
      VIDEO_DESC: this.state.videoDesc,
      VIDEO_STATUS: this.state.videoStatus,
    };
    fetchAPIData(
      "/api/addvideostoalbum/updateVideoStatus.php",
      formData,
      "POST"
    ).then((json) => {
      if (json.status === "Success") {
        this.setState({ videoTitle: "" });
        this.setState({ videoDesc: "" });
        this.setState({ videoStatus: "" });
        this.handleCloseEditModal();
        this.getData();
      } else {
        this.setState({ message: "Failed To Upload" });
      }
    });
  }

  openVideoModal(videoName) {
    const videoURI = `${API_ROOT_PATH}/images/uploadedVideos/${videoName}`;
    this.setState({ videoSrcURL: videoURI });
    this.handleOpenVideoModal();
  }

  render() {
    return (
      <div>
        <Modal show={this.state.openVideoModalState}>
          <Modal.Header>
            <Modal.Title>View Video</Modal.Title>
          </Modal.Header>
          <Modal.Body>
            <div
              style={{
                display: "flex",
                justifyContent: "center",
                alignItems: "center",
              }}
            >
              <video width="430" height="300" controls>
                <source src={this.state.videoSrcURL} type="video/mp4" />
                Your browser does not support the video tag.
              </video>
            </div>
          </Modal.Body>
          <br />
          <Modal.Footer>
            <Button
              variant="secondary"
              onClick={() => this.handleCloseVideoModal()}
            >
              Close
            </Button>
          </Modal.Footer>
        </Modal>
        <Modal show={this.state.openEditVideoModal}>
          <Form onSubmit={this.handleSubmitEditData}>
            <Modal.Header>
              <Modal.Title>Edit Video</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              {this.state.message ? (
                <div className="mt3 mb-3">
                  <Message color="info" message={this.state.message} />
                </div>
              ) : null}
              <Form.Control
                type="text"
                placeholder="Enter Video Title"
                className="mb-3"
                required
                value={this.state.videoTitle}
                onChange={(e) => this.setState({ videoTitle: e.target.value })}
              />
              <Form.Control
                as="textarea"
                rows={3}
                placeholder="Enter Video Description"
                className="mb-3"
                required
                value={this.state.videoDesc}
                onChange={(e) => this.setState({ videoDesc: e.target.value })}
              />
              <div style={{ float: "left", position: "relative" }}>
                <input
                  type="radio"
                  value="Y"
                  className="mr-1"
                  required
                  name="activeInactive"
                  checked={this.state.videoStatus === "Y"}
                  onChange={(e) =>
                    this.setState({ videoStatus: e.target.value })
                  }
                />
                <label htmlFor="activeInactive" className="mr-1">
                  Active
                </label>
                <input
                  type="radio"
                  value="N"
                  className="mr-1"
                  name="activeInactive"
                  checked={this.state.videoStatus === "N"}
                  onChange={(e) =>
                    this.setState({ videoStatus: e.target.value })
                  }
                />
                <label htmlFor="activeInactive">InActive</label>
              </div>
            </Modal.Body>
            <br />
            <Modal.Footer>
              <Button
                variant="secondary"
                onClick={() => this.handleCloseEditModal()}
              >
                Close
              </Button>
              <Button variant="primary" type="submit">
                Submit
              </Button>
            </Modal.Footer>
          </Form>
        </Modal>
        <Modal show={this.state.openUploadModal}>
          <Form onSubmit={this.handleSubmit}>
            <Modal.Header>
              <Modal.Title>Upload Video</Modal.Title>
            </Modal.Header>
            <Modal.Body>
              {this.state.message ? (
                <div className="mt3 mb-3">
                  <Message color="info" message={this.state.message} />
                </div>
              ) : null}
              <Form.Control
                type="text"
                placeholder="Enter Video Title"
                className="mb-3"
                required
                onChange={(e) => this.setState({ videoTitle: e.target.value })}
              />
              <Form.Control
                as="textarea"
                rows={3}
                placeholder="Enter Video Description"
                className="mb-3"
                required
                onChange={(e) => this.setState({ videoDesc: e.target.value })}
              />
              <div style={{ float: "left", position: "relative" }}>
                <input
                  type="radio"
                  value="Y"
                  className="mr-1"
                  required
                  name="activeInactive"
                  checked={this.state.videoStatus === "Y"}
                  onChange={(e) =>
                    this.setState({ videoStatus: e.target.value })
                  }
                />
                <label htmlFor="activeInactive" className="mr-1">
                  Active
                </label>
                <input
                  type="radio"
                  value="N"
                  className="mr-1"
                  name="activeInactive"
                  checked={this.state.videoStatus === "N"}
                  onChange={(e) =>
                    this.setState({ videoStatus: e.target.value })
                  }
                />
                <label htmlFor="activeInactive">InActive</label>
              </div>

              <Form.File
                onChange={(e) => this.selectFileData(e)}
                style={{ textAlign: "left" }}
                accept="video/mp4,video/x-m4v,video/*"
                required
                label={this.state.fileName}
                custom
              />
              <div className="mt-3">
                <ProgressBar
                  now={this.state.uploadPercentage}
                  label={`${this.state.uploadPercentage}%`}
                  animated
                />
              </div>
            </Modal.Body>
            <Modal.Footer>
              <Button variant="secondary" onClick={() => this.handleClose()}>
                Close
              </Button>
              {this.state.buttonDisabled === false ? (
                <Button variant="primary" type="submit">
                  Submit
                </Button>
              ) : (
                <Button variant="primary" disabled>
                  Submit
                </Button>
              )}
            </Modal.Footer>
          </Form>
        </Modal>
        <NavBarMenu />
        <Button
          variant="info"
          className="mt-3 mb-3 mr-3"
          style={{ float: "right" }}
          onClick={() => this.handleOpen()}
        >
          <FontAwesomeIcon icon={faFileUpload} className="mr-2" />
          Upload Video
        </Button>
        <Container fluid className="p-0 mt-5">
          {this.state.videosList.length > 0 ? (
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
                  <th style={{ fontSize: "15px", width: "3%" }}>ID</th>
                  <th style={{ fontSize: "15px" }}>VIDEO TITLE</th>
                  <th style={{ fontSize: "15px" }}>VIDEO DESC</th>
                  <th style={{ fontSize: "15px", width: "8%" }}>ACTION</th>
                </tr>
              </thead>
              <tbody>
                {this.state.videosList.map((item, key) => (
                  <tr
                    key={key}
                    className={
                      item.VIDEO_STATUS === "Y"
                        ? "albumstatusactive"
                        : "albumstatusinactive"
                    }
                  >
                    <td
                      style={{
                        verticalAlign: "middle",
                        width: "110px",
                      }}
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
                        {item.ID}
                      </div>
                    </td>
                    <td
                      style={{
                        verticalAlign: "middle",
                        width: "110px",
                      }}
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
                        {item.VIDEO_TITLE}
                      </div>
                    </td>
                    <td
                      style={{
                        verticalAlign: "middle",
                        width: "110px",
                      }}
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
                        {item.VIDEO_DESC}
                      </div>
                    </td>
                    <td
                      style={{
                        fontSize: "13px",
                        fontWeight: "bold",
                        verticalAlign: "middle",
                      }}
                    >
                      <OverlayTrigger
                        placement="top"
                        overlay={
                          <Tooltip>
                            <strong>Play Video</strong>
                          </Tooltip>
                        }
                      >
                        <FontAwesomeIcon
                          className="ml-2"
                          style={{ fontSize: "16px", cursor: "pointer" }}
                          icon={faPlayCircle}
                          onClick={() => {
                            this.openVideoModal(item.VIDEO_NAME);
                          }}
                        />
                      </OverlayTrigger>
                      <OverlayTrigger
                        placement="top"
                        overlay={
                          <Tooltip>
                            <strong>Edit Video</strong>
                          </Tooltip>
                        }
                      >
                        <FontAwesomeIcon
                          className="ml-2"
                          style={{ fontSize: "16px", cursor: "pointer" }}
                          icon={faEdit}
                          onClick={() => {
                            this.getDataToEdit(item.ID);
                          }}
                        />
                      </OverlayTrigger>
                    </td>
                  </tr>
                ))}
              </tbody>
            </Table>
          ) : (
            <Container className="mt-5">
              <Card style={{ marginTop: "70px" }}>
                <Card.Body>
                  <h5>No Videos available at this moment!</h5>
                </Card.Body>
              </Card>
            </Container>
          )}
        </Container>
      </div>
    );
  }
}
export default AddVideoToAlbum;
