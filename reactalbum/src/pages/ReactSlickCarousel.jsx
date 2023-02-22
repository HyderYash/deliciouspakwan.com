import React, { Component } from "react";
import { Container } from "react-bootstrap";
import Slider from "react-slick";
import NoPhotoVideoInCarousel from "../components/NoPhotoVideoInCarousel";

class ReactSlickCarousel extends Component {
  render() {
    if (this.props.albumPhotos.length > 0) {
      return (
        <Container
          fluid
          className="customMarginPaddingForPhone"
          style={{ margin: "0", padding: "0" }}
        >
          <Slider
            {...this.props.settings}
            className="mx-auto my-auto customCarouselCssForPhone"
            style={{
              width: "100%",
              backgroundColor: "black",
            }}
          >
            {this.props.albumPhotos.map((items, key) => {
              return (
                <div style={{ height: "40em", display: "flex" }} key={key}>
                  <img
                    className="d-block mx-auto my-auto"
                    src={`${
                      this.props.API_ROOT_PATH
                    }/images/albums/${localStorage.getItem(
                      "ALBUM_FOLDER_TITLE"
                    )}/${items.IMAGE_THUMB_URL}`}
                    alt="First slide"
                    style={{
                      objectFit: "scale-down",
                      width: "100%",
                      height: "40rem",
                    }}
                  />
                </div>
              );
            })}
          </Slider>
        </Container>
      );
    } else {
      return <NoPhotoVideoInCarousel type="photo" />;
    }
  }
}

export default ReactSlickCarousel;
