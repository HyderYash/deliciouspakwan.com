import React, { useEffect } from "react";
import { useState } from "react";
import {
  Button,
  Card,
  Container,
  Modal,
  OverlayTrigger,
  Table,
  Tooltip,
} from "react-bootstrap";
import { fetchAPIData, API_ROOT_PATH } from "./Common";
import NavBarMenu from "./NavBarMenu";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import {
  faPlusCircle,
  faFileUpload,
  faEdit,
  faList,
  faFileVideo,
} from "@fortawesome/free-solid-svg-icons";
import UploadPhotosInAlbum from "./UploadPhotosInAlbum";
import Spinner from "../assets/utility/Loader";
import AddEditAlbum from "./AddEditAlbum";
import "../assets/css/AlbumList.css";
import UploadVideosInAlbum from "./UploadVideosInAlbum";

function PhotoUpload() {
  const [albumList, setAlbumList] = useState([]);
  const [imageFolderName, setImageFolderName] = useState("");
  const [albumID, setAlbumID] = useState("");
  const [imageFolderDisplayTitle, setImageFolderDisplayTitle] = useState("");
  const [showAddPhotosInAlbumModal, setShowAddPhotosInAlbumModal] =
    useState(false);
  const [showAddEditAlbumModal, setShowAddEditAlbumModal] = useState(false);
  const [fileUploadMode, setFileUploadMode] = useState(null);
  const [loading, setLoading] = useState(true);
  const setAddEditAlbumId = (id) => {
    setAlbumID(id);
  };
  const setAlbumDetailsForPhotoUpload = (id, title, displayTitle) => {
    setImageFolderName(title);
    setAlbumID(id);
    setImageFolderDisplayTitle(displayTitle);
  };
  const setalbumData = (desc, status, imageURL) => {
    sessionStorage.setItem("ALBUM_DESC", desc);
    sessionStorage.setItem("ALBUM_STATUS", status);
    sessionStorage.setItem("ALBUM_THUMBNAIL", imageURL);
  };

  //THIS WILL CALL ON THE PAGE LOAD
  useEffect(() => {
    const formData = {
      DISPLAY: "BE",
    };
    fetchAPIData("/api/albums/album_list.php", formData).then((json) => {
      setAlbumList(json.records);
      setLoading(false);
    });
  }, []);

  //MAIN VIEW
  return (
    <div>
      {loading ? <Spinner /> : null}
      <NavBarMenu />
      {/* PHOTO UPLOAD IN ALBUM MODAL */}
      <Modal
        backdrop="static"
        show={showAddPhotosInAlbumModal}
        onHide={() => setShowAddPhotosInAlbumModal(false)}
        keyboard={false}
        size="lg"
      >
        <Modal.Header>
          <b>
            Upload {fileUploadMode === "video" ? "Videos " : "Images "}in "
            <span style={{ color: "red" }}>{imageFolderDisplayTitle}</span>"
            (Max 80 Files)
          </b>
        </Modal.Header>
        <Modal.Body>
          {fileUploadMode === "video" ? (
            <UploadVideosInAlbum title={imageFolderName} id={albumID} />
          ) : (
            <UploadPhotosInAlbum title={imageFolderName} id={albumID} />
          )}
        </Modal.Body>
      </Modal>
      {/* EDIT ALBUM MODAL */}
      <Modal
        backdrop="static"
        show={showAddEditAlbumModal}
        onHide={() => setShowAddEditAlbumModal(false)}
        keyboard={false}
        size="lg"
      >
        <Modal.Header>
          <b>{albumID > 0 ? "Edit Album" : "Create Album"}</b>
        </Modal.Header>
        <Modal.Body>
          <AddEditAlbum albumId={albumID} />
        </Modal.Body>
      </Modal>
      {/* CREATE ALBUM BUTTON */}
      <Button
        variant="info"
        className="mt-3 mb-3 mr-3"
        style={{ float: "right" }}
        onClick={() => {
          setAddEditAlbumId(0);
          setShowAddEditAlbumModal(true);
        }}
      >
        <FontAwesomeIcon icon={faPlusCircle} className="mr-2" />
        Create Album
      </Button>
      {/* ALBUM LISTING */}
      <div>
        {albumList.length > 0 ? (
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
                <th style={{ fontSize: "15px" }}>THUMB</th>
                <th style={{ fontSize: "15px" }}>TITLE</th>
                <th style={{ fontSize: "15px" }}>ACTION</th>
              </tr>
            </thead>
            <tbody>
              {albumList.map((item, key) => (
                <tr
                  key={key}
                  className={
                    item.STATUS === "Y"
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
                      <img
                        draggable="false"
                        alt="albumThumbnail"
                        style={{
                          maxWidth: "100%",
                          maxHeight: "100%",
                          display: "block",
                          pointerEvents: "none",
                        }}
                        src={`${API_ROOT_PATH}/images/albums/${item.ALBUM_FOLDER_TITLE}/thumbnail/${item.IMAGE_THUMB_URL}`}
                      ></img>
                    </div>
                  </td>
                  <td
                    style={{
                      fontSize: "13px",
                      fontWeight: "bold",
                      verticalAlign: "middle",
                      textAlign: "left",
                    }}
                  >
                    <OverlayTrigger
                      placement="right"
                      overlay={
                        <Tooltip>
                          <strong>Edit Album</strong>
                        </Tooltip>
                      }
                    >
                      <FontAwesomeIcon
                        className="ml-2"
                        style={{ fontSize: "16px" }}
                        icon={faEdit}
                        onClick={() => {
                          setAddEditAlbumId(item.ID);
                          setShowAddEditAlbumModal(true);
                        }}
                      />
                    </OverlayTrigger>
                    <span className="ml-1">
                      {item.ALBUM_DISPLAY_TITLE} ({item.photoCount})
                    </span>
                  </td>
                  <td
                    style={{
                      fontWeight: "900",
                      verticalAlign: "middle",
                    }}
                  >
                    <OverlayTrigger
                      placement="left"
                      overlay={
                        <Tooltip>
                          <strong>Upload Videos in Album</strong>
                        </Tooltip>
                      }
                    >
                      <FontAwesomeIcon
                        icon={faFileVideo}
                        onClick={() => {
                          setFileUploadMode("video");
                          setAlbumDetailsForPhotoUpload(
                            item.ID,
                            item.ALBUM_FOLDER_TITLE,
                            item.ALBUM_DISPLAY_TITLE
                          );
                          setShowAddPhotosInAlbumModal(true);
                        }}
                      />
                    </OverlayTrigger>
                    <OverlayTrigger
                      placement="left"
                      overlay={
                        <Tooltip>
                          <strong>Upload Photos in Album</strong>
                        </Tooltip>
                      }
                    >
                      <FontAwesomeIcon
                        className="ml-3"
                        icon={faFileUpload}
                        onClick={() => {
                          setFileUploadMode("photo");
                          setAlbumDetailsForPhotoUpload(
                            item.ID,
                            item.ALBUM_FOLDER_TITLE,
                            item.ALBUM_DISPLAY_TITLE
                          );
                          setShowAddPhotosInAlbumModal(true);
                        }}
                      />
                    </OverlayTrigger>
                    <OverlayTrigger
                      placement="top"
                      overlay={
                        <Tooltip>
                          <strong>Show Album Details</strong>
                        </Tooltip>
                      }
                    >
                      <a
                        style={{
                          cursor: "pointer",
                          textDecoration: "none",
                          color: "black",
                        }}
                        href={`/editalbum/${
                          item.ALBUM_FOLDER_TITLE
                        }/${item.ALBUM_DISPLAY_TITLE.replace(/\s+/g, "-")}/${
                          item.ID
                        }`}
                      >
                        <FontAwesomeIcon
                          className="ml-3"
                          icon={faList}
                          onClick={() =>
                            setalbumData(
                              item.ALBUM_DESC,
                              item.STATUS,
                              `${API_ROOT_PATH}/images/albums/${item.ALBUM_FOLDER_TITLE}/thumbnail/${item.IMAGE_THUMB_URL}`
                            )
                          }
                        />
                      </a>
                    </OverlayTrigger>
                  </td>
                </tr>
              ))}
            </tbody>
          </Table>
        ) : (
          <Container className="mt-5">
            <Card className="mt-3">
              <Card.Body>
                <h5>No Album available at this moment!</h5>
              </Card.Body>
            </Card>
          </Container>
        )}
      </div>
    </div>
  );
}

export default PhotoUpload;
