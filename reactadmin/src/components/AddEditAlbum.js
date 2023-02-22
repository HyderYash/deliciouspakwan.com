import React, { Component } from "react";
import { fetchAPIData, API_ROOT_PATH } from "./Common";
import { Form, Button, FormControl, Spinner } from "react-bootstrap";
import axios from "axios";
import Message from "../assets/utility/Message";

class AddEditAlbum extends Component {
  constructor() {
    super();
    this.state = {
      albumId: "",
      ALBUM_DISPLAY_TITLE: "",
      ALBUM_DESC: "",
      STATUS: "",
      ALBUM_TYPE: "",
      ALBUM_FOLDER_TITLE: new Date().getTime(),
      IMAGE_THUMB_URL: "",
      albumThumbCondition: "prev",
      file: "",
      fileName: "Choose File",
      uploadPercentage: 0,
      message: "",
      loading: false,
    };
    this.selectFileData.bind(this);
  }
  componentDidMount() {
    this.setState({ albumId: this.props.albumId });
    if (this.props.albumId > 0) {
      this.getAlbumDataById(this.props.albumId);
    } else {
      this.setState({ albumThumbCondition: "new" });
    }
  }
  getAlbumDataById(albumId) {
    const formData = {
      albumId: albumId,
    };
    fetchAPIData("/api/albums/album_details.php", formData)
      .then((json) => {
        this.setState({
          ALBUM_DISPLAY_TITLE: json.records.ALBUM_DISPLAY_TITLE,
        });
        this.setState({ ALBUM_DESC: json.records.ALBUM_DESC });
        this.setState({ STATUS: json.records.STATUS });
        this.setState({ ALBUM_TYPE: json.records.ALBUM_TYPE });
        this.setState({
          ALBUM_FOLDER_TITLE: json.records.ALBUM_FOLDER_TITLE,
        });
        this.setState({ IMAGE_THUMB_URL: json.records.IMAGE_THUMB_URL });
      })
      .catch((err) => {
        console.log(err);
      });
  }

  //ON BROWSE IMAGE
  selectFileData = (e) => {
    this.setState({ file: e.target.files[0] });
    this.setState({ fileName: e.target.files[0].name });
  };

