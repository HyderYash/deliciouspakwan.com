import React, { Component, Suspense, lazy } from "react";
import {
  API_ROOT_PATH,
  getCarouselData,
  updateVisitCounter,
  getAlbumVideosData,
  getAlbumSongsDisplaySettings,
  isMobile,
} from "../utility/Common";
import Loader from "../components/Loader";
import CarouselHeader from "../components/CarouselHeader";
import VideoPlayer from "../components/VideoPlayer";
import ReactSlickCarousel from "./ReactSlickCarousel";
import ReactBootsrapCarousel from "./ReactBootstrapCarousel";
import MobileHeader from "../components/MobileHeader";
import { Tabs, Tab } from "react-bootstrap";
import ReactThumbnailCarousel from "./ReactThumbnailCarousel";
// import MusicController from "../components/MusicController";
const MusicController = lazy(() => import("../components/MusicController"));

class CarouselContainer extends Component {
  constructor() {
    super();
    this.state = {
      albumPhotos: [],
      albumVideos: [],
      loading: true,
      albumDisplayTitle: "",
      visitCounter: "",
      viewType: "photos",
      showSongPlayer: false,
    };
    this.settings = {
      arrows: true,
      lazyLoad: true,
      infinite: true,
      slidesToShow: 1,
      autoplay: true,
      speed: 500,
      autoplaySpeed: 7000,
      cssEase: "linear",
    };
  }
  componentDidMount() {
    localStorage.clear();
    Promise.all([
      this.getVisitCounter(),
      this.getData(),
      this.getAlbumSongSettings(),
    ]);
  }
  async getVisitCounter() {
    const visitCounter = await updateVisitCounter();
    this.setState({ visitCounter: visitCounter.records.VISIT_COUNTER });
  }
  async getData() {
    const retData = await getCarouselData();
    this.setState({ albumPhotos: retData.albumPhotos });
    this.setState({ albumDisplayTitle: retData.albumDisplayTitle });
    const vidData = await getAlbumVideosData();
    this.setState({ albumVideos: vidData.albumVideos });
    this.setState({ loading: retData.loading });
  }

  async getAlbumSongSettings() {
    const songSetting = await getAlbumSongsDisplaySettings();
    if (songSetting.displaySongPlayer === 0) {
      this.setState({ showSongPlayer: false });
    } else {
      this.setState({ showSongPlayer: true });
    }
  }

  render() {
    let selectedCarousel;
    if (this.props.carouselType === 0) {
      selectedCarousel = (
        <ReactSlickCarousel
          albumPhotos={this.state.albumPhotos}
          settings={this.settings}
          API_ROOT_PATH={API_ROOT_PATH}
        />
      );
    } else if (this.props.carouselType === 1) {
      selectedCarousel = (
        <ReactBootsrapCarousel
          albumPhotos={this.state.albumPhotos}
          API_ROOT_PATH={API_ROOT_PATH}
        />
      );
    } else {
      selectedCarousel = (
        <ReactThumbnailCarousel
          albumPhotos={this.state.albumPhotos}
          API_ROOT_PATH={API_ROOT_PATH}
        />
      );
    }

    if (this.state.loading === true) {
      return <Loader />;
    } else {
      return (
        <>
          {isMobile === true ? (
            <MobileHeader
              visitCounter={this.state.visitCounter}
              albumDisplayTitle={this.state.albumDisplayTitle}
            />
          ) : (
            <CarouselHeader
              visitCounter={this.state.visitCounter}
              albumDisplayTitle={this.state.albumDisplayTitle}
            />
          )}
          <Tabs
            id="controlled-tab-example"
            activeKey={this.state.viewType}
            onSelect={(k) => this.setState({ viewType: k })}
            className="mt-3"
            mountOnEnter={true}
            unmountOnExit={true}
          >
            <Tab eventKey="photos" title="Photos">
              {selectedCarousel}
              {/* {this.props.carouselType === 0 ? (
                <ReactSlickCarousel
                  albumPhotos={this.state.albumPhotos}
                  settings={this.settings}
                  API_ROOT_PATH={API_ROOT_PATH}
                />
              ) : (
                <ReactBootsrapCarousel
                  albumPhotos={this.state.albumPhotos}
                  API_ROOT_PATH={API_ROOT_PATH}
                />
              )} */}
            </Tab>
            <Tab eventKey="videos" title="Videos">
              <VideoPlayer
                albumVideos={this.state.albumVideos}
                API_ROOT_PATH={API_ROOT_PATH}
              />
            </Tab>
          </Tabs>
          <Suspense fallback={<div>Loading...</div>}>
            {this.state.albumPhotos.length > 0 &&
            this.state.showSongPlayer === true ? (
              <MusicController albumLength={this.state.albumPhotos.length} />
            ) : null}
          </Suspense>
        </>
      );
    }
  }
}

export default CarouselContainer;
