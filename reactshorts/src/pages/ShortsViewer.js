import React, { Component } from "react";
import DesktopShortsViewer from "../components/DesktopShortsViewer";

export default class ShortsViewer extends Component {
  constructor() {
    super();
    this.state = {
      currentShort: {},
      apiRootPath: "",
      noShorts: false,
      allShortIds: [],
    };
    this.returnPrevShort = this.returnPrevShort.bind(this);
    this.returnNextShort = this.returnNextShort.bind(this);
    this.returnFirstShort = this.returnFirstShort.bind(this);
    this.handleEnded = this.handleEnded.bind(this);
  }
  componentDidMount() {
    const shortIds = JSON.parse(sessionStorage.getItem("SHORTS_ID"));
    this.setState({ allShortIds: shortIds });
    const currentShortId = window.location.href.split("/")[4].split("?")[0];
    this.returnFirstShort(currentShortId);
  }
  returnFirstShort(currentShortId) {
    this.setState({
      currentShort: { id: currentShortId },
    });
  }
  returnPrevShort() {
    const currentIndex = this.state.allShortIds.indexOf(
      this.state.currentShort
    );
    let prevIndex;
    if (currentIndex <= 0) {
      prevIndex = this.state.allShortIds.length - 1;
    } else {
      prevIndex = (currentIndex - 1) % this.state.allShortIds.length;
    }
    this.setState({
      currentShort: this.state.allShortIds[prevIndex],
    });
  }
  returnNextShort() {
    const currentIndex = this.state.allShortIds.indexOf(
      this.state.currentShort
    );
    const nextIndex = (currentIndex + 1) % this.state.allShortIds.length;
    this.setState({
      currentShort: this.state.allShortIds[nextIndex],
    });
  }

  handleEnded() {
    const currentIndex = this.state.allShortIds.indexOf(
      this.state.currentShort
    );
    if (currentIndex >= this.state.allShortIds.length - 1) {
      this.returnFirstShort();
    } else {
      this.returnNextShort();
    }
  }

  render() {
    return (
      <div className="flex justify-center items-center h-full">
        <DesktopShortsViewer
          returnPrevShort={this.returnPrevShort}
          returnNextShort={this.returnNextShort}
          handleEnded={this.handleEnded}
          currentShort={this.state.currentShort}
          key={this.state.currentShort}
        />
      </div>
    );
  }
}
