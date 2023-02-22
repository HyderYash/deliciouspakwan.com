import React, { Component } from "react";
import { Button, Container } from "react-bootstrap";
import NoPhotoVideoInCarousel from "./NoPhotoVideoInCarousel";

export default class VideoPlayer extends Component {
  constructor(props) {
    super(props);
    this.props = props;
    this.state = {
      currentVideo: {},
      apiRootPath: "",
      noVideos: false,
    };
    this.returnPrevVideo = this.returnPrevVideo.bind(this);
    this.returnNextVideo = this.returnNextVideo.bind(this);
    this.returnFirstVideo = this.returnFirstVideo.bind(this);
  }
  componentDidMount() {
    if (this.props.albumVideos === false) {
      this.setState({ noVideos: true });
    } else {
      this.returnFirstVideo();
    }
  }
  returnFirstVideo() {
    this.setState({
      currentVideo: this.props.albumVideos[0],
    });
  }
  returnPrevVideo() {
    const currentIndex = this.props.albumVideos.indexOf(
      this.state.currentVideo
    );
    let prevIndex;
    if (currentIndex <= 0) {
      prevIndex = this.props.albumVideos.length - 1;
    } else {
      prevIndex = (currentIndex - 1) % this.props.albumVideos.length;
    }
    this.setState({
      currentVideo: this.props.albumVideos[prevIndex],
    });
  }
  returnNextVideo() {
    const currentIndex = this.props.albumVideos.indexOf(
      this.state.currentVideo
    );
    const nextIndex = (currentIndex + 1) % this.props.albumVideos.length;
    this.setState({
      currentVideo: this.props.albumVideos[nextIndex],
    });
  }

  render() {
    if (this.props.albumVideos.length > 0 && this.state.noVideos === false) {
      return (
        <>
          <div>
            <Container fluid style={{ height: "612px", padding: "0px" }}>
              <video
                controls
                loop
                style={{
                  objectFit: "scale-down !important",
                  width: "100% !important",
                  height: "100%",
                  backgroundColor: "black",
                  zIndex: "-1",
                }}
                key={this.state.currentVideo}
                src={`${
                  this.props.API_ROOT_PATH
                }/images/albums/${localStorage.getItem("ALBUM_FOLDER_TITLE")}/${
                  this.state.currentVideo.IMAGE_THUMB_URL
                }`}
              />
            </Container>
          </div>
          <Container
            fluid
            style={{
              marginTop: "28px",
              padding: "5px",
              display: "flex",
              justifyContent: "space-evenly",
              backgroundColor: "black",
            }}
          >
            <Button onClick={this.returnPrevVideo} variant="primary">
              {"👈 Prev"}
            </Button>
            <Button onClick={this.returnNextVideo} variant="primary">
              {"Next 👉"}
            </Button>
          </Container>
        </>
      );
    } else {
      return <NoPhotoVideoInCarousel type="video" />;
    }
  }
}
