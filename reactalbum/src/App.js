import React, { Component } from "react";
import { Route, BrowserRouter, Switch } from "react-router-dom";
import { isMobile } from "react-device-detect";
import Home from "./pages/Home";
import NavbarComponent from "./components/NavbarComponent";
import NotFound from "./pages/NotFound";
import { ALBUM_CAROUSEL_TYPE, fetchAPIData } from "./utility/Common";
import CarouselContainer from "./pages/CarouselContainer";
import Footer from "./components/Footer";

export default class App extends Component {
  constructor() {
    super();
    //! 0 => <ReactSlickCarousel />
    //! 1 => <ReactBootstrapCarousel />
    this.state = {
      currentCarousel: null,
      visitCounter: "",
    };
  }
  componentDidMount() {
    Promise.all([this.updateAlbumVisitCounter(), this.getCarouselType()]);
  }
  async updateAlbumVisitCounter() {
    await fetchAPIData("/api/albums/update_albums_view_counter.php", "", "GET")
      .then((json) => {
        this.setState({ visitCounter: json.records.ALBUMS_VIEW_COUNTER });
      })
      .catch((err) => {
        console.log(err);
      });
  }
  async getCarouselType() {
    const formData = {
      DISPLAY_NAME: ALBUM_CAROUSEL_TYPE,
    };
    await fetchAPIData(
      "/api/albums/get_react_comp_setting.php",
      formData,
      "POST"
    )
      .then((json) => {
        if (json.status === "Success") {
          const mobileDevice = json.records.MOBILE;
          const desktopDevice = json.records.DESKTOP;
          if (isMobile) {
            this.setState({ currentCarousel: parseInt(mobileDevice) });
          } else {
            this.setState({ currentCarousel: parseInt(desktopDevice) });
          }
        } else {
          if (isMobile) {
            this.setState({ currentCarousel: 0 });
          } else {
            this.setState({ currentCarousel: 0 });
          }
        }
      })
      .catch((err) => {
        console.log(err);
      });
  }

  render() {
    if (this.state.currentCarousel !== null) {
      return (
        <div>
          <BrowserRouter>
            <Switch>
              <Route path="/" exact>
                <NavbarComponent />
                <Home />
              </Route>
              <Route
                exact
                path="/albumcarousel/:foldertitle/:id/:albumaccessinfo/:adminkey/:albumtype"
                render={() => (
                  <CarouselContainer
                    carouselType={this.state.currentCarousel}
                  />
                )}
              />

              <Route component={NotFound} />
            </Switch>
          </BrowserRouter>
          <Footer albumVisitCounter={this.state.visitCounter} />
        </div>
      );
    } else {
      return null;
    }
  }
}
