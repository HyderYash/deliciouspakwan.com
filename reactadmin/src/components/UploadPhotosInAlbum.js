import React, { Component } from "react";
import { Alert, Badge, Button, Form, ProgressBar } from "react-bootstrap";
import { API_ROOT_PATH } from "./Common";
import axios from "axios";

class UploadPhotosInAlbum extends Component {
  constructor(props) {
    super(props);
    this.state = {
      selectedFile: "",
      message: "",
      uploadPercentage: 0,
      isUploading: false,
      fileDetails: "",
      allFilesUploaded: false,
      totalNumberOfFiles: "",
      uploadFileNum: "",
      queNumberOfFile: 0,
      fileUploaded: "",
      closeBtnEnabled: true,
    };
  }
  onChangeHandler = (event) => {
    this.setState({ selectedFile: event.target.files });
  };
  goToAlbumList = () => {
    window.location.href = "/albumlist";
  };
  onClickHandler = async (event) => {
    event.preventDefault();
    this.setState({ uploadPercentage: 0 });
    console.log(this.state.selectedFile.length);
    if (this.state.selectedFile.length > 80) {
      this.setState({
        message: `Error: More than 80 files selected (${this.state.selectedFile.length} Files Selected)`,
      });
      this.setState({ selectedFile: "" });
    } else {
      const formData = new FormData();
      formData.append("albumIntTitle", this.props.title);
      formData.append("albumId", this.props.id);
      this.setState({ totalNumberOfFiles: this.state.selectedFile.length });
      let i = 1;
      let uploadCounter = 100 / this.state.selectedFile.length;
      let fileCounter = 0;
      this.setState({ closeBtnEnabled: false });
      for (const file of this.state.selectedFile) {
        this.setState({ fileUploaded: "Processing..." });
        console.log("here");
        this.setState({ fileDetails: file.name });
        this.setState({ isUploading: true });
        formData.append("myFiles", file);
        await axios
          .post(`${API_ROOT_PATH}/api/albums/uploadAlbumPhotos.php`, formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          })
          // eslint-disable-next-line
          .then(() => {
            let cssPercent = i * uploadCounter;
            fileCounter += 1;
            this.setState({ queNumberOfFile: fileCounter });
            this.setState({ uploadPercentage: cssPercent.toFixed(0) });
            // this.setState({ fileUploaded: res.data.message });
            this.setState({ fileUploaded: "Done... Please close this popup." });
            console.log("here2");
            formData.delete("myFiles", file);
            if (fileCounter === this.state.totalNumberOfFiles) {
              this.setState({ allFilesUploaded: true });
              this.setState({ closeBtnEnabled: true });
            }
            i++;
          })
          .catch((err) => {
            console.log(err);
          });
      }
    }
  };
  render() {
    return (
      <>
        {this.state.isUploading ? (
          <>
            {this.state.allFilesUploaded ? (
              <div className="mt-2 mb-2">
                <h3>
                  <Badge className="bg-success text-white" pill>
                    All Photos Uploaded
                  </Badge>
                </h3>
              </div>
            ) : (
              <div className="mt-2 mb-2">
                <h4>
                  <Badge className="bg-primary text-white" pill>
                    Uploading Started...
                  </Badge>
                </h4>
                <span style={{ color: "red" }}>
                  Please don't refresh the page.
                </span>
              </div>
            )}
            <div>
              <ProgressBar
                animated
                now={this.state.uploadPercentage}
                label={`${this.state.uploadPercentage}%`}
              />
            </div>
            <div style={{ textAlign: "center" }} className="mt-2 mb-2">
              <span style={{ fontSize: "20px", fontWeight: "bold" }}>
                <Badge className="bg-success text-white" pill>
                  {this.state.queNumberOfFile}
                </Badge>{" "}
                of{" "}
                <Badge className="bg-danger text-white" pill>
                  {this.state.totalNumberOfFiles}
                </Badge>{" "}
                Uploaded
              </span>
            </div>
            <Alert
              variant="success"
              style={{
                backgroundColor: "#55E6C1",
                color: "black",
                fontWeight: "bold",
              }}
            >
              {this.state.fileUploaded === "Processing..." ? (
                <span>Current Photo: {this.state.fileDetails}</span>
              ) : null}

              <div className="mt-2 mb-2">
                <h4>
                  <Badge className="bg-primary text-white">
                    {this.state.fileUploaded}
                  </Badge>
                </h4>
              </div>
            </Alert>
          </>
        ) : null}
        {this.state.message ? (
          <div className="mt3 mb-3">
            <Alert
              style={{
                backgroundColor: "lightcoral",
                color: "black",
                fontWeight: "bold",
              }}
            >
              {this.state.message}{" "}
            </Alert>
          </div>
        ) : null}
        <Form onSubmit={this.onClickHandler}>
          {this.state.uploadPercentage >= 20 ? (
            <div className="mt-2">
              <Form.File
                onChange={this.onChangeHandler}
                style={{ textAlign: "left" }}
                accept=".jpg, .png, .jpeg"
                required
                label="Upload Image Files"
                multiple
                custom
                disabled
              />
            </div>
          ) : (
            <div className="mt-2">
              <Form.File
                onChange={this.onChangeHandler}
                style={{ textAlign: "left" }}
                accept=".jpg, .png, .jpeg"
                required
                label="Upload Image Files"
                multiple
                custom
              />
            </div>
          )}
          {this.state.uploadPercentage > 0 ? (
            <Button type="submit" className="mt-3 mb-3" disabled>
              Upload!
            </Button>
          ) : (
            <Button type="submit" className="mt-3 mb-3">
              Upload!
            </Button>
          )}
          {this.state.closeBtnEnabled ? (
            <Button
              variant="primary"
              className="ml-2"
              onClick={() => this.goToAlbumList()}
            >
              Close
            </Button>
          ) : (
            <Button variant="primary" className="ml-2" disabled>
              Close
            </Button>
          )}
        </Form>
      </>
    );
  }
}

export default UploadPhotosInAlbum;
