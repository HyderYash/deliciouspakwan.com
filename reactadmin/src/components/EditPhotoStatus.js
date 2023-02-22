import React, { useState } from "react";
import { Form, Button } from "react-bootstrap";
import Message from "../assets/utility/Message";
import { fetchAPIData } from "./Common";

function EditPhotoStatus(props) {
  const [photoStatus, setPhotoStatus] = useState(props.photoStatus);
  const [message, setMessage] = useState("");
  const photoId = props.id;
  const imageUrl = props.imageUrl;
  const goToEditAlbum = () => {
    window.location.href = props.editAlbumUrl;
  };
  const handleSubmit = (e) => {
    e.preventDefault();
    const formData = {
      PHOTO_STATUS: photoStatus,
      ID: photoId,
    };
    fetchAPIData("/api/albums/updatePhotoStatus.php", formData)
      .then((res) => {
        console.log(res);
        setMessage(res.message);
      })
      .catch((err) => {
        console.error(err);
      });
  };
  return (
    <div>
      {message !== "" ? <Message color="success" message={message} /> : null}
      <Form onSubmit={handleSubmit}>
        <div style={{ width: "100%" }}>
          <p className="mt-1 text-left" style={{ marginBottom: "0rem" }}>
            Photo:
          </p>
          <img height="150px" src={imageUrl} alt="ALBUM_THUMB"></img>
        </div>
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
            checked={photoStatus === "Y"}
            onChange={(e) => setPhotoStatus(e.target.value)}
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
            checked={photoStatus === "N"}
            onChange={(e) => setPhotoStatus(e.target.value)}
          />
          <label htmlFor="female">InActive</label>
        </div>
        <div style={{ marginTop: "38px" }}>
          <Button variant="primary" type="submit" className="mt-2">
            Submit
          </Button>
          <Button
            variant="primary"
            className="ml-2 mt-2"
            onClick={() => goToEditAlbum()}
          >
            Close
          </Button>
        </div>
      </Form>
    </div>
  );
}

export default EditPhotoStatus;
