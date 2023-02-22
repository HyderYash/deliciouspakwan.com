import React, { Component } from "react";
import { ALBUM_SONG_LOCATION_PATH, fetchAPIData } from "../utility/Common";

export default class MusicController extends Component {
  constructor() {
    super();
    this.state = {
      songsList: [],
      currentSongIndex: 0,
      currentSongSrc: "",
    };
    this.handleEnded = this.handleEnded.bind(this);
  }

  getSongList = () => {
    const formData = {
      albumLength: this.props.albumLength,
    };
    fetchAPIData("/api/albums/album_songs.php", formData, "POST")
      .then((json) => {
        let updatedSongsList = [];
        json.records.forEach((item) => {
          updatedSongsList.push(`${ALBUM_SONG_LOCATION_PATH}${item}`);
        });
        this.setState({ songsList: updatedSongsList });
      })
      .catch((err) => {
        console.log(err);
      });
  };

  componentDidMount() {
    this.getSongList();
  }

  audioTagRerender() {
    this.refs.audio.pause();
    this.refs.audio.load();
    this.refs.audio.play();
  }

  handleEnded() {
    if (this.state.currentSongIndex >= this.state.songsList.length - 1) {
      this.setState({ currentSongIndex: 0 });
    } else {
      this.setState({ currentSongIndex: this.state.currentSongIndex + 1 });
    }
    this.audioTagRerender();
  }

  render() {
    if (this.state.songsList.length > 0) {
      return (
        <div>
          <audio
            autoPlay
            controls
            id="backgroundMusic"
            ref="audio"
            onEnded={this.handleEnded}
          >
            <source
              src={this.state.songsList[this.state.currentSongIndex]}
              type="audio/mpeg"
            />
            Your browser does not support the audio element.
          </audio>
        </div>
      );
    } else {
      return "YASh";
    }
  }
}
