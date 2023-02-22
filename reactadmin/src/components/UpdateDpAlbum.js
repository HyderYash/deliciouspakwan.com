import React, { useState } from "react";
import { Button, Card, Container } from "react-bootstrap";
import { fetchAPIData } from "./Common";
import NavBarMenu from "./NavBarMenu";
import Message from "../assets/utility/Message";
import Spinner from "../assets/utility/Loader";

function UpdateDpAlbum() {
  const [message, setMessage] = useState("");
  const [loading, setLoading] = useState(false);

  const downloadYTThumbnails = () => {
    setMessage("Downloading...");
    console.log("here");
    fetchAPIData("/api/albums/downloadDPAlbumThumbnail.php").then((json) => {
      console.log(json);
      setLoading(false);
      setMessage(json.message);
    });
    // dpAlbumsList.map((item, index) => {
    //   const YTThumbnailList = {
    //     ID: item.ID,
    //     videoId: item.VIDEO_ID,
    //   };
    //   Array(YTThumbnailList).forEach((item) => {
    //     console.log(item);
    //     fetchAPIData(
    //       "/api/albums/downloadDPAlbumThumbnail.php",
    //       item,
    //       "POST"
    //     ).then((json) => {
    //       setMessage(`${index}. ${item.videoId}.jpg Downloaded...`);
    //       console.log(json);
    //     });
    //   });

    //   return YTThumbnailList;
    // });
    // console.log("Download finishes");
  };
  return (
    <div>
      <NavBarMenu />
      {loading ? <Spinner /> : null}
      {message ? (
        <div className="mt3 mb-3">
          <Message color="info" message={message} />
        </div>
      ) : null}
      <Container className="mt-5">
        <Card>
          <Card.Body>
            <h3>
              <strong>Download Thumbnails</strong>
            </h3>
            <Card.Text>
              <em>Click The Button Below to Download the Thumbnails</em>
            </Card.Text>
            <Button
              variant="primary"
              size="lg"
              block
              onClick={() => {
                downloadYTThumbnails();
                setLoading(true);
              }}
            >
              <b>DOWNLOAD!</b>
            </Button>
          </Card.Body>
        </Card>
      </Container>
    </div>
  );
}

export default UpdateDpAlbum;
