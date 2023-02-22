import React, { Component } from "react";
import { Carousel, Container } from "react-bootstrap";
import NoPhotoVideoInCarousel from "../components/NoPhotoVideoInCarousel";

class ReactBootsrapCarousel extends Component {
  render() {
    if (this.props.albumPhotos.length > 0) {
      return (
        <Container
          fluid
          className="customMarginPaddingForPhone"
          style={{ margin: "0", padding: "0" }}
        >
          <Carousel
            className="mx-auto customCarouselCssForPhone"
            prevIcon={
              <span
                aria-hidden="true"
                className="carousel-control-prev-icon"
                style={{
                  height: "30px",
                  width: "30px",
                  borderRadius: "50%",
                }}
              />
            }
            nextIcon={
              <span
                aria-hidden="true"
                className="carousel-control-next-icon"
                style={{
                  height: "30px",
                  width: "30px",
                  borderRadius: "50%",
                }}
              />
            }
            style={{
              width: "100%",
              backgroundColor: "black",
              padding: "0.5rem",
            }}
          >
            {this.props.albumPhotos.map((items, key) => {
              return (
                <Carousel.Item key={key}>
                  <img
                    className="d-block mx-auto"
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
                </Carousel.Item>
              );
            })}
          </Carousel>
        </Container>
      );
    } else {
      return <NoPhotoVideoInCarousel type="photo" />;
    }
  }
}

export default ReactBootsrapCarousel;
