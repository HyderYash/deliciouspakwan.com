import React, { useState } from "react";
import { Button, Form, ProgressBar } from "react-bootstrap";
import Message from "../assets/utility/Message";
import { API_ROOT_PATH } from "./Common";
import axios from "axios";

function UploadPhotosInAlbum(props) {
  const [selectedFile, setSelectedFile] = useState("");
  const [message, setMessage] = useState("");
  const [uploadPercentage, setUploadPercentage] = useState(0);
  const onChangeHandler = (event) => {
    setSelectedFile(event.target.files);
  };
  const onClickHandler = async (event) => {
    event.preventDefault();
    setUploadPercentage(0);
    if (selectedFile.length > 8) {
      setMessage(
        `More than 8 files selected (${selectedFile.length} Files Selected)`
      );
      setSelectedFile("");
    } else {
      const formData = new FormData();
      formData.append("albumIntTitle", props.title);
      formData.append("albumId", props.id);
      var i = 1;
      var uploadCounter = 100 / selectedFile.length;
      console.log(selectedFile);
      for (const file of selectedFile) {
        let cssPercent = i * uploadCounter;
        formData.append("myFiles", file);
        await axios
          .post(`${API_ROOT_PATH}/api/albums/uploadAlbumPhotos.php`, formData, {
            headers: {
              "Content-Type": "multipart/form-data",
            },
            onUploadProgress: (ProgressEvent) => {
              setUploadPercentage(cssPercent.toFixed(0));
            },
          })
          .then((res) => {
            setMessage(res.data.message);
            formData.delete("myFiles", file);
          })
          .catch((err) => {
            console.log(err);
          });
        i++;
      }
    }
  };
  const goToAlbumList = () => {
    window.location.href = "/albumlist";
  };
  return (
    <>
      {message ? (
        <div className="mt3 mb-3">
          <Message color="info" message={message} />
        </div>
      ) : null}
      <div className="mt-3 mb-3">
        <ProgressBar
          animated
          now={uploadPercentage}
          label={`${uploadPercentage}%`}
        />
      </div>
      <Form onSubmit={onClickHandler}>
        {uploadPercentage >= 20 ? (
          <div className="mt-2">
            <Form.File
              onChange={onChangeHandler}
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
              onChange={onChangeHandler}
              style={{ textAlign: "left" }}
              accept=".jpg, .png, .jpeg"
              required
              label="Upload Image Files"
              multiple
              custom
            />
          </div>
        )}
        {uploadPercentage > 0 ? (
          <Button type="submit" className="mt-3 mb-3" disabled>
            Upload!
          </Button>
        ) : (
          <Button type="submit" className="mt-3 mb-3">
            Upload!
          </Button>
        )}
        <Button
          variant="primary"
          className="ml-2"
          onClick={() => goToAlbumList()}
        >
          Close
        </Button>
      </Form>
    </>
  );
}

export default UploadPhotosInAlbum;
