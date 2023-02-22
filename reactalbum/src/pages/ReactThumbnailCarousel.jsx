import React, { Component } from "react";
// import { Carousel, Container } from "react-bootstrap";
import NoPhotoVideoInCarousel from "../components/NoPhotoVideoInCarousel";
import "react-image-gallery/styles/css/image-gallery.css";
import ImageGallery from "react-image-gallery";
import { Container } from "react-bootstrap";

class ReactThumbnailCarousel extends Component {
  constructor() {
    super();
    this.state = {
      images: [],
    };
  }
  componentDidMount() {
    if (this.props.albumPhotos) {
      let finalPushArr = [];
      this.props.albumPhotos.forEach((item) => {
        let imgURL = `${
          this.props.API_ROOT_PATH
        }/images/albums/${localStorage.getItem("ALBUM_FOLDER_TITLE")}/${
          item.IMAGE_THUMB_URL
        }`;
        finalPushArr.push({
          original: imgURL,
          thumbnail: imgURL,
          loading: "lazy",
          thumbnailClass: "thumbnailFit",
        });
      });
      this.setState({ images: finalPushArr });
    } else {
      return;
    }
  }
  render() {
    if (this.props.albumPhotos.length > 0) {
      return (
        <Container
          fluid
          className="customMarginPaddingForPhone"
          style={{
            margin: "0",
            padding: "0",
            backgroundColor: "black",
          }}
        >
          <ImageGallery
            items={this.state.images}
            autoPlay={true}
            slideInterval={8000}
            slideDuration={1000}
          />
        </Container>
      );
    } else {
      return <NoPhotoVideoInCarousel type="photo" />;
    }
  }
}

export default ReactThumbnailCarousel;
