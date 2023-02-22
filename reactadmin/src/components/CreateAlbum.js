import React from "react";
import { useState } from "react";
import {
  Card,
  Container,
  Button,
  Form,
  FormControl,
  ProgressBar,
  Modal,
  Breadcrumb,
} from "react-bootstrap";
import NavBarMenu from "./NavBarMenu";
import axios from "axios";
import { fetchAPIData, API_ROOT_PATH } from "./Common";
import { Link } from "react-router-dom";

function CreateAlbum() {
  const [title, setTitle] = useState("");
  const [desc, setDesc] = useState("");
  const [radioStatus, setradioStatus] = useState("Active");
  const [file, setFile] = useState("");
  const [filename, setFilename] = useState("Choose File");
  const [uploadPercentage, setUploadPercentage] = useState(0);
  const [show, setShow] = useState(false);
  const [albumIntTitleState, setAlbumIntTitleState] = useState("");

  const handleClose = () => setShow(false);
  const handleShow = () => setShow(true);

  const selectFileData = (e) => {
    const albumIntTitle = new Date().getTime();
    setAlbumIntTitleState(albumIntTitle);
    setFile(e.target.files[0]);
    setFilename(e.target.files[0].name);
  };
  const promiseSetUploadedFile = (fileName) => {
    return new Promise((resolve) => {
      resolve(fileName);
    });
  };
  const uploadImage = async (event) => {
    event.preventDefault();
    const formData = new FormData();
    var FinalTitle = title.trim();
    FinalTitle = title.toLowerCase();
    FinalTitle = title.split(" ").join("-");
    formData.append("file", file);
    formData.append("title", FinalTitle);
    formData.append("albumIntTitle", albumIntTitleState);
    await axios
      .post(API_ROOT_PATH + "/api/albums/uploadAlbumThumbnail.php", formData, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
        onUploadProgress: (ProgressEvent) => {
          setUploadPercentage(50);
        },
      })
      .then((res) => {
        setUploadPercentage(75);
        if (res.data.status === "Success") {
          promiseSetUploadedFile(res.data).then((result) => {
            handleSubmit(result).then(function (finalRes) {
              setUploadPercentage(100);
              handleShow();
            });
          });
        }
      })
      .catch((err) => {
        console.log(err);
      });
  };

  const handleSubmit = (data) => {
    return new Promise((resolve) => {
      const UploadedDate = new Date()
        .toISOString()
        .slice(0, 19)
        .replace("T", " ");
      var FinalTitle = title.trim();
      FinalTitle = title.toLowerCase();
      FinalTitle = title.split(" ").join("-");
      const formData = {
        ALBUM_FOLDER_TITLE: albumIntTitleState,
        DESC: desc,
        STATUS: radioStatus,
        IMAGE_THUMB_URL: filename,
        UPLOADED_DATE: UploadedDate,
        ALBUM_DISPLAY_TITLE: FinalTitle,
      };
      fetchAPIData("/api/albums/newAlbums.php", formData)
        .then((res) => {
          document.getElementById("title").value = "";
          document.getElementById("description").value = "";
          resolve(res);
        })
        .catch((err) => {
          console.error(err);
        });
    });
  };
  const setToDefault = () => {
    document.getElementById("title").value = "";
    document.getElementById("description").value = "";
    setFile("");
    setUploadPercentage(0);
    setFilename("Choose File");
    setradioStatus("Active");
  };

  return (
    <div>
      <NavBarMenu />
      <Modal
        backdrop="static"
        show={show}
        onHide={handleClose}
        keyboard={false}
        size="lg"
      >
        <Modal.Header>
          <b>INFORMATION</b>
        </Modal.Header>
        <Modal.Body>
          <h6>You Have made album named {title}..</h6>
          <Breadcrumb>
            <Breadcrumb.Item active>albums</Breadcrumb.Item>
            <Breadcrumb.Item active>{albumIntTitleState}</Breadcrumb.Item>
            <Breadcrumb.Item active>thumbnail</Breadcrumb.Item>
            <Breadcrumb.Item active>{filename}</Breadcrumb.Item>
          </Breadcrumb>
          <p>You can upload images in it in the next page</p>
          <Button as={Link} to="/albumlist">
            Go To Album List
          </Button>
          <Button
            className="ml-3"
            onClick={() => {
              handleClose();
              setToDefault();
            }}
          >
            Close
          </Button>
        </Modal.Body>
      </Modal>
      <Container className="mt-5 mb-5">
        <Card>
          <Card.Body>
            <Card.Title>
              <h2>Create Album</h2>
            </Card.Title>
            <Form onSubmit={uploadImage}>
              <FormControl
                type="text"
                placeholder="Enter Title"
                required
                id="title"
                autoComplete="off"
                onChange={(e) => setTitle(e.target.value)}
                className="mr-sm-2 mt-3"
              />
              <FormControl
                as="textarea"
                placeholder="Enter Description"
                required
                id="description"
                autoComplete="off"
                onChange={(e) => setDesc(e.target.value)}
                className="mr-sm-2 mt-3"
              />
              <h5 className="mt-3 text-left" style={{ marginBottom: "0rem" }}>
                STATUS:
              </h5>
              <div style={{ float: "left", position: "relative" }}>
                <input
                  type="radio"
                  id="active"
                  name="status"
                  value="Y"
                  className="mr-1"
                  required
                  onChange={(event) => setradioStatus(event.target.value)}
                />
                <label htmlFor="male" className="mr-1">
                  Active
                </label>
                <input
                  type="radio"
                  id="inactive"
                  name="status"
                  value="N"
                  className="mr-1"
                  onChange={(event) => setradioStatus(event.target.value)}
                />
                <label htmlFor="female">InActive</label>
              </div>
              <div className="mt-5">
                <Form.File
                  onChange={selectFileData}
                  style={{ textAlign: "left" }}
                  accept=".jpg, .png, .jpeg"
                  required
                  label={filename}
                  custom
                />
              </div>
              <div className="mt-3 mb-3">
                <ProgressBar
                  animated
                  now={uploadPercentage}
                  label={`${uploadPercentage}%`}
                />
              </div>

              <div className="mt-3">
                <Button
                  className="mt-3 mx-auto"
                  type="submit"
                  variant="primary"
                >
                  Submit
                </Button>
              </div>
            </Form>
          </Card.Body>
        </Card>
      </Container>
    </div>
  );
}

export default CreateAlbum;