  uploadAlbumThumbnail = async (event) => {
    event.preventDefault();
    if (this.state.albumThumbCondition === "new") {
      const formData = new FormData();
      var FinalTitle = this.state.ALBUM_DISPLAY_TITLE.trim();
      FinalTitle = this.state.ALBUM_DISPLAY_TITLE.toLowerCase();
      FinalTitle = this.state.ALBUM_DISPLAY_TITLE.split(" ").join("-");
      formData.append("file", this.state.file);
      formData.append("title", FinalTitle);

      formData.append("albumIntTitle", this.state.ALBUM_FOLDER_TITLE);
      this.setState({ loading: true });
      await axios
        .post(
          API_ROOT_PATH + "/api/albums/uploadAlbumThumbnail.php",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
        .then((res) => {
          if (res.data.status === "Success") {
            this.promiseSetUploadedFile(res.data).then((result) => {
              this.setState({ loading: false });
              this.updateAlbumInfoInDB(result).then(function (finalRes) {});
            });
          }
        })
        .catch((err) => {
          console.log(err);
        });
    } else {
      this.updateAlbumInfoInDB(true).then(function (finalRes) {});
    }
  };
  promiseSetUploadedFile = (fileName) => {
    return new Promise((resolve) => {
      resolve(fileName);
    });
  };

  updateAlbumInfoInDB = (data) => {
    return new Promise((resolve) => {
      var albumThumbnailUrl = "";
      if (this.state.albumId > 0) {
        if (this.state.albumThumbCondition === "prev") {
          albumThumbnailUrl = this.state.IMAGE_THUMB_URL;
        } else {
          albumThumbnailUrl = this.state.fileName;
        }
      } else {
        albumThumbnailUrl = this.state.fileName;
      }
      const uploadedDate = new Date()
        .toISOString()
        .slice(0, 19)
        .replace("T", " ");
      var albumDisplayTitle = this.state.ALBUM_DISPLAY_TITLE.trim();
      const formData = {
        ALBUM_FOLDER_TITLE: this.state.ALBUM_FOLDER_TITLE,
        DESC: this.state.ALBUM_DESC,
        STATUS: this.state.STATUS,
        ALBUM_TYPE: this.state.ALBUM_TYPE,
        IMAGE_THUMB_URL: albumThumbnailUrl,
        UPLOADED_DATE: uploadedDate,
        ALBUM_DISPLAY_TITLE: albumDisplayTitle,
      };
      this.setState({ loading: true });
      fetchAPIData("/api/albums/newAlbums.php", formData)
        .then((res) => {
          resolve(res);
          console.log(res);
          this.setState({ message: res.message });
          this.setState({ loading: false });
        })
        .catch((err) => {
          console.error(err);
        });
    });
  };
  goToAlbumList = () => {
    window.location.href = "/albumlist";
  };
  render() {
    return (
      <div>
        {this.state.message !== "" ? (
          <Message message={this.state.message} />
        ) : null}
        <Form onSubmit={this.uploadAlbumThumbnail}>
          <Form.Group>
            <FormControl
              type="text"
              value={this.state.ALBUM_DISPLAY_TITLE}
              placeholder="Enter New Album Name"
              required
              onChange={(e) =>
                this.setState({ ALBUM_DISPLAY_TITLE: e.target.value })
              }
            />
            <FormControl
              as="textarea"
              placeholder="Enter New Description"
              required
              value={this.state.ALBUM_DESC}
              autoComplete="off"
              onChange={(e) => this.setState({ ALBUM_DESC: e.target.value })}
              className="mr-sm-2 mt-3"
            />
            <div
              className="mt-2"
              style={{ float: "left", position: "relative", width: "100%" }}
            >
              <input
                type="radio"
                value="Y"
                className="mr-1"
                required
                checked={this.state.STATUS === "Y"}
                onChange={(e) => this.setState({ STATUS: e.target.value })}
              />
              <label htmlFor="male" className="mr-1">
                Active
              </label>
              <input
                type="radio"
                value="N"
                className="mr-1"
                checked={this.state.STATUS === "N"}
                onChange={(e) => this.setState({ STATUS: e.target.value })}
              />
              <label htmlFor="female">InActive</label>
            </div>
            <div style={{ float: "left", position: "relative" }}>
              <input
                type="radio"
                value="PB"
                className="mr-1"
                required
                checked={this.state.ALBUM_TYPE === "PB"}
                onChange={(e) => this.setState({ ALBUM_TYPE: e.target.value })}
              />
              <label htmlFor="male" className="mr-1">
                Public
              </label>
              <input
                type="radio"
                value="PR"
                className="mr-1"
                checked={this.state.ALBUM_TYPE === "PR"}
                onChange={(e) => this.setState({ ALBUM_TYPE: e.target.value })}
              />
              <label htmlFor="female">Private</label>
            </div>
            {this.state.albumId > 0 ? (
              <>
                <div
                  style={{
                    width: "100%",
                    display: "inline-grid",
                  }}
                >
                  <p className="text-left" style={{ marginBottom: "0rem" }}>
                    Previous Thumbnail:
                  </p>
                  <img
                    height="150px"
                    src={`${API_ROOT_PATH}/images/albums/${this.state.ALBUM_FOLDER_TITLE}/thumbnail/${this.state.IMAGE_THUMB_URL}`}
                    alt="ALBUM_THUMB"
                  ></img>
                </div>
                <div style={{ float: "left", position: "relative" }}>
                  <input
                    type="radio"
                    value="prev"
                    className="mr-1"
                    required
                    checked={this.state.albumThumbCondition === "prev"}
                    onChange={(e) =>
                      this.setState({ albumThumbCondition: e.target.value })
                    }
                  />
                  <label className="mr-1">Keep Previous Thumbnail</label>
                  <br />
                  <input
                    type="radio"
                    value="new"
                    className="mr-1"
                    checked={this.state.albumThumbCondition === "new"}
                    onChange={(e) =>
                      this.setState({ albumThumbCondition: e.target.value })
                    }
                  />
                  <label>Upload New Thumbnail</label>
                </div>
                <div className="mt-3">
                  {this.state.albumThumbCondition === "new" ? (
                    <Form.File
                      onChange={this.selectFileData}
                      style={{ textAlign: "left" }}
                      accept=".jpg, .png, .jpeg"
                      required
                      label={this.state.fileName}
                      custom
                    />
                  ) : (
                    <Form.File
                      style={{ textAlign: "left" }}
                      accept=".jpg, .png, .jpeg"
                      required
                      label="Select File"
                      custom
                      disabled
                    />
                  )}
                </div>
              </>
            ) : (
              <div className="mt-3">
                <Form.File
                  onChange={this.selectFileData}
                  style={{ textAlign: "left" }}
                  accept=".jpg, .png, .jpeg"
                  required
                  label={this.state.fileName}
                  custom
                />
              </div>
            )}
          </Form.Group>
          {this.state.loading ? (
            <Button variant="primary" disabled>
              <Spinner
                as="span"
                animation="border"
                className="mr-2"
                size="sm"
                role="status"
                aria-hidden="true"
              />
              Loading...
            </Button>
          ) : (
            <Button variant="primary" type="submit">
              Submit
            </Button>
          )}
          <Button
            variant="primary"
            className="ml-2"
            onClick={() => this.goToAlbumList()}
          >
            Close
          </Button>
        </Form>
      </div>
    );
  }
}
export default AddEditAlbum;
